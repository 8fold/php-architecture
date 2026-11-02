<?php

/**
 * Refreshes the existing Graphify knowledge graph for `composer graph:*`.
 *
 * Usage: php cli/graph.php code|docs
 *
 * - code: Graphify CLI refresh of code files; no agent and no API key.
 * - docs: launches the Copilot CLI to run the Graphify skill, whose agent
 *   extracts documentation without an API key.
 *
 * Exits non-zero unless the refresh is verified, so `composer graph` stops
 * instead of reporting a stale graph as current.
 */

declare(strict_types=1);

const GRAPH = 'graphify-out/graph.json';
const MANIFEST = 'graphify-out/manifest.json';
const SKILL = '/.copilot/skills/graphify/SKILL.md';
const MARKER = 'GRAPH_DOCS_RESULT:';

function fail(string $message): never
{
    fwrite(STDERR, "\n[graph] FAILED: {$message}\n");
    fwrite(STDERR, "[graph] The knowledge graph may be stale.\n");
    exit(1);
}

function findCommand(string $name): ?string
{
    $dirs = explode(PATH_SEPARATOR, (string) getenv('PATH'));
    $dirs[] = getenv('HOME') . '/.local/bin';
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
}

function modifiedSince(string $path, int $start): bool
{
    clearstatcache(true, $path);
    return is_file($path) && filemtime($path) >= $start;
}

function refreshCode(): void
{
    $graphify = findCommand('graphify')
        ?? fail('graphify is not installed or not on PATH. See docs/graphify-setup.md#local-tooling.');

    echo "[graph] Refreshing code with the Graphify CLI.\n";
    passthru(escapeshellarg($graphify) . ' update .', $code);
    if ($code !== 0) {
        fail("graphify update exited with code {$code}.");
    }
    echo "[graph] Code refresh complete. Documentation is refreshed by `composer graph:docs`.\n";
}

function refreshDocs(): void
{
    $copilot = findCommand('copilot')
        ?? fail('the Copilot CLI is not installed or not on PATH. See docs/graphify-setup.md#local-tooling.');
    if (! is_file(getenv('HOME') . SKILL)) {
        fail('the Graphify skill is not installed at ~' . SKILL . '. See docs/graphify-setup.md#local-tooling.');
    }

    $prompt = <<<PROMPT
        Use the Graphify skill to refresh the existing knowledge graph in this
        repository: invoke it as `/graphify . --update` and follow its update
        flow, including semantic extraction of changed documentation. No API key
        is needed; you are the extraction model. Do not run `graphify . --update`,
        `graphify extract`, or any other headless extraction command from the
        shell. Write only inside graphify-out/. Do not edit other files, install
        or upgrade software, or run git, gh, or composer.

        When finished, list any document that failed extraction, then print one
        final line, exactly one of:
        GRAPH_DOCS_RESULT: success   (graph.json, GRAPH_REPORT.md, and
                                      manifest.json were regenerated and every
                                      changed document was extracted)
        GRAPH_DOCS_RESULT: unchanged (the skill reported no changed files)
        GRAPH_DOCS_RESULT: failure   (anything else; give the reason above it)
        PROMPT;

    $command = implode(' ', array_map('escapeshellarg', [
        $copilot,
        '--prompt', $prompt,
        '--no-custom-instructions',
        '--no-ask-user',
        '--no-auto-update',
        '--allow-all-tools',
        '--deny-tool', 'shell(git:*)',
        '--deny-tool', 'shell(gh:*)',
        '--deny-tool', 'shell(composer:*)',
    ]));

    echo "[graph] Launching a Copilot agent to refresh documentation with the Graphify skill.\n";
    echo "[graph] This can take several minutes.\n\n";

    $start = time();
    $pipe = popen($command . ' 2>&1', 'r') ?: fail('could not launch the Copilot CLI.');
    $output = '';
    while (($line = fgets($pipe)) !== false) {
        echo $line;
        $output .= $line;
    }
    $code = pclose($pipe);

    if ($code !== 0) {
        fail("the Copilot CLI exited with code {$code}.");
    }

    $result = null;
    foreach (preg_split('/\R/', $output) as $line) {
        if (preg_match('/^\s*' . preg_quote(MARKER, '/') . '\s*(success|unchanged|failure)\b/', $line, $m)) {
            $result = $m[1];
        }
    }

    match ($result) {
        null => fail('the agent did not report a result.'),
        'failure' => fail('the agent reported a failed refresh.'),
        'unchanged' => is_file(MANIFEST)
            ? null
            : fail('the agent reported no changes, but there is no manifest to compare against.'),
        'success' => modifiedSince(GRAPH, $start) && modifiedSince(MANIFEST, $start)
            ? null
            : fail('the agent reported success, but graph.json and manifest.json were not both regenerated.'),
    };

    markUsageUnavailable();
    echo "\n[graph] Documentation refresh verified ({$result}).\n";
}

/**
 * The skill records placeholder zeros for agent extraction; report them as
 * unavailable rather than measured (docs/graphify-workflow.md).
 */
function markUsageUnavailable(): void
{
    $report = 'graphify-out/GRAPH_REPORT.md';
    $text = (string) file_get_contents($report);
    $updated = preg_replace(
        '/^- Token cost: 0 input · 0 output$/mu',
        '- Token cost: unavailable (extracted by a Copilot agent; usage appears in the Copilot CLI output)',
        $text,
    );
    if ($updated !== null && $updated !== $text) {
        file_put_contents($report, $updated);
    }
}

chdir(dirname(__DIR__));

if (! is_file(GRAPH)) {
    fail(
        'no knowledge graph at ' . GRAPH . '. These commands refresh an existing graph;'
        . ' follow docs/graphify-setup.md to create one.'
    );
}

match ($argv[1] ?? '') {
    'code' => refreshCode(),
    'docs' => refreshDocs(),
    default => fail('usage: php cli/graph.php code|docs'),
};
