<?php

declare(strict_types=1);

namespace webignition\YamlDocumentSetParser;

use webignition\YamlDocument\Document;

class Parser
{
    /**
     * @return array<mixed>
     */
    public function parse(string $yaml): array
    {
        $yaml = trim($yaml);
        if ('' === $yaml) {
            return [];
        }

        $parsedDocuments = [];
        $currentDocument = new Document();

        $lines = explode("\n", $yaml);

        foreach ($lines as $line) {
            $isDocumentStart = Document::isDocumentStart($line);
            $isDocumentEnd = Document::isDocumentEnd($line);

            if ($isDocumentStart) {
                if (false === $currentDocument->isEmpty()) {
                    $parsedDocuments[] = $currentDocument->parse();
                }

                $currentDocument = new Document();
            }

            if (false === $isDocumentStart && false === $isDocumentEnd) {
                $currentDocument = $currentDocument->append("\n" . $line);
            }
        }

        if (false === $currentDocument->isEmpty()) {
            $parsedDocuments[] = $currentDocument->parse();
        }

        return $parsedDocuments;
    }
}
