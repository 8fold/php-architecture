<?php

/**
 * Refreshes code in the existing Graphify knowledge graph.
 *
 * Usage: php cli/graph.php code
 */

declare(strict_types=1);

$fail = static function (string $message): never {
    fwrite(STDERR, "\n[graph] FAILED: {$message}\n");
    fwrite(STDERR, "[graph] The knowledge graph may be stale.\n");
    exit(1);
};

$findCommand = static function (string $name): ?string {
    $dirs = explode(PATH_SEPARATOR, (string) getenv('PATH'));
    $home = getenv('HOME');
    if ($home !== false && $home !== '') {
        $dirs[] = $home . '/.local/bin';
    }

    foreach ($dirs as $dir) {
        if ($dir === '') {
            continue;
        }
        $path = rtrim($dir, '/') . '/' . $name;
        if (is_file($path) && is_executable($path)) {
            return $path;
        }
    }
    return null;
};

if (!chdir(dirname(__DIR__))) {
    $fail('could not change to the repository root.');
}

if (count($argv) !== 2 || $argv[1] !== 'code') {
    $fail('usage: php cli/graph.php code');
}

$graph = 'graphify-out/graph.json';
if (!is_file($graph)) {
    $fail(
        'no knowledge graph at ' . $graph . '. This command refreshes an existing graph;'
        . ' follow docs/graphify-setup.md to create one.'
    );
}

$graphify = $findCommand('graphify')
    ?? $fail(
        'graphify is not on PATH or in ~/.local/bin. '
        . 'See docs/graphify-setup.md#local-tooling.'
    );

echo "[graph] Refreshing code with the Graphify CLI.\n";
passthru(escapeshellarg($graphify) . ' update .', $code);
if ($code !== 0) {
    $fail("graphify update exited with code {$code}.");
}
echo "[graph] Code refresh finished. Check Graphify warnings; documentation may still be stale.\n";
