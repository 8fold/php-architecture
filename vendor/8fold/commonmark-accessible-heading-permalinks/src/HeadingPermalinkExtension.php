<?php

declare(strict_types=1);

namespace Eightfold\CommonMarkAccessibleHeadingPermalink;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;

/**
 * Don't use PHP ability to infer namespace.
 */
use Eightfold\CommonMarkAccessibleHeadingPermalink\HeadingPermalink;
use Eightfold\CommonMarkAccessibleHeadingPermalink\HeadingPermalinkPocessor;
use Eightfold\CommonMarkAccessibleHeadingPermalink\HeadingPermalinkRenderer;

/**
 * For the most part, this class should match the one from League CommonMark.
 *
 * Differences are annotated.
 */
final class HeadingPermalinkExtension implements ConfigurableExtensionInterface
{
    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        // ignore: insert, id_prefix, fragment_prefix, html_class, title, and
        //     aria_hidden keys from origin
        $builder->addSchema('accessible_heading_permalink', Expect::structure([
            'min_heading_level' => Expect::int()->min(1)->max(6)->default(1),
            'max_heading_level' => Expect::int()->min(1)->max(6)->default(6),
            'symbol' => Expect::string()->default(HeadingPermalinkRenderer::DEFAULT_SYMBOL),
        ]));
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(
            DocumentParsedEvent::class,
            new HeadingPermalinkProcessor(),
            -100
        );

        $environment->addRenderer(
            HeadingPermalink::class,
            new HeadingPermalinkRenderer()
        );
    }
}
