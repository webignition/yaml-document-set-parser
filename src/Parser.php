<?php

declare(strict_types=1);

namespace webignition\YamlDocumentSetParser;

class Parser
{
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

    /**
     * @return array<int, string>
     */
    public function parse(string $content): array
    {
        $content = trim($content);
        if ('' === $content) {
            return [];
        }

        if (
            false === str_starts_with($content, self::DOCUMENT_START . "\n")
            || false === str_ends_with($content, "\n" . self::DOCUMENT_END)
        ) {
            return [];
        }

        $documents = explode(self::DOCUMENT_START . "\n", $content);
        array_shift($documents);

        array_walk($documents, function (&$document) {
            $document = rtrim($document);
            $document = rtrim($document, '.');
            $document = rtrim($document);
        });

        return $documents;
    }
}
