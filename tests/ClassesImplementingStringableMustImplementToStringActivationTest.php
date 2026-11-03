<?php

declare(strict_types=1);

namespace Eightfold\Tests;

use Eightfold\Attributes\Classes\Subclass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[Subclass(rationale: 'PHPUnit test classes must extend TestCase.')]
final class ClassesImplementingStringableMustImplementToStringActivationTest extends TestCase
{
    private const MESSAGE =
        'Classes implementing Stringable must implement Eightfold\\Interfaces\\ToString.';

    /**
     * @return array<string, array{list<string>}>
     */
    public static function analyzerCommands(): array
    {
        $root = dirname(__DIR__);

        return [
            'PHPCS' => [[
                PHP_BINARY,
                $root . '/vendor/bin/phpcs',
                '--standard=' . $root . '/phpcs.xml',
                '--report=emacs',
            ]],
            'PHPStan' => [[
                PHP_BINARY,
                $root . '/vendor/bin/phpstan',
                'analyze',
                '--configuration=' . $root . '/phpstan.neon',
                '--no-progress',
                '--error-format=raw',
            ]],
        ];
    }

    /**
     * @param list<string> $command
     */
    #[Test]
    #[Group('analysis')]
    #[DataProvider('analyzerCommands')]
    public function configured_analyzer_enforces_direct_stringability(array $command): void
    {
        $directory = sys_get_temp_dir() . '/stringability-' . bin2hex(random_bytes(8));
        if (mkdir($directory, 0700) === false) {
            throw new RuntimeException('Unable to create the activation test directory.');
        }

        $noncompliant = $directory . '/Noncompliant.php';
        $compliant = $directory . '/Compliant.php';

        try {
            $noncompliantSource = <<<'PHP'
                <?php

                declare(strict_types=1);

                namespace StringabilityActivation;

                final class DirectStringable implements \Stringable
                {
                    public function __toString(): string
                    {
                        return 'value';
                    }
                }
                PHP;
            $compliantSource = <<<'PHP'
                <?php

                declare(strict_types=1);

                namespace StringabilityActivation;

                final class SuiteStringable implements \Eightfold\Interfaces\ToString
                {
                    public function __toString(): string
                    {
                        return 'value';
                    }

                    public function toString(): string
                    {
                        return 'value';
                    }
                }
                PHP;
            if (
                file_put_contents($noncompliant, $noncompliantSource . "\n") === false
                || file_put_contents($compliant, $compliantSource . "\n") === false
            ) {
                throw new RuntimeException('Unable to write the activation test inputs.');
            }

            [$exitCode, $output, $error] = self::runAnalyzer($command, $noncompliant);
            parent::assertNotSame(0, $exitCode, $error);
            parent::assertStringContainsString(self::MESSAGE, $output);

            [$exitCode, $output, $error] = self::runAnalyzer($command, $compliant);
            parent::assertSame(0, $exitCode, $output . $error);
        } finally {
            foreach ([$noncompliant, $compliant] as $path) {
                if (is_file($path) && unlink($path) === false) {
                    throw new RuntimeException('Unable to remove the activation test input.');
                }
            }
            if (rmdir($directory) === false) {
                throw new RuntimeException('Unable to remove the activation test directory.');
            }
        }
    }

    /**
     * @param list<string> $command
     * @return array{int, string, string}
     */
    private static function runAnalyzer(array $command, string $path): array
    {
        $process = proc_open(
            [...$command, $path],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
            dirname(__DIR__)
        );
        if (is_resource($process) === false) {
            throw new RuntimeException('Unable to run the configured analyzer.');
        }

        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);
        if ($output === false || $error === false) {
            throw new RuntimeException('Unable to read the analyzer output.');
        }

        return [$exitCode, $output, $error];
    }
}
