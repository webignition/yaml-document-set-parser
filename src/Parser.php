<?php

declare(strict_types=1);

namespace webignition\YamlDocumentSetParser;

use Symfony\Component\Yaml\Yaml;

class Parser
{
    private const YAML_DOCUMENT_START = '---';
    private const YAML_DOCUMENT_END = '...';

    private const YAML_DOCUMENT_START_LENGTH = 3;
    private const YAML_DOCUMENT_END_LENGTH = 3;

    /**
     * @param string $yaml
     *
     * @return array<mixed>
     */
    public function parse(string $yaml): array
    {
        $yaml = trim($yaml);
        if ('' === $yaml) {
            return [];
        }

        $documents = [];
        $parsedDocuments = [];
        $currentDocument = '';

        $lines = explode("\n", $yaml);

        foreach ($lines as $line) {
            $isDocumentStart = self::lineStartsWithDocumentStart($line);
            $isDocumentEnd = self::lineStartsWithDocumentEnd($line);

            if ($isDocumentStart) {
                if ('' !== $currentDocument) {
                    $documents[] = $currentDocument;
                    $parsedDocuments[] = Yaml::parse($currentDocument);
                }

                $currentDocument = '';
            }

            if (false === $isDocumentStart && false === $isDocumentEnd) {
                $currentDocument .= "\n" . $line;
            }
        }

        if ('' !== $currentDocument) {
            $parsedDocuments[] = Yaml::parse($currentDocument);
        }

        return $parsedDocuments;
    }

    private static function lineStartsWithDocumentStart(string $line): bool
    {
        if (self::YAML_DOCUMENT_START_LENGTH > strlen($line)) {
            return false;
        }

        return substr($line, 0, self::YAML_DOCUMENT_START_LENGTH) === self::YAML_DOCUMENT_START;
    }

    private static function lineStartsWithDocumentEnd(string $line): bool
    {
        if (self::YAML_DOCUMENT_END_LENGTH > strlen($line)) {
            return false;
        }

        return substr($line, 0, self::YAML_DOCUMENT_END_LENGTH) === self::YAML_DOCUMENT_END;
    }
}
