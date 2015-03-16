<?php

namespace Doctor\Tests\Integration;

use Doctor\Extractor\Markdown;

class MarkdownTest extends IntegrationTest
{
    protected function getDocumentsDirectory()
    {
        return 'markdown';
    }

    protected function getExtractor()
    {
        $parser = new \Kurenai\DocumentParser();

        return new Markdown($parser);
    }
}
