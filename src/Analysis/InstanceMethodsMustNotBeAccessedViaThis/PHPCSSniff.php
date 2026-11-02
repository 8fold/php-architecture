<?php

declare(strict_types=1);

namespace Eightfold\Analysis\InstanceMethodsMustNotBeAccessedViaThis;

use Eightfold\Analysis\MessageCompiler;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;
use UnexpectedValueException;

final class PHPCSSniff implements Sniff
{
    public function register(): array
    {
        return [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR];
    }

    public function process(File $file, int $stackPtr): void
    {
        if (
            self::isThisReceiver($file, $stackPtr) === false
            || self::isMethodAccess($file, $stackPtr) === false
        ) {
            return;
        }

        $file->addError(
            self::messageCompiler()->compile('message'),
            $stackPtr,
            'MethodAccessViaThis'
        );
    }

    private static function isThisReceiver(File $file, int $operator): bool
    {
        $tokens = $file->getTokens();
        $receiver = $file->findPrevious(Tokens::EMPTY_TOKENS, $operator - 1, null, true);

        if ($receiver === false) {
            return false;
        }

        $receiverToken = self::token($tokens, $receiver);
        while ($receiverToken['code'] === T_CLOSE_PARENTHESIS) {
            $opener = self::tokenIndex($receiverToken, 'parenthesis_opener');
            if ($opener === null || self::isCallArgumentList($file, $opener)) {
                return false;
            }

            $first = $file->findNext(Tokens::EMPTY_TOKENS, $opener + 1, $receiver, true);
            $last = $file->findPrevious(Tokens::EMPTY_TOKENS, $receiver - 1, $opener + 1, true);

            if ($first === false || $last === false) {
                return false;
            }

            $firstToken = self::token($tokens, $first);
            if (
                $first !== $last
                && (
                    $firstToken['code'] !== T_OPEN_PARENTHESIS
                    || self::tokenIndex($firstToken, 'parenthesis_closer') !== $last
                )
            ) {
                return false;
            }

            $receiver = $last;
            $receiverToken = self::token($tokens, $receiver);
        }

        if (
            $receiverToken['code'] !== T_VARIABLE
            || $receiverToken['content'] !== '$this'
        ) {
            return false;
        }

        $previous = $file->findPrevious(Tokens::EMPTY_TOKENS, $receiver - 1, null, true);

        return $previous === false || in_array(
            self::token($tokens, $previous)['code'],
            [T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR, T_DOUBLE_COLON, T_DOLLAR],
            true,
        ) === false;
    }

    private static function isCallArgumentList(File $file, int $opener): bool
    {
        $tokens = $file->getTokens();
        $previous = $file->findPrevious(Tokens::EMPTY_TOKENS, $opener - 1, null, true);

        if ($previous === false) {
            return false;
        }

        // A control condition or statement block can precede a grouped receiver.
        $token = self::token($tokens, $previous);
        $parenthesisOwner = self::tokenIndex($token, 'parenthesis_owner');
        if ($token['code'] === T_CLOSE_PARENTHESIS && $parenthesisOwner !== null) {
            $owner = self::token($tokens, $parenthesisOwner)['code'];
            if (in_array($owner, [T_IF, T_ELSEIF, T_WHILE, T_FOR, T_FOREACH, T_DECLARE], true)) {
                return false;
            }
        }

        $scopeCondition = self::tokenIndex($token, 'scope_condition');
        if ($token['code'] === T_CLOSE_CURLY_BRACKET && $scopeCondition !== null) {
            $owner = self::token($tokens, $scopeCondition)['code'];
            if (in_array($owner, [T_ANON_CLASS, T_CLOSURE, T_MATCH], true) === false) {
                return false;
            }
        }

        return in_array($token['code'], [
            T_STRING,
            T_NAME_FULLY_QUALIFIED,
            T_NAME_QUALIFIED,
            T_NAME_RELATIVE,
            T_VARIABLE,
            T_CLOSE_PARENTHESIS,
            T_CLOSE_SQUARE_BRACKET,
            T_CLOSE_SHORT_ARRAY,
            T_CLOSE_CURLY_BRACKET,
            T_CONSTANT_ENCAPSED_STRING,
            T_DOUBLE_QUOTED_STRING,
            T_ISSET,
            T_EMPTY,
            T_EVAL,
            T_EXIT,
            T_ARRAY,
            T_LIST,
        ], true);
    }

    private static function isMethodAccess(File $file, int $operator): bool
    {
        $tokens = $file->getTokens();
        $member = $file->findNext(Tokens::EMPTY_TOKENS, $operator + 1, null, true);

        while ($member !== false && self::token($tokens, $member)['code'] === T_DOLLAR) {
            $member = $file->findNext(Tokens::EMPTY_TOKENS, $member + 1, null, true);
        }

        if ($member === false) {
            return false;
        }

        $memberToken = self::token($tokens, $member);
        if ($memberToken['code'] === T_OPEN_CURLY_BRACKET) {
            $member = self::tokenIndex($memberToken, 'bracket_closer');
            if ($member === null) {
                return false;
            }
        } elseif (in_array($memberToken['code'], [T_STRING, T_VARIABLE], true) === false) {
            return false;
        }

        $next = $file->findNext(Tokens::EMPTY_TOKENS, $member + 1, null, true);

        return $next !== false && self::token($tokens, $next)['code'] === T_OPEN_PARENTHESIS;
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

    /**
     * @param array<array-key, mixed> $token
     */
    private static function tokenIndex(array $token, string $field): ?int
    {
        $index = $token[$field] ?? null;
        if ($index !== null && (is_int($index) === false || $index < 0)) {
            throw new UnexpectedValueException("Invalid PHPCS token index: {$field}.");
        }

        return $index;
    }

    private static function messageCompiler(): MessageCompiler
    {
        return new MessageCompiler(__DIR__);
    }
}
