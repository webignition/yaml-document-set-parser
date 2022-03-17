<?php

declare(strict_types=1);

namespace webignition\YamlDocumentSetParser\Tests\Unit;

use PHPUnit\Framework\TestCase;
use webignition\YamlDocumentSetParser\Parser;

class ParserTest extends TestCase
{
    /**
     * @dataProvider parseDataProvider
     *
     * @param string[] $expected
     */
    public function testParse(string $content, array $expected): void
    {
        self::assertSame($expected, (new Parser())->parse($content));
    }

    /**
     * @return array<mixed>
     */
    public function parseDataProvider(): array
    {
        $content = [
            '- item1.1' . "\n" . '- item1.2' . "\n" . '- item1.3',
            '- item2.1' . "\n" . '- item2.2' . "\n" . '- item2.3',
        ];

        return [
            'empty' => [
                'content' => '',
                'expected' => [],
            ],
            'single document, no start or end delimiters' => [
                'content' => $content[0],
                'expected' => [$content[0]],
            ],
            'single document, start delimiter only' => [
                'content' => <<< EOF
                ---
                {$content[0]}
                EOF,
                'expected' => [$content[0]],
            ],
            'single document, end delimiter only' => [
                'content' => <<< EOF
                {$content[0]}
                ...
                EOF,
                'expected' => [$content[0]],
            ],
            'single document, start and end delimiters' => [
                'content' => <<< EOF
                ---
                {$content[0]}
                ...
                EOF,
                'expected' => [$content[0]],
            ],
            'two documents, start delimiters only' => [
                'content' => <<< EOF
                ---
                {$content[0]}
                ---
                {$content[1]}
                EOF,
                'expected' => [$content[0], $content[1]],
            ],
            'two documents, start and end delimiters' => [
                'content' => <<< EOF
                ---
                {$content[0]}
                ...
                ---
                {$content[1]}
                ...
                EOF,
                'expected' => [$content[0], $content[1]],
            ],
        ];
    }
}
