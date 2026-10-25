<?php
declare(strict_types=1);

// Minimal PSR-4 autoloader for shared first-party and HtmlBuilder classes.
// Map-free and class-per-file, so we avoid pulling in the full composer
// autoload for every page request.
//
// Shared namespaces are enumerated on purpose: registering the bare MTM\
// prefix would make every application namespace loadable from every route
// (the kitchen sink). A route that needs an application's classes requires
// that application's autoload-*.php (see autoload-time-journal.php), so the
// reachable classes are declared at the top of each controller — and a
// cross-application reference fails loudly (class not found) instead of
// silently working.
spl_autoload_register(function (string $class): void {
    $prefixes = [
        'MTM\\Shared\\'            => '/src/Shared/',
        'MTM\\Authentication\\'    => '/src/Authentication/',
        'MTM\\Billing\\'           => '/src/Billing/',
        'MTM\\Legal\\'             => '/src/Legal/',
        'MTM\\Messaging\\'         => '/src/Messaging/',
        'MTM\\Tenancy\\'           => '/src/Tenancy/',
        'Eightfold\\HtmlBuilder\\' => '/vendor/8fold/php-html-builder/src/',
    ];

    $baseDir = dirname(__DIR__);

    foreach ($prefixes as $prefix => $dir) {
        if (str_starts_with($class, $prefix) === false) {
            continue;
        }

        $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
        $file     = $baseDir . $dir . $relative . '.php';

        if (is_file($file)) {
            require_once $file;
        }
        return;
    }

    // Root-level MTM classes (no subnamespace) map to src/<Class>.php.
    if (str_starts_with($class, 'MTM\\')) {
        $relative = substr($class, strlen('MTM\\'));
        if (str_contains($relative, '\\') === false) {
            $file = $baseDir . '/src/' . $relative . '.php';
            if (is_file($file)) {
                require_once $file;
            }
        }
    }
});
