<?php

declare(strict_types=1);

namespace Eightfold\Analysis;

use InvalidArgumentException;
use RuntimeException;

final readonly class MessageCompiler
{
    public function __construct(
        private string $templateDirectory
    ) {
    }

    /**
     * @param array<string, string> $values
     */
    public function compile(string $templateName, array $values = []): string
    {
        if (preg_match('/\A[a-zA-Z0-9][a-zA-Z0-9_.-]*\z/', $templateName) !== 1) {
            throw new InvalidArgumentException(
                "Invalid message template name: {$templateName}"
            );
        }

        $path = $this->templateDirectory . DIRECTORY_SEPARATOR . $templateName . '.md';
        if (is_file($path) === false || is_readable($path) === false) {
            throw new RuntimeException("Message template not found: {$path}");
        }

        $template = file_get_contents($path);
        if ($template === false) {
            throw new RuntimeException("Unable to read message template: {$path}");
        }

        $replacements = [];
        foreach ($values as $name => $value) {
            $replacements['{{' . $name . '}}'] = $value;
        }

        $compiled = rtrim(strtr($template, $replacements), "\r\n");
        $matchCount = preg_match_all(
            '/\{\{([a-zA-Z][a-zA-Z0-9_]*)\}\}/',
            $compiled,
            $matches
        );
        if ($matchCount === false) {
            throw new RuntimeException("Unable to inspect placeholders in template: {$path}");
        }
        if ($matchCount > 0) {
            $placeholders = array_values(array_unique($matches[1]));
            sort($placeholders);
            throw new RuntimeException(
                'Unresolved placeholders in message template '
                . "\"{$templateName}\": "
                . implode(', ', $placeholders)
            );
        }

        return $compiled;
    }
}
