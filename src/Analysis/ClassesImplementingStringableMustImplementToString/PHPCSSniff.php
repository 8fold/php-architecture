<?php

declare(strict_types=1);

namespace Eightfold\Analysis\ClassesImplementingStringableMustImplementToString;

use Eightfold\Analysis\MessageCompiler;
use Eightfold\Interfaces\ToString;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use Stringable;
use UnexpectedValueException;

final class PHPCSSniff implements Sniff
{
    private ?File $currentFile = null;

    private int $lastToken = -1;

    private string $namespace = '';

    /** @var array<string, string> */
    private array $imports = [];

    /** @return list<int|string> */
    public function register(): array
    {
        return [T_NAMESPACE, T_USE, T_CLASS, T_ANON_CLASS];
    }

    public function process(File $file, int $stackPtr): void
    {
        if ($this->currentFile !== $file || $stackPtr <= $this->lastToken) {
            $this->currentFile = $file;
            $this->namespace = '';
            $this->imports = [];
        }
        $this->lastToken = $stackPtr;

        $tokens = $file->getTokens();
        $token = self::token($tokens, $stackPtr);
        if ($token['code'] === T_NAMESPACE) {
            self::beginNamespace($file, $stackPtr);
            return;
        }

        if ($token['code'] === T_USE) {
            self::registerImports($file, $stackPtr);
            return;
        }

        $hasNativeInterface = false;
        foreach (self::implementedNames($file, $stackPtr) as $name) {
            $resolved = self::resolveName($name);
            if (strcasecmp($resolved, ToString::class) === 0) {
                return;
            }
            if (strcasecmp($resolved, Stringable::class) === 0) {
                $hasNativeInterface = true;
            }
        }

        if (
            $hasNativeInterface === false
            && self::declaresMagicMethod($file, $stackPtr) === false
        ) {
            return;
        }

        $file->addError(
            (new MessageCompiler(__DIR__))->compile('message'),
            self::declarationStart($file, $stackPtr),
            'MissingToStringInterface'
        );
    }

    private function beginNamespace(File $file, int $stackPtr): void
    {
        $tokens = $file->getTokens();
        $end = $file->findNext([T_SEMICOLON, T_OPEN_CURLY_BRACKET], $stackPtr + 1);
        if ($end === false) {
            throw new UnexpectedValueException('Namespace declaration has no terminator.');
        }

        $this->namespace = trim(self::nameFromTokens($tokens, $stackPtr + 1, $end), '\\');
        $this->imports = [];
    }

    private function registerImports(File $file, int $stackPtr): void
    {
        $tokens = $file->getTokens();
        $use = self::token($tokens, $stackPtr);
        if (isset($use['parenthesis_owner']) || self::isNamespaceScope($use) === false) {
            return;
        }

        $end = $file->findNext(T_SEMICOLON, $stackPtr + 1);
        if ($end === false) {
            throw new UnexpectedValueException('Import declaration has no terminator.');
        }

        $start = $file->findNext(Tokens::EMPTY_TOKENS, $stackPtr + 1, $end, true);
        if ($start === false) {
            throw new UnexpectedValueException('Import declaration has no name.');
        }

        if (
            in_array(
                strtolower(self::token($tokens, $start)['content']),
                ['function', 'const'],
                true
            )
        ) {
            return;
        }

        $prefix = '';
        $group = $file->findNext(T_OPEN_USE_GROUP, $start, $end);
        if ($group !== false) {
            $prefix = trim(self::nameFromTokens($tokens, $start, $group), '\\');
            $end = $file->findNext(T_CLOSE_USE_GROUP, $group + 1, $end);
            if ($end === false) {
                throw new UnexpectedValueException('Grouped import has no closing brace.');
            }
            $start = $group + 1;
        }

        for ($position = $start; $position <= $end; $position++) {
            if (
                $position === $end
                || self::token($tokens, $position)['code'] === T_COMMA
            ) {
                self::recordImport($tokens, $start, $position, $prefix);
                $start = $position + 1;
            }
        }
    }

    /**
     * @param array<mixed> $tokens
     */
    private function recordImport(array $tokens, int $start, int $end, string $prefix): void
    {
        $name = '';
        $alias = '';
        $afterAs = false;

        for ($position = $start; $position < $end; $position++) {
            $token = self::token($tokens, $position);
            if (isset(Tokens::EMPTY_TOKENS[$token['code']])) {
                continue;
            }
            if (
                $name === ''
                && in_array(strtolower($token['content']), ['function', 'const'], true)
            ) {
                return;
            }
            if ($token['code'] === T_AS) {
                $afterAs = true;
                continue;
            }
            if (
                isset(Tokens::NAME_TOKENS[$token['code']]) === false
                && $token['code'] !== T_NS_SEPARATOR
            ) {
                throw new UnexpectedValueException('Unexpected token in import declaration.');
            }

            if ($afterAs) {
                $alias .= $token['content'];
            } else {
                $name .= $token['content'];
            }
        }

        $name = trim($name, '\\');
        if ($name === '') {
            return;
        }

        $qualified = $prefix === '' ? $name : $prefix . '\\' . $name;
        $parts = explode('\\', $qualified);
        $key = $alias === '' ? $parts[count($parts) - 1] : $alias;
        $this->imports[strtolower($key)] = $qualified;
    }

    private function resolveName(string $name): string
    {
        if (str_starts_with($name, '\\')) {
            return ltrim($name, '\\');
        }

        $parts = explode('\\', $name, 2);
        $first = strtolower($parts[0]);
        if ($first === 'namespace' && isset($parts[1])) {
            return self::inNamespace($parts[1]);
        }
        if (isset($this->imports[$first])) {
            $suffix = isset($parts[1]) ? '\\' . $parts[1] : '';
            return $this->imports[$first] . $suffix;
        }

        return self::inNamespace($name);
    }

    private function inNamespace(string $name): string
    {
        return $this->namespace === '' ? $name : $this->namespace . '\\' . $name;
    }

    /** @return list<string> */
    private static function implementedNames(File $file, int $stackPtr): array
    {
        $tokens = $file->getTokens();
        $class = self::token($tokens, $stackPtr);
        $opener = self::requiredIndex($class, 'scope_opener');
        $start = $stackPtr + 1;
        if (isset($class['parenthesis_closer'])) {
            // Constructor arguments can contain another class declaration.
            $start = self::requiredIndex($class, 'parenthesis_closer') + 1;
        }

        $implements = $file->findNext(T_IMPLEMENTS, $start, $opener);
        if ($implements === false) {
            return [];
        }

        $names = [];
        $start = $implements + 1;
        for ($position = $start; $position <= $opener; $position++) {
            if ($position === $opener || self::token($tokens, $position)['code'] === T_COMMA) {
                $name = self::nameFromTokens($tokens, $start, $position);
                if ($name === '') {
                    throw new UnexpectedValueException('Implemented interface has no name.');
                }
                $names[] = $name;
                $start = $position + 1;
            }
        }

        return $names;
    }

    private static function declaresMagicMethod(File $file, int $stackPtr): bool
    {
        $tokens = $file->getTokens();
        $class = self::token($tokens, $stackPtr);
        $opener = self::requiredIndex($class, 'scope_opener');
        $closer = self::requiredIndex($class, 'scope_closer');
        $method = $file->findNext(T_FUNCTION, $opener + 1, $closer);

        while ($method !== false) {
            $token = self::token($tokens, $method);
            $conditions = self::conditions($token);
            if (
                array_key_last($conditions) === $stackPtr
                && strcasecmp($file->getDeclarationName($method), '__toString') === 0
            ) {
                return true;
            }
            $method = $file->findNext(T_FUNCTION, $method + 1, $closer);
        }

        return false;
    }

    private static function declarationStart(File $file, int $stackPtr): int
    {
        $tokens = $file->getTokens();
        if (self::token($tokens, $stackPtr)['code'] === T_ANON_CLASS) {
            return $stackPtr;
        }

        $start = $stackPtr;
        $previous = $file->findPrevious(Tokens::EMPTY_TOKENS, $start - 1, null, true);
        while (
            $previous !== false
            && in_array(
                self::token($tokens, $previous)['code'],
                [T_ABSTRACT, T_FINAL, T_READONLY],
                true
            )
        ) {
            $start = $previous;
            $previous = $file->findPrevious(Tokens::EMPTY_TOKENS, $start - 1, null, true);
        }

        return $start;
    }

    /** @param array<array-key, mixed> $token */
    private static function isNamespaceScope(array $token): bool
    {
        foreach (self::conditions($token) as $condition) {
            if ($condition !== T_NAMESPACE) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<mixed> $tokens
     */
    private static function nameFromTokens(array $tokens, int $start, int $end): string
    {
        $name = '';
        for ($position = $start; $position < $end; $position++) {
            $token = self::token($tokens, $position);
            if (isset(Tokens::EMPTY_TOKENS[$token['code']])) {
                continue;
            }
            if (
                isset(Tokens::NAME_TOKENS[$token['code']]) === false
                && $token['code'] !== T_NS_SEPARATOR
            ) {
                throw new UnexpectedValueException('Unexpected token in a qualified name.');
            }
            $name .= $token['content'];
        }

        return $name;
    }

    /**
     * @param array<mixed> $tokens
     * @return array{code: int|string, content: string, ...}
     */
    private static function token(array $tokens, int $index): array
    {
        $token = $tokens[$index] ?? null;
        if (
            is_array($token) === false
            || (
                is_int($token['code'] ?? null) === false
                && is_string($token['code'] ?? null) === false
            )
            || is_string($token['content'] ?? null) === false
        ) {
            throw new UnexpectedValueException("Invalid PHPCS token at position {$index}.");
        }

        return $token;
    }

    /** @param array<array-key, mixed> $token */
    private static function requiredIndex(array $token, string $field): int
    {
        $index = $token[$field] ?? null;
        if (is_int($index) === false || $index < 0) {
            throw new UnexpectedValueException("Invalid PHPCS token index: {$field}.");
        }

        return $index;
    }

    /**
     * @param array<array-key, mixed> $token
     * @return array<array-key, mixed>
     */
    private static function conditions(array $token): array
    {
        $conditions = $token['conditions'] ?? null;
        if (is_array($conditions) === false) {
            throw new UnexpectedValueException('Invalid PHPCS token conditions.');
        }

        return $conditions;
    }
}
