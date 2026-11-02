<?php

declare(strict_types=1);

namespace Eightfold\Tests\Analysis;

use Eightfold\Analysis\MessageCompiler;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class MessageCompilerTest extends TestCase
{
    #[Test]
    #[Group('analysis')]
    public function compiles_named_templates_with_values(): void
    {
        $compiler = new MessageCompiler(__DIR__ . '/Fixtures/MessageCompiler');

        parent::assertSame(
            'Details for Example.',
            $compiler->compile('details', ['name' => 'Example'])
        );
    }

    #[Test]
    #[Group('analysis')]
    public function reports_missing_templates(): void
    {
        $compiler = new MessageCompiler(__DIR__ . '/Fixtures/MessageCompiler');

        parent::expectException(RuntimeException::class);
        parent::expectExceptionMessage('Message template not found:');

        $compiler->compile('missing');
    }

    #[Test]
    #[Group('analysis')]
    public function reports_unresolved_placeholders(): void
    {
        $compiler = new MessageCompiler(__DIR__ . '/Fixtures/MessageCompiler');

        parent::expectException(RuntimeException::class);
        parent::expectExceptionMessage(
            'Unresolved placeholders in message template "details": name'
        );

        $compiler->compile('details');
    }
}
