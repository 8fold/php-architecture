<?php

declare(strict_types=1);

namespace Eightfold\CommonMarkAccessibleHeadingPermalink\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\CommonMarkAccessibleHeadingPermalink\HeadingPermalinkExtension;

use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter;

use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

class AccessibleHeadingPermalinkTest extends TestCase
{
    /**
     * @test
     */
    public function can_change_symbol_used(): void
    {
        $environment = new Environment([
            'accessible_heading_permalink' => [
                'min_heading_level' => 2,
                'symbol' => '#'
            ]
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new HeadingPermalinkExtension());

        $converter = new MarkdownConverter($environment);

        $this->assertEquals(<<<html
            <h1>A word of caution</h1>
            <p>Something</p><div is="heading-wrapper"><h2 id="another-word-of-caution">Another word of caution</h2><a href="#another-word-of-caution"><span aria-hidden="true">#</span><span>Section titled Another word of caution</span></a></div>

            html,
            (string) $converter->convertToHtml(<<<md
                # A word of caution

                Something

                ## Another word of caution
                md
            )
        );
    }

    /**
     * @test
     */
    public function respects_ignoring_levels(): void
    {
        $environment = new Environment([
            'accessible_heading_permalink' => [
                'min_heading_level' => 2
            ]
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new HeadingPermalinkExtension());

        $converter = new MarkdownConverter($environment);


        $this->assertEquals(<<<html
            <h1>A word of caution</h1>
            <p>Something</p><div is="heading-wrapper"><h2 id="another-word-of-caution">Another word of caution</h2><a href="#another-word-of-caution"><span aria-hidden="true">¶</span><span>Section titled Another word of caution</span></a></div>

            html,
            (string) $converter->convertToHtml(<<<md
                # A word of caution

                Something

                ## Another word of caution
                md
            )
        );
    }

    /**
     * @test
     */
    public function matches_baseline(): void
    {
        // matches Amber Wilson implementation, not example iteration
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new HeadingPermalinkExtension());

        $converter = new MarkdownConverter($environment);

        $this->assertEquals(<<<html
            <div is="heading-wrapper"><h1 id="a-word-of-caution">A word of caution</h1><a href="#a-word-of-caution"><span aria-hidden="true">¶</span><span>Section titled A word of caution</span></a></div>

            html,
            (string) $converter->convertToHtml(<<<md
                # A word of caution
                md
            )
        );

        $this->assertEquals(<<<html
            <div is="heading-wrapper"><h1 id="a-word-of-caution">A word of caution</h1><a href="#a-word-of-caution"><span aria-hidden="true">¶</span><span>Section titled A word of caution</span></a></div>
            <p>Something</p><div is="heading-wrapper"><h2 id="another-word-of-caution">Another word of caution</h2><a href="#another-word-of-caution"><span aria-hidden="true">¶</span><span>Section titled Another word of caution</span></a></div>

            html,
            (string) $converter->convertToHtml(<<<md
                # A word of caution

                Something

                ## Another word of caution
                md
            )
        );
    }
}
