<?php

declare(strict_types=1);

/**
 * Converts every Markdown source in `public_content/` and `messages/` into a sibling .html
 * fragment (content.md -> content.html), ready for pages to read with
 * file_get_contents() — the server never parses Markdown per request.
 *
 * The .html files are committed build artifacts: they are generated
 * locally (composer compile-content), verified fresh by CI, and deployed
 * as-is — the same contract as the minified stylesheets and scripts.
 *
 * Standalone by design (no bootstrap): conversion needs no environment,
 * database, or session — only the Composer autoloader.
 */
require_once __DIR__ . '/../vendor/autoload.php';

use Eightfold\Content\Compiler;

$converted = 0;
$root = dirname(__DIR__);

if (is_dir($root . '/public_content') === false or is_dir($root . '/messages') === false) {
    echo 'public_content or messages directories not found' . PHP_EOL;
    exit();
}

if (is_dir($root . '/config') === false or file_exists($root . '/config/.env-local') === false) {
    echo 'no valid environment found' . PHP_EOL;
    exit();
}

foreach (['public_content', 'messages'] as $directory) {
    echo 'compiling ' . $domain . '/' . $directory . '/:' . PHP_EOL;

    $entries = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(
            $root . '/' . $directory,
            FilesystemIterator::SKIP_DOTS
        )
    );

    foreach ($entries as $entry) {
        if (
            $entry instanceof SplFileInfo === false or
            $entry->getExtension() !== 'md' or
            str_starts_with($entry->getPathname(), $root . '/messages/email/')
        ) {
            continue;
        }

        $source   = $entry->getPathname();
        $markdown = file_get_contents($source);
        if (is_string($markdown) === false) {
            throw new RuntimeException('Could not read Markdown source: ' . $source);
        }

        $target = substr($source, 0, -3) . '.html';
        $html = Compiler::compile($markdown);
        if (file_put_contents($target, $html) === false) {
            throw new RuntimeException('Could not write compiled fragment: ' . $target);
        }

        echo '    '
            . substr($source, strlen($root)) . ' -> ' . basename($target) . PHP_EOL;
        $converted++;
    }
}

if ($converted === 0) {
    echo 'no markdown sources found (nothing to compile)' . PHP_EOL;
}
