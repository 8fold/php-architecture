<?php

declare(strict_types=1);

/**
 * Minifies every source stylesheet and script in public/assets, writing a
 * sibling .min file (time-journal.css -> time-journal.min.css). Files whose
 * name already ends in .min are treated as artifacts and skipped.
 *
 * The .min files are committed build artifacts: they are generated locally
 * (composer minify), verified fresh by CI, and deployed as-is — no
 * compiling or transpiling happens on the server.
 *
 * Standalone by design (no bootstrap): minification needs no environment,
 * database, or session — only the Composer autoloader.
 */

use MatthiasMullie\Minify;

require_once __DIR__ . '/../vendor/autoload.php';

$assetTargets = [
    [__DIR__ . '/../public/assets/css', 'css', Minify\CSS::class],
    [__DIR__ . '/../public/assets/js', 'js', Minify\JS::class],
];

$minified = 0;

foreach ($assetTargets as [$directory, $extension, $minifierClass]) {
    if (!is_dir($directory)) {
        continue;
    }

    foreach (glob($directory . '/*.' . $extension) ?: [] as $source) {
        if (str_ends_with(basename($source), '.min.' . $extension)) {
            continue;
        }

        $target = substr($source, 0, -(strlen($extension) + 1)) . '.min.' . $extension;

        $minifier = new $minifierClass($source);
        $minifier->minify($target);

        echo 'minified: ' . basename($source) . ' -> ' . basename($target) . PHP_EOL;
        $minified++;
    }
}

if ($minified === 0) {
    echo 'no source assets found (nothing to minify)' . PHP_EOL;
}
