<?php

declare(strict_types=1);

namespace webignition\YamlDocumentSetParser;

class Parser
{
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

    /**
     * @return array<mixed>
     */
    public function parse(string $content): array
    {
        if ('' === trim($content)) {
            return [];
        }

        $parsedDocuments = [];
        $lines = explode("\n", $content);
        $lineCount = count($lines);

        $currentLines = [];

        foreach ($lines as $lineIndex => $line) {
            $isDocumentStart = self::DOCUMENT_START === $line;
            $isDocumentEnd = self::DOCUMENT_END === $line;
            $isLastLine = $lineIndex === $lineCount - 1;

            if (false === $isDocumentStart && false === $isDocumentEnd) {
                $currentLines[] = $line;
            }

            if (($isDocumentStart || $isLastLine) && [] !== $currentLines) {
                $parsedDocuments[] = implode("\n", $currentLines);
                $currentLines = [];
            }
        }

        return $parsedDocuments;
    }
}
