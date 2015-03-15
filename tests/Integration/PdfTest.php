<?php

namespace Doctor\Tests\Integration;

use Doctor\Extractor\Pdf;

class PdfTest extends IntegrationTest
{
    protected function getDocumentsDirectory()
    {
        return 'pdf';
    }

    protected function getExtractor()
    {
        $parser = new \Smalot\PdfParser\Parser();

        return new Pdf($parser);
    }
}
