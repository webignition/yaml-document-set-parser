<?php

declare(strict_types=1);

namespace webignition\YamlDocumentSetParser;

use Symfony\Component\Yaml\Parser as SymfonyYamlParser;

class Document
{
    private const DOCUMENT_START = '---';
    private const DOCUMENT_END = '...';

    private const DOCUMENT_START_LENGTH = 3;
    private const DOCUMENT_END_LENGTH = 3;

    private string $content;

    public function __construct(string $content = '')
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isEmpty(): bool
    {
        return '' === $this->content;
    }

    public function append(string $content): self
    {
        $new = clone $this;
        $new->content .= $content;

        return $new;
    }

    /**
     * @param SymfonyYamlParser|null $parser
     *
     * @return mixed
     */
    public function parse(?SymfonyYamlParser $parser = null)
    {
        if (null === $parser) {
            $parser = new SymfonyYamlParser();
        }

        return $parser->parse($this->content);
    }

    public static function isDocumentStart(string $line): bool
    {
        if (self::DOCUMENT_START_LENGTH > strlen($line)) {
            return false;
        }

        return substr($line, 0, self::DOCUMENT_START_LENGTH) === self::DOCUMENT_START;
    }

    public static function isDocumentEnd(string $line): bool
    {
        if (self::DOCUMENT_END_LENGTH > strlen($line)) {
            return false;
        }

        return substr($line, 0, self::DOCUMENT_END_LENGTH) === self::DOCUMENT_END;
    }
}
