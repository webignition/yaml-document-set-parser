<?php

declare(strict_types=1);

namespace webignition\YamlDocumentSetParser;

use Symfony\Component\Yaml\Exception\ParseException;
use webignition\YamlDocument\Document;

class Parser
{
    /**
     * @throws ParseException
     *
     * @return array<mixed>
     */
    public function parse(string $yaml): array
    {
        if ('' === trim($yaml)) {
            return [];
        }

        $parsedDocuments = [];
        $lines = explode("\n", $yaml);
        $lineCount = count($lines);

        $currentLines = [];

        foreach ($lines as $lineIndex => $line) {
            $isDocumentStart = Document::isDocumentStart($line);
            $isDocumentEnd = Document::isDocumentEnd($line);
            $isLastLine = $lineIndex === $lineCount - 1;

            if (false === $isDocumentStart && false === $isDocumentEnd) {
                $currentLines[] = $line;
            }

            if (($isDocumentStart || $isLastLine) && [] !== $currentLines) {
                $parsedDocuments[] = (new Document(implode("\n", $currentLines)))->parse();
                $currentLines = [];
            }
        }

        return $parsedDocuments;
    }
}
