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
     * @param string $yaml
     * @param array<mixed> $expectedParsedDocuments
     */
    public function testParse(string $yaml, array $expectedParsedDocuments): void
    {
        self::assertSame($expectedParsedDocuments, (new Parser())->parse($yaml));
    }

    /**
     * @return array[]
     */
    public function parseDataProvider(): array
    {
        return [
            'empty' => [
                'yaml' => '',
                'expectedParsedDocuments' => [],
            ],
            'single document, no start or end delimiters' => [
                'yaml' =>
                    '- item1' . "\n" .
                    '- item2' . "\n" .
                    '- item3',
                'expectedParsedDocuments' => [
                    [
                        'item1',
                        'item2',
                        'item3',
                    ],
                ],
            ],
            'single document, start delimiter only' => [
                'yaml' =>
                    '---' . "\n" .
                    '- item1' . "\n" .
                    '- item2' . "\n" .
                    '- item3',
                'expectedParsedDocuments' => [
                    [
                        'item1',
                        'item2',
                        'item3',
                    ],
                ],
            ],
            'single document, end delimiter only' => [
                'yaml' =>
                    '- item1' . "\n" .
                    '- item2' . "\n" .
                    '- item3' . "\n" .
                    '...',
                'expectedParsedDocuments' => [
                    [
                        'item1',
                        'item2',
                        'item3',
                    ],
                ],
            ],
            'single document, start and end delimiters' => [
                'yaml' =>
                    '---' . "\n" .
                    '- item1' . "\n" .
                    '- item2' . "\n" .
                    '- item3' . "\n" .
                    '...',
                'expectedParsedDocuments' => [
                    [
                        'item1',
                        'item2',
                        'item3',
                    ],
                ],
            ],
            'two documents, start delimiters only' => [
                'yaml' =>
                    '---' . "\n" .
                    '- item1.1' . "\n" .
                    '- item1.2' . "\n" .
                    '- item1.3' . "\n" .
                    '---' . "\n" .
                    '- item2.1' . "\n" .
                    '- item2.2' . "\n" .
                    '- item2.3',
                'expectedParsedDocuments' => [
                    [
                        'item1.1',
                        'item1.2',
                        'item1.3',
                    ],
                    [
                        'item2.1',
                        'item2.2',
                        'item2.3',
                    ],
                ],
            ],
            'two documents, start and end delimiters' => [
                'yaml' =>
                    '---' . "\n" .
                    '- item1.1' . "\n" .
                    '- item1.2' . "\n" .
                    '- item1.3' . "\n" .
                    '...' . "\n" .
                    '---' . "\n" .
                    '- item2.1' . "\n" .
                    '- item2.2' . "\n" .
                    '- item2.3' . "\n" .
                    '...',
                'expectedParsedDocuments' => [
                    [
                        'item1.1',
                        'item1.2',
                        'item1.3',
                    ],
                    [
                        'item2.1',
                        'item2.2',
                        'item2.3',
                    ],
                ],
            ],
        ];
    }
}
