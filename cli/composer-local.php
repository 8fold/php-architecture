<?php

declare(strict_types=1);

/**
 * Development Environment Bootstrap Script
 *
 * Usage:
 *   composer local                             Run default prep (git hooks + site build)
 *   composer local user@example.com            Prep + seed local account
 *   composer local graph                       Prep + refresh Knowledge Graph
 *   composer local user@example.com graph      Prep + seed account + refresh Knowledge Graph
 *   composer local no-build                    Skip resource build step
 */

// 1. Smart Defaults
$build = true;
$graph = false;
$seedEmail = null;

// 2. Parse Positional Tokens & Arguments
$args = array_slice($argv, 1);

foreach ($args as $arg) {
    $token = strtolower(trim($arg));

    if ($token === 'graph' || $token === '--graph') {
        $graph = true;
    } elseif ($token === 'no-build' || $token === '--no-build') {
        $build = false;
    } elseif ($token === 'build' || $token === '--build') {
        $build = true;
    } elseif (filter_var($arg, FILTER_VALIDATE_EMAIL) !== false) {
        $seedEmail = $arg;
    }
}

// 3. Execution Pipeline
echo "Preparing 8fold local environment...\n";

// Configure Git Hooks
passthru('git config core.hooksPath git');

// A. Build Site Resources (Default: Enabled)
if ($build) {
    echo "\nBuilding site resources...\n";
    passthru('composer build');
} else {
    echo "\nSkipping site resource build.\n";
}

// B. Seed Account (If email argument provided)
if ($seedEmail !== null) {
    echo "\nSeeding local account: {$seedEmail}\n";
    passthru('php cli/seed-local-account.php ' . escapeshellarg($seedEmail));
}

// C. Refresh Knowledge Graph (If 'graph' argument provided)
if ($graph) {
    echo "\nRefreshing Graphify knowledge graph...\n";
    passthru('composer graph:refresh');
}

echo "\nLocal environment ready!\n";
