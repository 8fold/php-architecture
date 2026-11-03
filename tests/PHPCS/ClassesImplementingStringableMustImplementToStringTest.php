<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS;

use Eightfold\Efqs\Sniffs\Classes\ClassesImplementingStringableMustImplementToStringSniff;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\DummyFile;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Tokens;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ClassesImplementingStringableMustImplementToStringTest extends TestCase
{
    private const SNIFF = ClassesImplementingStringableMustImplementToStringSniff::class;

    private const ERROR_CODE =
        'Efqs.Classes.ClassesImplementingStringableMustImplementToString.MissingToStringInterface';

    private const FIXTURE_PREFIX = 'ClassesImplementingStringableMustImplementToString';

    #[Test]
    #[Group('phpcs')]
    public function compliant_classes_have_no_diagnostics(): void
    {
        $file = self::analyseFixture(self::FIXTURE_PREFIX . 'Compliant.php');

        parent::assertSame([], $file->getErrors());
        parent::assertSame([], $file->getWarnings());
    }

    #[Test]
    #[Group('phpcs')]
    public function reports_each_directly_stringable_class_once(): void
    {
        $file = self::analyseFixture(self::FIXTURE_PREFIX . 'Noncompliant.php');
        $actual = [];

        foreach ($file->getErrors() as $line => $columns) {
            if (is_int($line) === false || is_array($columns) === false) {
                throw new RuntimeException('Unexpected PHPCS error line data.');
            }
            foreach ($columns as $column => $errors) {
                if (is_int($column) === false || is_array($errors) === false) {
                    throw new RuntimeException('Unexpected PHPCS error column data.');
                }
                foreach ($errors as $error) {
                    if (
                        is_array($error) === false
                        || is_string($error['source'] ?? null) === false
                        || is_int($error['severity'] ?? null) === false
                        || is_bool($error['fixable'] ?? null) === false
                    ) {
                        throw new RuntimeException('Unexpected PHPCS error details.');
                    }
                    $actual[] = [
                        'line' => $line,
                        'column' => $column,
                        'code' => $error['source'],
                        'severity' => $error['severity'],
                        'fixable' => $error['fixable'],
                    ];
                }
            }
        }

        $expected = [];
        $locations = [
            17 => 1,
            25 => 1,
            33 => 1,
            41 => 1,
            49 => 1,
            57 => 1,
            61 => 1,
            71 => 1,
            83 => 20,
            94 => 16,
        ];
        foreach ($locations as $line => $column) {
            $expected[] = [
                'line' => $line,
                'column' => $column,
                'code' => self::ERROR_CODE,
                'severity' => 5,
                'fixable' => false,
            ];
        }

        parent::assertSame($expected, $actual);
        parent::assertSame([], $file->getWarnings());
    }

    #[Test]
    #[Group('phpcs')]
    public function resolves_grouped_aliases_without_leaking_namespace_or_file_scope(): void
    {
        [$config, $ruleset] = self::sniffEnvironment();
        $first = <<<'PHP'
            <?php
            namespace First;
            use Eightfold\Interfaces\{ToString as Contract};
            $value = '';
            $closure = static function () use ($value): string { return $value; };
            abstract class Good implements Contract {}
            PHP;
        $second = <<<'PHP'
            <?php
            interface Contract extends \Stringable {}
            abstract class Bad implements Contract {
                abstract public function __toString(): string;
            }
            PHP;
        $namespaces = <<<'PHP'
            <?php
            namespace First {
                use Eightfold\Interfaces\{ToString as Contract};
                use Eightfold\{Interfaces as ContractNamespace};
                abstract class Good implements Contract {}
                abstract class AlsoGood implements ContractNamespace\ToString {}
            }
            namespace Second {
                interface Contract extends \Stringable {}
                abstract class Bad implements Contract {
                    abstract public function __toString(): string;
                }
            }
            PHP;

        self::assertErrorLines(self::analyseSource($first, $config, $ruleset), []);
        self::assertErrorLines(self::analyseSource($second, $config, $ruleset), [3]);
        self::assertErrorLines(self::analyseSource($namespaces, $config, $ruleset), [10]);
    }

    #[Test]
    #[Group('phpcs')]
    public function attributes_nested_anonymous_constructor_arguments_to_their_own_class(): void
    {
        [$config, $ruleset] = self::sniffEnvironment();
        $source = <<<'PHP'
            <?php
            function make(): object {
                return new class(
                    new class implements \Stringable {
                        public function __toString(): string { return 'value'; }
                    }
                ) {
                    public function __construct(\Stringable $value) {}
                };
            }
            PHP;

        self::assertErrorLines(self::analyseSource($source, $config, $ruleset), [4]);
    }

    private static function analyseFixture(string $name): LocalFile
    {
        [$config, $ruleset] = self::sniffEnvironment();
        $path = __DIR__ . '/Fixtures/' . $name;
        if (is_file($path) === false || is_readable($path) === false) {
            throw new RuntimeException("Unable to read the fixture: {$path}");
        }

        $file = new LocalFile($path, $ruleset, $config);
        self::processFile($file);

        return $file;
    }

    private static function analyseSource(
        string $source,
        Config $config,
        Ruleset $ruleset
    ): DummyFile {
        $file = new DummyFile($source, $ruleset, $config);
        self::processFile($file);

        return $file;
    }

    /** @param list<int> $lines */
    private static function assertErrorLines(File $file, array $lines): void
    {
        parent::assertSame($lines, array_keys($file->getErrors()));
        parent::assertSame(count($lines), $file->getErrorCount());
        parent::assertSame([], $file->getWarnings());
    }

    /** @return array{Config, Ruleset} */
    private static function sniffEnvironment(): array
    {
        require_once dirname(__DIR__, 2) . '/vendor/squizlabs/php_codesniffer/autoload.php';

        if (class_exists(self::SNIFF) === false) {
            throw new RuntimeException('The proposed PHPCS sniff is unavailable.');
        }

        if (defined('PHP_CODESNIFFER_CBF') === false) {
            define('PHP_CODESNIFFER_CBF', false);
        }
        if (defined('PHP_CODESNIFFER_VERBOSITY') === false) {
            define('PHP_CODESNIFFER_VERBOSITY', 0);
        }

        if (class_exists(Tokens::class) === false) {
            throw new RuntimeException('Unable to load PHPCS tokens.');
        }
        $config = new Config(['--standard=PSR1', '--severity=1']);
        $config->cache = false;
        $config->recordErrors = true;
        $config->sniffs = [];
        $config->exclude = [];
        $config->trackTime = true;

        $ruleset = new Ruleset($config);
        $ruleset->sniffs = [
            self::SNIFF => new ClassesImplementingStringableMustImplementToStringSniff(),
        ];
        $ruleset->sniffCodes = [];
        $ruleset->ruleset = [];
        $ruleset->ignorePatterns = [];
        $ruleset->includePatterns = [];
        $ruleset->populateTokenListeners();

        if (
            isset($ruleset->sniffs[self::SNIFF]) === false
            || $ruleset->tokenListeners === []
        ) {
            throw new RuntimeException('The proposed sniff did not register any token listeners.');
        }

        return [$config, $ruleset];
    }

    private static function processFile(File $file): void
    {
        $file->process();

        if (array_key_exists(self::SNIFF, $file->getListenerTimes()) === false) {
            throw new RuntimeException('The proposed sniff did not process the fixture.');
        }
    }
}
