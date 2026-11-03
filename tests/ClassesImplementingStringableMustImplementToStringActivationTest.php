<?php

declare(strict_types=1);

namespace Eightfold\Tests;

use Eightfold\Attributes\Classes\Subclass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[Subclass(rationale: 'PHPUnit test classes must extend TestCase.')]
final class ClassesImplementingStringableMustImplementToStringActivationTest extends TestCase
{
    private const MESSAGE =
        'Classes implementing Stringable must implement Eightfold\\Interfaces\\ToString.';

    #[Test]
    #[Group('analysis')]
    public function configured_phpstan_enforces_direct_stringability_and_defers_indirect(): void
    {
        $root = dirname(__DIR__);
        $command = [
            PHP_BINARY,
            $root . '/vendor/bin/phpstan',
            'analyze',
            '--configuration=' . $root . '/phpstan.neon',
            '--no-progress',
            '--error-format=raw',
        ];
        $directory = sys_get_temp_dir() . '/stringability-' . bin2hex(random_bytes(8));
        if (mkdir($directory, 0700) === false) {
            throw new RuntimeException('Unable to create the activation test directory.');
        }

        $noncompliant = $directory . '/Noncompliant.php';
        $compliant = $directory . '/Compliant.php';
        $indirect = $directory . '/Indirect.php';

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
            $indirectSource = <<<'PHP'
                <?php

                declare(strict_types=1);

                namespace StringabilityActivation;

                trait StringableTrait
                {
                    public function __toString(): string
                    {
                        return 'value';
                    }
                }

                final class UsesStringableTrait
                {
                    use StringableTrait;
                }
                PHP;
            if (
                file_put_contents($noncompliant, $noncompliantSource . "\n") === false
                || file_put_contents($compliant, $compliantSource . "\n") === false
                || file_put_contents($indirect, $indirectSource . "\n") === false
            ) {
                throw new RuntimeException('Unable to write the activation test inputs.');
            }

            [$exitCode, $output, $error] = self::runAnalyzer($command, $noncompliant);
            parent::assertNotSame(0, $exitCode, $error);
            parent::assertStringContainsString(self::MESSAGE, $output);

            [$exitCode, $output, $error] = self::runAnalyzer($command, $compliant);
            parent::assertSame(0, $exitCode, $output . $error);

            [$exitCode, $output, $error] = self::runAnalyzer($command, $indirect);
            parent::assertSame(0, $exitCode, $output . $error);
        } finally {
            foreach ([$noncompliant, $compliant, $indirect] as $path) {
                if (is_file($path) && unlink($path) === false) {
                    throw new RuntimeException('Unable to remove the activation test input.');
                }
            }
            if (rmdir($directory) === false) {
                throw new RuntimeException('Unable to remove the activation test directory.');
            }
        }
    }

    #[Test]
    #[Group('analysis')]
    public function phpstan_extension_loads_from_a_consumer_package_directory(): void
    {
        $directory = sys_get_temp_dir() . '/stringability-consumer-' . bin2hex(random_bytes(8));
        $packageDirectory = $directory . '/vendor/8fold/php-architecture';
        if (mkdir($packageDirectory, 0700, true) === false) {
            throw new RuntimeException('Unable to create the consumer test directory.');
        }

        $extension = $packageDirectory . '/extension.neon';
        $config = $directory . '/phpstan.neon';
        $fixture = $directory . '/Noncompliant.php';

        try {
            $configSource = <<<'NEON'
                includes:
                    - vendor/8fold/php-architecture/extension.neon

                parameters:
                    level: 10
                NEON;
            $fixtureSource = <<<'PHP'
                <?php

                namespace StringabilityConsumer;

                final class DirectStringable implements \Stringable
                {
                    public function __toString(): string
                    {
                        return 'value';
                    }
                }
                PHP;
            if (
                copy(dirname(__DIR__) . '/extension.neon', $extension) === false
                || file_put_contents($config, $configSource . "\n") === false
                || file_put_contents($fixture, $fixtureSource . "\n") === false
            ) {
                throw new RuntimeException('Unable to write the consumer test inputs.');
            }

            [$exitCode, $output, $error] = self::runAnalyzer(
                [
                    PHP_BINARY,
                    dirname(__DIR__) . '/vendor/bin/phpstan',
                    'analyze',
                    '--configuration=' . $config,
                    '--no-progress',
                    '--error-format=raw',
                ],
                $fixture
            );
            parent::assertNotSame(0, $exitCode, $error);
            parent::assertStringContainsString(self::MESSAGE, $output, $error);
        } finally {
            foreach ([$fixture, $config, $extension] as $path) {
                if (is_file($path) && unlink($path) === false) {
                    throw new RuntimeException('Unable to remove a consumer test input.');
                }
            }
            $directories = [
                $packageDirectory,
                $directory . '/vendor/8fold',
                $directory . '/vendor',
                $directory,
            ];
            foreach ($directories as $path) {
                if (rmdir($path) === false) {
                    throw new RuntimeException('Unable to remove a consumer test directory.');
                }
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
