<?php

declare(strict_types=1);

namespace Eightfold\Tests;

use FilesystemIterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

final class CheckInstructionCoverageTest extends TestCase
{
    private string $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = sys_get_temp_dir()
            . '/instruction-coverage-' . bin2hex(random_bytes(8));
        if (!mkdir($this->repository . '/.github/instructions', 0700, true)) {
            throw new RuntimeException('Unable to create the test repository.');
        }
        self::runCommand($this->repository, ['git', 'init', '--quiet']);
        self::write($this->repository, 'src/Nested/Thing.php', "<?php\n");
        self::write($this->repository, 'tests/ThingTest.php', "<?php\n");
        self::write($this->repository, 'untracked.php', "<?php\n");
        self::runCommand($this->repository, ['git', 'add', 'src', 'tests']);
    }

    protected function tearDown(): void
    {
        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->repository, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                rmdir($item->getPathname());
                continue;
            }
            unlink($item->getPathname());
        }
        rmdir($this->repository);
        parent::tearDown();
    }

    #[Test]
    #[Group('cli')]
    public function lists_tracked_files_matched_by_each_pattern(): void
    {
        self::write(
            $this->repository,
            '.github/instructions/php.instructions.md',
            "---\napplyTo: \"src/**/*.php, tests/**/*Test.php\"\n---\n"
        );

        [$exitCode, $output, $error] = self::runCommand($this->repository);

        parent::assertSame(0, $exitCode, $error);
        parent::assertStringContainsString(
            "Pattern: src/**/*.php\nsrc/Nested/Thing.php\n",
            $output
        );
        parent::assertStringContainsString(
            "Pattern: tests/**/*Test.php\ntests/ThingTest.php\n",
            $output
        );
        parent::assertStringNotContainsString('untracked.php', $output);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function invalidInstructions(): array
    {
        return [
            'no frontmatter' => ["# Title\n", 'missing or unreadable frontmatter'],
            'no applyTo' => ["---\ndescription: x\n---\n", 'needs one quoted applyTo line'],
            'unquoted applyTo' => [
                "---\napplyTo: src/**/*.php\n---\n",
                'needs one quoted applyTo line',
            ],
            'empty pattern' => ["---\napplyTo: \"src/**/*.php,\"\n---\n", 'empty pattern'],
            'no matches' => [
                "---\napplyTo: \"lib/**/*.php\"\n---\n",
                'matches no tracked files: lib/**/*.php',
            ],
            'untracked only' => [
                "---\napplyTo: \"untracked.php\"\n---\n",
                'matches no tracked files: untracked.php',
            ],
        ];
    }

    #[Test]
    #[Group('cli')]
    #[DataProvider('invalidInstructions')]
    public function fails_on_invalid_or_unmatched_instructions(
        string $contents,
        string $message
    ): void {
        self::write($this->repository, '.github/instructions/bad.instructions.md', $contents);

        [$exitCode, , $error] = self::runCommand($this->repository);

        parent::assertSame(1, $exitCode);
        parent::assertStringContainsString($message, $error);
    }

    #[Test]
    #[Group('cli')]
    public function fails_without_instruction_files(): void
    {
        [$exitCode, , $error] = self::runCommand($this->repository);

        parent::assertSame(1, $exitCode);
        parent::assertStringContainsString(
            'No .github/instructions/*.instructions.md files found',
            $error
        );
    }

    private static function write(string $repository, string $path, string $contents): void
    {
        $path = $repository . '/' . $path;
        if (!is_dir(dirname($path)) && !mkdir(dirname($path), 0700, true)) {
            throw new RuntimeException('Unable to create a test directory.');
        }
        if (file_put_contents($path, $contents) === false) {
            throw new RuntimeException('Unable to write a test file.');
        }
    }

    /**
     * @param list<string>|null $command
     * @return array{int, string, string}
     */
    private static function runCommand(string $directory, ?array $command = null): array
    {
        $command ??= [PHP_BINARY, dirname(__DIR__) . '/cli/check-instructions-coverage.php'];
        $process = proc_open(
            $command,
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
            $directory
        );
        if (!is_resource($process)) {
            throw new RuntimeException('Unable to run the command.');
        }

        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);
        if ($output === false || $error === false) {
            throw new RuntimeException('Unable to read the command output.');
        }

        return [$exitCode, $output, $error];
    }
}
