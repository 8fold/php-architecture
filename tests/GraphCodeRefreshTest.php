<?php

declare(strict_types=1);

namespace Eightfold\Tests;

use Eightfold\Attributes\Classes\Subclass;
use FilesystemIterator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

#[Subclass(rationale: 'PHPUnit test classes must extend TestCase.')]
final class GraphCodeRefreshTest extends TestCase
{
    private string $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = sys_get_temp_dir() . '/graph-code-' . bin2hex(random_bytes(8));
        foreach (['cli', 'graphify-out', 'bin', 'home/.local/bin'] as $directory) {
            if (mkdir($this->repository . '/' . $directory, 0700, true) === false) {
                throw new RuntimeException('Unable to create the test repository.');
            }
        }
        if (
            copy(
                dirname(__DIR__) . '/cli/graph.php',
                $this->repository . '/cli/graph.php'
            ) === false
        ) {
            throw new RuntimeException('Unable to copy the graph refresh helper.');
        }
        self::write($this->repository . '/graphify-out/graph.json', '{}');
    }

    protected function tearDown(): void
    {
        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->repository, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir() && $item->isLink() === false) {
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
    public function refreshes_code_from_the_repository_root(): void
    {
        self::installGraphify('bin');

        [$exitCode, $output, $error] = self::runHelper();

        parent::assertSame(0, $exitCode, $error);
        parent::assertSame("update\n.\n", file_get_contents($this->repository . '/arguments.txt'));
        parent::assertStringContainsString('Code refresh finished', $output);
        parent::assertStringContainsString('Check Graphify warnings', $output);
        parent::assertStringContainsString('documentation may still be stale', $output);
    }

    #[Test]
    #[Group('cli')]
    public function finds_graphify_in_the_user_local_bin_directory(): void
    {
        self::installGraphify('home/.local/bin');

        [$exitCode, , $error] = self::runHelper();

        parent::assertSame(0, $exitCode, $error);
        parent::assertSame("update\n.\n", file_get_contents($this->repository . '/arguments.txt'));
    }

    #[Test]
    #[Group('cli')]
    public function fails_without_an_existing_graph(): void
    {
        self::installGraphify('bin');
        unlink($this->repository . '/graphify-out/graph.json');

        [$exitCode, , $error] = self::runHelper();

        parent::assertSame(1, $exitCode);
        parent::assertStringContainsString('no knowledge graph', $error);
        parent::assertStringContainsString('may be stale', $error);
        parent::assertFileDoesNotExist($this->repository . '/arguments.txt');
    }

    #[Test]
    #[Group('cli')]
    public function fails_when_graphify_is_unavailable(): void
    {
        [$exitCode, , $error] = self::runHelper();

        parent::assertSame(1, $exitCode);
        parent::assertStringContainsString('graphify is not on PATH or in ~/.local/bin', $error);
        parent::assertStringContainsString('may be stale', $error);
    }

    #[Test]
    #[Group('cli')]
    public function reports_a_failed_graphify_update(): void
    {
        self::installGraphify('bin', 7);

        [$exitCode, $output, $error] = self::runHelper();

        parent::assertSame(1, $exitCode);
        parent::assertStringContainsString('graphify update exited with code 7', $error);
        parent::assertStringContainsString('may be stale', $error);
        parent::assertStringNotContainsString('Code refresh finished', $output);
    }

    #[Test]
    #[Group('cli')]
    public function rejects_the_deferred_documentation_mode(): void
    {
        self::installGraphify('bin');

        [$exitCode, , $error] = self::runHelper('docs');

        parent::assertSame(1, $exitCode);
        parent::assertStringContainsString('usage: php cli/graph.php code', $error);
        parent::assertFileDoesNotExist($this->repository . '/arguments.txt');
    }

    private function installGraphify(string $directory, int $exitCode = 0): void
    {
        $path = $this->repository . '/' . $directory . '/graphify';
        self::write($path, "#!/bin/sh\nprintf '%s\\n' \"\$@\" > arguments.txt\nexit {$exitCode}\n");
        if (chmod($path, 0755) === false) {
            throw new RuntimeException('Unable to make the fake Graphify executable.');
        }
    }

    private static function write(string $path, string $contents): void
    {
        if (file_put_contents($path, $contents) === false) {
            throw new RuntimeException('Unable to write a test file.');
        }
    }

    /**
     * @return array{int, string, string}
     */
    private function runHelper(string $mode = 'code'): array
    {
        $process = proc_open(
            [PHP_BINARY, $this->repository . '/cli/graph.php', $mode],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
            $this->repository . '/home',
            [
                'HOME' => $this->repository . '/home',
                'PATH' => $this->repository . '/bin',
            ]
        );
        if (is_resource($process) === false) {
            throw new RuntimeException('Unable to run the graph refresh helper.');
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
