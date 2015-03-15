<?php

namespace Doctor\Tests;

use Doctor\Doctor;

class DoctorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractFromAKnownFileType()
    {
        $extractor = $this->getExtractorMock('pdf');
        $doctor    = new Doctor([$extractor]);

        $extractor
            ->expects($this->once())
            ->method('extract')
            ->with('file.pdf', 'pdf')
            ->will($this->returnValue($extractionResult = [
                'author'        => '',
                'title'         => '',
                'creation_date' => null,
                'keywords'      => [],
                'content'       => 'does not really matter'
            ]));

        $this->assertSame($extractionResult, $doctor->extract('file.pdf'));
    }

    public function testTheRightExtractorIsChosenWhenThereAreSeveralRegistered()
    {
        $pdfExtractor  = $this->getExtractorMock('pdf');
        $docxExtractor = $this->getExtractorMock('docx');
        $doctor        = new Doctor([$pdfExtractor, $docxExtractor]);

        $pdfExtractor
            ->expects($this->once())
            ->method('extract')
            ->with('file.pdf', 'pdf')
            ->will($this->returnValue([]));

        $docxExtractor
            ->expects($this->never())
            ->method('extract');

        $doctor->extract('file.pdf');
    }

    public function testTheExtensionCanBeOverriden()
    {
        $extractor = $this->getExtractorMock('pdf');
        $doctor    = new Doctor([$extractor]);

        $extractor
            ->expects($this->once())
            ->method('extract')
            ->with('/tmp/uploaded_file', 'pdf')
            ->will($this->returnValue([]));

        $doctor->extract('/tmp/uploaded_file', 'pdf');
    }

    public function testExtensionsAreNormalized()
    {
        $extractor = $this->getExtractorMock('pdf');
        $doctor    = new Doctor([$extractor]);

        $extractor
            ->expects($this->once())
            ->method('extract')
            ->with('file.PdF', 'pdf')
            ->will($this->returnValue([]));

        $doctor->extract('file.PdF');
    }

    public function testExtractedMetadataHaveDefaultValues()
    {
        $extractor = $this->getExtractorMock('pdf');
        $doctor    = new Doctor([$extractor]);

        $extractor
            ->expects($this->once())
            ->method('extract')
            ->with('file.pdf')
            ->will($this->returnValue([
                'author' => 'Joe' // only the author could be extracted
            ]));

        $this->assertSame([
            'author'        => 'Joe', // but the response contains all kind of metadata
            'title'         => '',
            'creation_date' => null,
            'keywords'      => [],
            'content'       => '',
        ], $doctor->extract('file.pdf'));
    }

    /**
     * @expectedException \Doctor\Exception\ExtractorNotFoundException
     */
    public function testAnExceptionIsThrownIfNoExtractorIsFound()
    {
        $extractor = $this->getExtractorMock('pdf');
        $doctor    = new Doctor([$extractor]);

        $extractor
            ->expects($this->never())
            ->method('extract');

        $doctor->extract('file.docx');
    }

    private function getExtractorMock($handledExtension)
    {
        $extractor = $this->getMock('Doctor\Extractor\Extractor');

        $extractor
            ->expects($this->any())
            ->method('supports')
            ->will($this->returnValueMap([
                [$handledExtension, true]
            ]));

        return $extractor;
    }
}
