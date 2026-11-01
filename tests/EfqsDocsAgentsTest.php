<?php

declare(strict_types=1);

namespace Eightfold\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class EfqsDocsAgentsTest extends TestCase
{
    private string $projectDirectory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectDirectory = sys_get_temp_dir() . '/efqs-agents-' . bin2hex(random_bytes(8));
        if (!mkdir($this->projectDirectory, 0700)) {
            throw new RuntimeException('Unable to create the test project directory.');
        }
    }

    protected function tearDown(): void
    {
        $files = [
            '/AGENTS.md',
            '/composer.json',
            '/nested/AGENTS.md',
            '/package with spaces/AGENTS.md',
            '/package with spaces/bin/efqs',
            '/project with spaces/AGENTS.md',
            '/project with spaces/composer.json',
            '/existing.txt',
        ];
        foreach ($files as $path) {
            $path = $this->projectDirectory . $path;
            if (is_file($path) || is_link($path)) {
                unlink($path);
            }
        }
        $directories = [
            '/AGENTS.md',
            '/nested',
            '/package with spaces/bin',
            '/package with spaces',
            '/project with spaces',
        ];
        foreach ($directories as $path) {
            $path = $this->projectDirectory . $path;
            if (is_dir($path)) {
                rmdir($path);
            }
        }
        rmdir($this->projectDirectory);
        parent::tearDown();
    }

    #[Test]
    #[Group('cli')]
    public function exposes_efqs_as_a_composer_binary(): void
    {
        $contents = file_get_contents(dirname(__DIR__) . '/composer.json');
        if ($contents === false) {
            parent::fail('Unable to read the package manifest.');
        }
        $manifest = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
        if (!is_array($manifest) || !isset($manifest['bin']) || !is_array($manifest['bin'])) {
            parent::fail('The package manifest does not declare Composer binaries.');
        }

        parent::assertContains('bin/efqs', $manifest['bin']);
    }

    #[Test]
    #[Group('cli')]
    public function copies_the_canonical_file_to_the_project_root(): void
    {
        self::createProjectManifest($this->projectDirectory);

        [$exitCode, $output, $error] = self::runInstaller($this->projectDirectory);

        parent::assertSame(0, $exitCode, $error);
        parent::assertStringContainsString('Copied AGENTS.md', $output);
        parent::assertFileEquals(
            dirname(__DIR__) . '/AGENTS.md',
            $this->projectDirectory . '/AGENTS.md'
        );
    }

    #[Test]
    #[Group('cli')]
    public function copies_to_a_project_root_containing_spaces(): void
    {
        $project = $this->projectDirectory . '/project with spaces';
        mkdir($project);
        self::createProjectManifest($project);

        [$exitCode, , $error] = self::runInstaller($project);

        parent::assertSame(0, $exitCode, $error);
        parent::assertFileEquals(dirname(__DIR__) . '/AGENTS.md', $project . '/AGENTS.md');
    }

    #[Test]
    #[Group('cli')]
    public function reports_an_identical_file_as_current(): void
    {
        self::createProjectManifest($this->projectDirectory);
        copy(dirname(__DIR__) . '/AGENTS.md', $this->projectDirectory . '/AGENTS.md');

        [$exitCode, $output, $error] = self::runInstaller($this->projectDirectory);

        parent::assertSame(0, $exitCode, $error);
        parent::assertStringContainsString('already current', $output);
        parent::assertFileEquals(
            dirname(__DIR__) . '/AGENTS.md',
            $this->projectDirectory . '/AGENTS.md'
        );
    }

    #[Test]
    #[Group('cli')]
    public function refuses_to_overwrite_a_different_file(): void
    {
        self::createProjectManifest($this->projectDirectory);
        file_put_contents($this->projectDirectory . '/AGENTS.md', "Local rules\n");

        [$exitCode, , $error] = self::runInstaller($this->projectDirectory);

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString('refusing to overwrite', $error);
        parent::assertSame(
            "Local rules\n",
            file_get_contents($this->projectDirectory . '/AGENTS.md')
        );
    }

    #[Test]
    #[Group('cli')]
    public function refuses_a_destination_that_is_not_a_regular_file(): void
    {
        self::createProjectManifest($this->projectDirectory);
        mkdir($this->projectDirectory . '/AGENTS.md');

        [$exitCode, , $error] = self::runInstaller($this->projectDirectory);

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString('not a regular file', $error);
        parent::assertDirectoryExists($this->projectDirectory . '/AGENTS.md');
    }

    #[Test]
    #[Group('cli')]
    public function refuses_to_change_a_symlink_to_different_content(): void
    {
        self::createProjectManifest($this->projectDirectory);
        file_put_contents($this->projectDirectory . '/existing.txt', "Local rules\n");
        if (!@symlink('existing.txt', $this->projectDirectory . '/AGENTS.md')) {
            parent::markTestSkipped('Symlinks are not available in this environment.');
        }

        [$exitCode, , $error] = self::runInstaller($this->projectDirectory);

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString('refusing to overwrite', $error);
        parent::assertTrue(is_link($this->projectDirectory . '/AGENTS.md'));
        parent::assertSame(
            "Local rules\n",
            file_get_contents($this->projectDirectory . '/existing.txt')
        );
    }

    #[Test]
    #[Group('cli')]
    public function refuses_to_replace_a_dangling_symlink(): void
    {
        self::createProjectManifest($this->projectDirectory);
        if (!@symlink('missing.txt', $this->projectDirectory . '/AGENTS.md')) {
            parent::markTestSkipped('Symlinks are not available in this environment.');
        }

        [$exitCode, , $error] = self::runInstaller($this->projectDirectory);

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString('not a regular file', $error);
        parent::assertTrue(is_link($this->projectDirectory . '/AGENTS.md'));
        parent::assertFileDoesNotExist($this->projectDirectory . '/missing.txt');
    }

    #[Test]
    #[Group('cli')]
    public function reports_when_the_destination_cannot_be_created(): void
    {
        self::createProjectManifest($this->projectDirectory);
        if (!chmod($this->projectDirectory, 0500)) {
            parent::markTestSkipped('Directory permissions cannot be changed in this environment.');
        }

        try {
            clearstatcache(true, $this->projectDirectory);
            if (is_writable($this->projectDirectory)) {
                parent::markTestSkipped(
                    'Directory permissions cannot prevent writing in this environment.'
                );
            }

            [$exitCode, , $error] = self::runInstaller($this->projectDirectory);

            parent::assertNotSame(0, $exitCode);
            parent::assertStringContainsString('Unable to create AGENTS.md', $error);
            parent::assertFileDoesNotExist($this->projectDirectory . '/AGENTS.md');
        } finally {
            chmod($this->projectDirectory, 0700);
        }
    }

    #[Test]
    #[Group('cli')]
    public function rejects_a_directory_without_a_project_manifest(): void
    {
        [$exitCode, , $error] = self::runInstaller($this->projectDirectory);

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString('project root', $error);
        parent::assertFileDoesNotExist($this->projectDirectory . '/AGENTS.md');
    }

    #[Test]
    #[Group('cli')]
    public function does_not_guess_the_project_root_from_a_subdirectory(): void
    {
        self::createProjectManifest($this->projectDirectory);
        mkdir($this->projectDirectory . '/nested');

        [$exitCode, , $error] = self::runInstaller($this->projectDirectory . '/nested');

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString('project root', $error);
        parent::assertFileDoesNotExist($this->projectDirectory . '/AGENTS.md');
        parent::assertFileDoesNotExist($this->projectDirectory . '/nested/AGENTS.md');
    }

    #[Test]
    #[Group('cli')]
    public function rejects_unexpected_arguments(): void
    {
        self::createProjectManifest($this->projectDirectory);

        [$exitCode, , $error] = self::runInstaller(
            $this->projectDirectory,
            ['docs:agents', '--force']
        );

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString('Usage:', $error);
        parent::assertFileDoesNotExist($this->projectDirectory . '/AGENTS.md');
    }

    #[Test]
    #[Group('cli')]
    public function rejects_missing_or_unknown_subcommands(): void
    {
        self::createProjectManifest($this->projectDirectory);

        foreach ([[], ['install']] as $arguments) {
            [$exitCode, , $error] = self::runInstaller($this->projectDirectory, $arguments);

            parent::assertNotSame(0, $exitCode);
            parent::assertStringContainsString('Usage: efqs docs:agents', $error);
            parent::assertFileDoesNotExist($this->projectDirectory . '/AGENTS.md');
        }
    }

    #[Test]
    #[Group('cli')]
    public function accepts_cli_arguments_when_argument_registration_is_disabled(): void
    {
        self::createProjectManifest($this->projectDirectory);

        [$exitCode, $output, $error] = self::runInstaller(
            $this->projectDirectory,
            ['docs:agents'],
            null,
            ['-d', 'register_argc_argv=0']
        );

        parent::assertSame(0, $exitCode, $error);
        parent::assertStringContainsString('Copied AGENTS.md', $output);
        parent::assertFileEquals(
            dirname(__DIR__) . '/AGENTS.md',
            $this->projectDirectory . '/AGENTS.md'
        );
    }

    #[Test]
    #[Group('cli')]
    public function reads_the_canonical_file_relative_to_the_installed_package(): void
    {
        self::createProjectManifest($this->projectDirectory);
        mkdir($this->projectDirectory . '/package with spaces/bin', 0700, true);
        copy(
            dirname(__DIR__) . '/bin/efqs',
            $this->projectDirectory . '/package with spaces/bin/efqs'
        );
        file_put_contents(
            $this->projectDirectory . '/package with spaces/AGENTS.md',
            "Installed package rules\n"
        );

        [$exitCode, , $error] = self::runInstaller(
            $this->projectDirectory,
            ['docs:agents'],
            $this->projectDirectory . '/package with spaces/bin/efqs'
        );

        parent::assertSame(0, $exitCode, $error);
        parent::assertSame(
            "Installed package rules\n",
            file_get_contents($this->projectDirectory . '/AGENTS.md')
        );
    }

    #[Test]
    #[Group('cli')]
    public function reports_a_missing_canonical_file(): void
    {
        self::createProjectManifest($this->projectDirectory);
        mkdir($this->projectDirectory . '/package with spaces/bin', 0700, true);
        copy(
            dirname(__DIR__) . '/bin/efqs',
            $this->projectDirectory . '/package with spaces/bin/efqs'
        );

        [$exitCode, , $error] = self::runInstaller(
            $this->projectDirectory,
            ['docs:agents'],
            $this->projectDirectory . '/package with spaces/bin/efqs'
        );

        parent::assertNotSame(0, $exitCode);
        parent::assertStringContainsString("Unable to read the package's AGENTS.md", $error);
        parent::assertFileDoesNotExist($this->projectDirectory . '/AGENTS.md');
    }

    private static function createProjectManifest(string $directory): void
    {
        if (file_put_contents($directory . '/composer.json', "{}\n") === false) {
            throw new RuntimeException('Unable to create the test project manifest.');
        }
    }

    /**
     * @param list<string> $arguments
     * @param list<string> $phpOptions
     * @return array{int, string, string}
     */
    private static function runInstaller(
        string $directory,
        array $arguments = ['docs:agents'],
        ?string $script = null,
        array $phpOptions = []
    ): array {
        $script ??= dirname(__DIR__) . '/bin/efqs';
        $process = proc_open(
            [PHP_BINARY, ...$phpOptions, $script, ...$arguments],
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes,
            $directory
        );
        if (!is_resource($process)) {
            throw new RuntimeException('Unable to run the installer.');
        }

        fclose($pipes[0]);
        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);
        if ($output === false || $error === false) {
            throw new RuntimeException('Unable to read the installer output.');
        }

        return [$exitCode, $output, $error];
    }
}
