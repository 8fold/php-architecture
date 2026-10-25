<?php

declare(strict_types=1);

namespace Eightfold\CommonMarkAccessibleHeadingPermalink;

use League\CommonMark\Node\Inline\AbstractInline;

/**
 * For the most part, this class should match the one from League CommonMark.
 *
 * Differences are annotated.
 */
final class HeadingPermalink extends AbstractInline
{
    private string $slug;

    /**
     * We need to know the level of the heading, because we insert it into the
     * wrapper.
     */
    private int $level;

    /**
     * We need to know the content of the heading, because we insert it into the
     * wrapper.
     */
    private string $content;

    /**
     * Constructor updated with additional properties.
     */
    public function __construct(string $slug, int $level, string $content)
    {
        parent::__construct();

        $this->slug  = $slug;

        $this->level = $level;
        $this->content  = $content;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Request level for build.
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Request content for build.
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
