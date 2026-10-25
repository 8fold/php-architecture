<?php

declare(strict_types=1);

/**
 * Renders the static error documents (public/404.html, public/500.html)
 * through DocumentBase, so they carry the site's shell — skip link,
 * navigation, application h1, footer, and the base stylesheet — without
 * PHP at serve time. Apache serves them directly (ErrorDocument), and
 * ErrorPageFallback reads 500.html when PHP itself fails.
 *
 * The pages are committed build artifacts that depend only on the shell
 * and messages/errors: assets are linked without a content version (#313),
 * so stylesheet and script changes never make them stale. The pre-commit
 * hook regenerates them on every commit; CI verifies they are fresh.
 *
 * Rendered as an anonymous visitor with no session: the page must not
 * depend on who is signed in, the database, or the request.
 */

use Eightfold\HtmlBuilder\Components\PageTitle;

use MTM\Authentication\AccountContext;
use MTM\Billing\Application;
use MTM\Shared\Http\MessageTemplate;
use MTM\Shared\Http\Request;
use MTM\Shared\UserInterface\DocumentBase;

require_once __DIR__ . '/../config/autoload-mtm-htmlbuilder.php';

$root = dirname(__DIR__);

$pages = [
    '404.html' => ['Page not found', 'errors/not-found'],
    '500.html' => ['Something went wrong', 'errors/server-error']
];

foreach ($pages as $file => [$title, $message]) {
    $html = (string) DocumentBase::create(
        PageTitle::create([$title, 'MtM - Apps']),
        new AccountContext(null, null),
        new Request('/' . $file, $root . '/public_content'),
        Application::Core
    )->unversionedAssets()->content(MessageTemplate::render($message));

    $target = $root . '/public/' . $file;
    if (file_put_contents($target, $html . PHP_EOL) === false) {
        throw new RuntimeException('Could not write error page: ' . $target);
    }
    echo 'built: ' . $target . PHP_EOL;
}
