<?php

declare(strict_types=1);

namespace Eightfold\CommonMarkAccessibleHeadingPermalink;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Xml\XmlNodeRendererInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

/**
 * Don't use PHP ability to infer namespace.
 */
use InvalidArgumentException;
use Eightfold\CommonMarkAccessibleHeadingPermalink\HeadingPermalink;

/**
 * For the most part, this class should match the one from League CommonMark.
 *
 * Differences are annotated.
 */
final class HeadingPermalinkRenderer implements
    NodeRendererInterface,
    XmlNodeRendererInterface,
    ConfigurationAwareInterface
{
    public const DEFAULT_SYMBOL = '¶';

    private ConfigurationInterface $config;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    /**
     * Almost a rewrite compared to the origin.
     */
    public function render(
        Node $node,
        ChildNodeRendererInterface $childRenderer
    ): HtmlElement {
        // PHPStan doesn't follow assertInstanceOf
        if (is_a($node, HeadingPermalink::class) === false) {
            $format    = 'Incompatible node type: expected %s, got %s';
            $nodeClass = get_class($node);
            $expected  = HeadingPermalink::class;
            $exception = sprintf($format, $expected, $nodeClass);
            throw new InvalidArgumentException($exception);
        }

        $slug    = $node->getSlug();

        // Needed to build heading.
        $level   = $node->getLevel();
        $content = $node->getContent();

        // fragment prefix not used

        $attrs = $node->data->getData('attributes');
        $attrs->set('href', '#' . $slug);
        $symbol = $this->config->get('accessible_heading_permalink/symbol');
        // removed setting custom class
        // aria-hidden is part of the span within the anchor tag

        assert(is_string($symbol));

        // build symbol span
        $symbolSpan = new HtmlElement(
            'span',
            [
                'aria-hidden' => "true"
            ],
            htmlspecialchars($symbol),
            false
        );

        // build the span for voice over to use
        $voiceoverSpan = new HtmlElement(
            'span',
            [],
            'Section titled ' . htmlspecialchars($content),
            false
        );

        // build the anchor itself
        $link = new HtmlElement(
            'a',
            $attrs->export(),
            [$symbolSpan, $voiceoverSpan],
            false
        );

        // build the header
        $header = new HtmlElement(
            'h' . $level,
            ['id' => $slug],
            $content,
            false
        );

        // return the wrapper and contents
        return new HtmlElement(
            'div',
            ['is' => 'heading-wrapper'],
            [$header, $link],
            false
        );
    }

    /**
     * Convention seems to be that the XML node name is the same as the primary
     * key for the configuration array.
     */
    public function getXmlTagName(Node $node): string
    {
        return 'accessible_heading_permalink';
    }

    public function getXmlAttributes(Node $node): array
    {
        // PHPStan doesn't follow assertInstanceOf
        if (is_a($node, HeadingPermalink::class) === false) {
            $format    = 'Incompatible node type: expected %s, got %s';
            $nodeClass = get_class($node);
            $expected  = HeadingPermalink::class;
            $exception = sprintf($format, $expected, $nodeClass);
            throw new InvalidArgumentException($exception);
        }

        return [
            'slug' => $node->getSlug(),
        ];
    }
}
