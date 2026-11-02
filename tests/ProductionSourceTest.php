<?php

declare(strict_types=1);

namespace Eightfold\Tests;

use Eightfold\Attributes\Classes\Subclass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RuntimeException;
use SplFileInfo;

#[Subclass(rationale: 'PHPUnit test classes must extend TestCase.')]
final class ProductionSourceTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function productionFiles(): array
    {
        $root = dirname(__DIR__);
        $files = [];
        foreach (['src', 'bin', 'cli'] as $directory) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
                $root . '/' . $directory,
                RecursiveDirectoryIterator::SKIP_DOTS
            ));

            foreach ($iterator as $file) {
                if (
                    $file instanceof SplFileInfo
                    && $file->isFile()
                    && (
                        $file->getExtension() === 'php'
                        || $file->getPathname() === $root . '/bin/efqs'
                    )
                ) {
                    $path = $file->getPathname();
                    $files[substr($path, strlen($root) + 1)] = [$path];
                }
            }
        }
        ksort($files);
        return $files;
    }

    #[Test]
    #[Group('architecture')]
    #[DataProvider('productionFiles')]
    public function production_file_parses(string $path): void
    {
        $process = proc_open(
            [PHP_BINARY, '-l', $path],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes
        );
        if (is_resource($process) === false) {
            throw new RuntimeException('Unable to start the PHP syntax check.');
        }

        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);
        if ($output === false || $error === false) {
            throw new RuntimeException('Unable to read PHP syntax-check output.');
        }

        parent::assertSame(0, $exitCode, $output . $error);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function sourceTypes(): array
    {
        $root = dirname(__DIR__) . '/src/';
        $namespaces = [
            'PHPCS/Efqs/' => 'Eightfold\\Efqs\\',
            'PHPCS/' => 'Eightfold\\PHPCS\\',
            'PHPStan/' => 'Eightfold\\PHPStan\\',
            'Attributes/' => 'Eightfold\\Attributes\\',
        ];
        $types = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
            $root,
            RecursiveDirectoryIterator::SKIP_DOTS
        ));

        foreach ($iterator as $file) {
            if (
                ($file instanceof SplFileInfo) === false
                || $file->isFile() === false
                || $file->getExtension() !== 'php'
            ) {
                continue;
            }
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root)));
            foreach ($namespaces as $directory => $namespace) {
                if (str_starts_with($relative, $directory)) {
                    $name = substr($relative, strlen($directory), -4);
                    $types[$relative] = [
                        $namespace . str_replace('/', '\\', $name),
                        $file->getPathname(),
                    ];
                    continue 2;
                }
            }
            throw new RuntimeException('No autoload namespace defined for src/' . $relative);
        }

        ksort($types);
        return $types;
    }

    #[Test]
    #[Group('architecture')]
    #[DataProvider('sourceTypes')]
    public function production_type_autoloads_from_its_file(string $className, string $path): void
    {
        require_once dirname(__DIR__) . '/vendor/squizlabs/php_codesniffer/autoload.php';

        parent::assertTrue(
            class_exists($className)
                || interface_exists($className, false)
                || trait_exists($className, false),
            $className . ' cannot be autoloaded.'
        );
        parent::assertSame($path, (new ReflectionClass($className))->getFileName());
    }
}
