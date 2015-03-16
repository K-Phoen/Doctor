<?php

namespace Doctor\Tests\Extractor;

use org\bovigo\vfs\vfsStream;

use Doctor\Extractor\Markdown;

class MarkdownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory $fs
     */
    protected $fs;

    public function setUp()
    {
        // and initialize the virtual filesystem
        $this->fs = vfsStream::setup('doctor', null, [
            'document.md' => 'content is irrelevant',
        ]);
    }

    /**
     * @dataProvider extractionProvider
     */
    public function testExtract($parsedDocument, $expectedResult)
    {
        $parser    = $this->getParserMock();
        $extractor = new Markdown($parser);

        $parser
            ->expects($this->once())
            ->method('parse')
            ->will($this->returnValue($parsedDocument));

        $this->assertEquals($expectedResult, $extractor->extract($this->fs->url() . '/document.md', 'md'));
    }

    public function extractionProvider()
    {
        $content = 'This is my **markdown** content!';

        $complete = [
            'title'         => 'Title',
            'creation_date' => \DateTime::createFromFormat('d-m-Y', '22-02-2015')->setTime(0, 0, 0),
            'content'       => $content,
        ];
        $noTitle = [
            'title'         => '',
            'creation_date' => \DateTime::createFromFormat('d-m-Y', '22-02-2015')->setTime(0, 0, 0),
            'content'       => $content,
        ];
        $noDate = [
            'title'         => 'Title',
            'creation_date' => null,
            'content'       => $content,
        ];

        return [
            [$this->getDocumentMock([
                ['title',   'Title'],
                ['date',    '22-02-2015'],
            ], $content), $complete],
            [$this->getDocumentMock([
                ['date',    '22-02-2015'],
            ], $content), $noTitle],
            [$this->getDocumentMock([
                ['title',   'Title'],
            ], $content), $noDate],
            [$this->getDocumentMock([
                ['title',   'Title'],
                ['date',    'invalid'],
            ], $content), $noDate],
        ];
    }

    /**
     * @dataProvider validExtensionProvider
     */
    public function testSupportWithValidExtension($extension)
    {
        $extractor = new Markdown($this->getParserMock());

        $this->assertTrue($extractor->supports($extension));
    }

    public function validExtensionProvider()
    {
        return [
            ['md'],
            ['mkd'],
            ['markdown'],
        ];
    }

    /**
     * @dataProvider invalidExtensionProvider
     */
    public function testSupportWithInvalidExtension($extension)
    {
        $extractor = new Markdown($this->getParserMock());

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
        return $this->getMock('Kurenai\DocumentParser');
    }

    private function getDocumentMock(array $data = [], $content = null)
    {
        $document = $this->getMock('Kurenai\Document');

        if (empty($data)) {
            return $document;
        }

        if ($content !== null) {
            $document
                ->expects($this->once())
                ->method('getContent')
                ->will($this->returnValue($content));
        }

        $document
            ->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($data));

        return $document;
    }
}
