<?php

declare(strict_types=1);

namespace Eightfold\Tests\PHPCS;

use Eightfold\PHPCodeSniffer\Sniffs\Classes\ThisMemberAccessSniff;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Tokens;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class ThisMemberAccessTest extends TestCase
{
    private const ERROR_CODE = 'PHPCodeSniffer.Classes.ThisMemberAccess.MethodAccessViaThis';

    #[Test]
    #[Group('phpcs')]
    public function compliant_member_access_has_no_diagnostics(): void
    {
        $file = self::analyseFixture('ThisMemberAccessCompliant.php');

        parent::assertSame([], $file->getErrors());
        parent::assertSame([], $file->getWarnings());
    }

    #[Test]
    #[Group('phpcs')]
    public function this_method_access_reports_each_violation_without_a_fix(): void
    {
        $file = self::analyseFixture('ThisMemberAccessNoncompliant.php');
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
            12 => 14,
            13 => 14,
            15 => 13,
            16 => 14,
            17 => 14,
            18 => 14,
            19 => 16,
            20 => 27,
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

        if (!class_exists(ThisMemberAccessSniff::class)) {
            throw new RuntimeException('Sniff unavailable; use the no-op bootstrap.');
        }

        if (!defined('PHP_CODESNIFFER_CBF')) {
            define('PHP_CODESNIFFER_CBF', false);
        }
        if (!defined('PHP_CODESNIFFER_VERBOSITY')) {
            define('PHP_CODESNIFFER_VERBOSITY', 0);
        }

        new Tokens();
        $config = new Config(['--standard=PSR1', '--severity=1']);
        $config->cache = false;
        $config->recordErrors = true;
        $config->sniffs = [];
        $config->exclude = [];
        $config->trackTime = true;

        // Initialize PHPCS with a built-in standard, then isolate the proposed sniff.
        $ruleset = new Ruleset($config);
        $ruleset->sniffs = [ThisMemberAccessSniff::class => ThisMemberAccessSniff::class];
        $ruleset->sniffCodes = [];
        $ruleset->ruleset = [];
        $ruleset->ignorePatterns = [];
        $ruleset->includePatterns = [];
        $ruleset->populateTokenListeners();

        if (
            !isset($ruleset->sniffs[ThisMemberAccessSniff::class])
            || $ruleset->tokenListeners === []
        ) {
            throw new RuntimeException('The proposed sniff did not register any token listeners.');
        }

        $path = __DIR__ . '/Fixtures/' . $name;
        if (!is_file($path) || !is_readable($path)) {
            throw new RuntimeException("Unable to read the fixture: {$path}");
        }

        $file = new LocalFile($path, $ruleset, $config);
        $file->process();

        if (!array_key_exists(ThisMemberAccessSniff::class, $file->getListenerTimes())) {
            throw new RuntimeException('The proposed sniff did not process the fixture.');
        }

        return $file;
    }
}
