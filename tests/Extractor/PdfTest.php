<?php

namespace Doctor\Tests\Extractor;

use org\bovigo\vfs\vfsStream;

use Doctor\Extractor\Pdf;

class PdfTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory $fs
     */
    protected $fs;

    public function setUp()
    {
        // and initialize the virtual filesystem
        $this->fs = vfsStream::setup('doctor', null, [
            'test.pdf' => 'content is irrelevant'
        ]);
    }

    /**
     * @dataProvider extractionProvider
     */
    public function testExtract($extractedData, $expectedResult)
    {
        $parser    = $this->getParserMock();
        $document  = $this->getDocumentMock();
        $extractor = new Pdf($parser);

        $parser
            ->expects($this->once())
            ->method('parseContent')
            ->will($this->returnValue($document));

        $document
            ->expects($this->once())
            ->method('getDetails')
            ->will($this->returnValue($extractedData));

        $this->assertEquals($expectedResult, $extractor->extract($this->fs->url() . '/test.pdf', 'pdf'));
    }

    public function extractionProvider()
    {
        $noExtractedData = [
            'author'        => '',
            'title'         => '',
            'creation_date' => null,
            'keywords'      => [],
            'content'       => '',
        ];

        $date = \DateTime::createFromFormat('Y-m-d', '2015-03-15')->setTime(0, 0, 0);

        return [
            [[],                                $noExtractedData],
            [['CreationDate' => 'invalid'],     $noExtractedData],
            [['CreationDate' => '2015-03-15'],  array_merge($noExtractedData, ['creation_date' => $date])],
            [['Author' => 'Kevin'],             array_merge($noExtractedData, ['author' => 'Kevin'])],
        ];
    }

    /**
     * @dataProvider validExtensionProvider
     */
    public function testSupportWithValidExtension($extension)
    {
        $extractor = new Pdf($this->getParserMock());

        $this->assertTrue($extractor->supports($extension));
    }

    public function validExtensionProvider()
    {
        return [
            ['pdf'],
        ];
    }

    /**
     * @dataProvider invalidExtensionProvider
     */
    public function testSupportWithInvalidExtension($extension)
    {
        $extractor = new Pdf($this->getParserMock());

        $this->assertFalse($extractor->supports($extension));
    }

    public function invalidExtensionProvider()
    {
        return [
            [''],
            ['foo'],
        ];
    }

    private function getParserMock()
    {
        return $this->getMock('Smalot\PdfParser\Parser');
    }

    private function getDocumentMock()
    {
        return $this->getMock('Smalot\PdfParser\Document');
    }
}
