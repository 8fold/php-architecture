<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS;

use Eightfold\Efqs\Sniffs\Classes\ClassesImplementingStringableMustImplementToStringSniff;
use PHP_CodeSniffer\Config;
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

    #[Test]
    #[Group('phpcs')]
    public function compliant_classes_have_no_diagnostics(): void
    {
        $file = self::analyseFixture('ClassesImplementingStringableMustImplementToStringCompliant.php');

        parent::assertSame([], $file->getErrors());
        parent::assertSame([], $file->getWarnings());
    }

    #[Test]
    #[Group('phpcs')]
    public function reports_each_directly_stringable_class_once(): void
    {
        $file = self::analyseFixture('ClassesImplementingStringableMustImplementToStringNoncompliant.php');
        $actual = [];

        foreach ($file->getErrors() as $line => $columns) {
            foreach ($columns as $column => $errors) {
                foreach ($errors as $error) {
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

    private static function analyseFixture(string $name): LocalFile
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

        new Tokens();
        $config = new Config(['--standard=PSR1', '--severity=1']);
        $config->cache = false;
        $config->recordErrors = true;
        $config->sniffs = [];
        $config->exclude = [];
        $config->trackTime = true;

        $ruleset = new Ruleset($config);
        $ruleset->sniffs = [self::SNIFF => self::SNIFF];
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

        $path = __DIR__ . '/Fixtures/' . $name;
        if (is_file($path) === false || is_readable($path) === false) {
            throw new RuntimeException("Unable to read the fixture: {$path}");
        }

        $file = new LocalFile($path, $ruleset, $config);
        $file->process();

        if (array_key_exists(self::SNIFF, $file->getListenerTimes()) === false) {
            throw new RuntimeException('The proposed sniff did not process the fixture.');
        }

        return $file;
    }
}
