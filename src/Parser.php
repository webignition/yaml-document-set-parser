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

        $currentLines = [];

        foreach ($lines as $line) {
            $isDocumentStart = Document::isDocumentStart($line);
            $isDocumentEnd = Document::isDocumentEnd($line);

            if ($isDocumentStart) {
                if ([] !== $currentLines) {
                    $parsedDocuments[] = $this->createParsedDocument($currentLines);
                    $currentLines = [];
                }
            }

            if (false === $isDocumentStart && false === $isDocumentEnd) {
                $currentLines[] = $line;
            }
        }

        if ([] !== $currentLines) {
            $parsedDocuments[] = $this->createParsedDocument($currentLines);
        }

        return $parsedDocuments;
    }

    /**
     * @param string[] $lines
     *
     * @throws ParseException
     */
    private function createParsedDocument(array $lines): mixed
    {
        return (new Document(implode("\n", $lines)))->parse();
    }
}
