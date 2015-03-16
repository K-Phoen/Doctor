<?php

namespace Doctor\Tests\Integration;

use Doctor\Extractor\Word;

class WordTest extends IntegrationTest
{
    protected function getDocumentsDirectory()
    {
        return 'word';
    }

    protected function getExtractor()
    {
        return new Word();
    }
}
