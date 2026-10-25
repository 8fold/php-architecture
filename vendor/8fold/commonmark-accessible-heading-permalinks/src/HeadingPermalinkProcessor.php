<?php

declare(strict_types=1);

namespace Eightfold\CommonMarkAccessibleHeadingPermalink;

use League\CommonMark\Environment\EnvironmentAwareInterface;
use League\CommonMark\Environment\EnvironmentInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Node\NodeIterator;
use League\CommonMark\Node\RawMarkupContainerInterface;
use League\CommonMark\Node\StringContainerHelper;
use League\CommonMark\Normalizer\TextNormalizerInterface;
use League\Config\ConfigurationInterface;

/**
 * For the most part, this class should match the one from League CommonMark.
 *
 * Differences are annotated.
 */
final class HeadingPermalinkProcessor implements EnvironmentAwareInterface
{
    public const INSERT_BEFORE = 'before';
    public const INSERT_AFTER  = 'after';

    private TextNormalizerInterface $slugNormalizer;

    private ConfigurationInterface $config;

    public function setEnvironment(EnvironmentInterface $environment): void
    {
        $this->config         = $environment->getConfiguration();
        $this->slugNormalizer = $environment->getSlugNormalizer();
    }

    public function __invoke(DocumentParsedEvent $e): void
    {
        // Primary key from configuration is different than base
        $min = (int) $this->config
            ->get('accessible_heading_permalink/min_heading_level');
        $max = (int) $this->config
            ->get('accessible_heading_permalink/max_heading_level');

        $slugLength = (int) $this->config->get('slug_normalizer/max_length');

        foreach ($e->getDocument()->iterator(NodeIterator::FLAG_BLOCKS_ONLY) as $node) {
            if (
                $node instanceof Heading and
                $node->getLevel() >= $min and
                $node->getLevel() <= $max
            ) {
                $this->addHeadingLink($node, $slugLength);
            }
        }
    }

    /**
     * The original uses an anchor tag that appears before or after the content
     * of the heading itself.
     *
     * The accessible solution applies a container for the heading and anchor as
     * separate elements at the same level of the DOM.
     */
    private function addHeadingLink(Heading $heading, int $slugLength): void
    {
        $level = $heading->getLevel();

        $content  = StringContainerHelper::getChildText(
            $heading,
            [RawMarkupContainerInterface::class]
        );

        $slug  = $this->slugNormalizer->normalize(
            $content,
            [
                'node'   => $heading,
                'length' => $slugLength,
            ]
        );

        $headingLinkAnchor = new HeadingPermalink($slug, $level, $content);

        $heading->replaceWith($headingLinkAnchor);
    }
}
