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
            'complete.md'     => $this->getCompleteMarkdownDocument(),
            'no_title.md'     => $this->getNoTitleMarkdownDocument(),
            'no_date.md'      => $this->getNoDateMarkdownDocument(),
            'invalid_date.md' => $this->getInvalidDateMarkdownDocument(),
        ]);
    }

    /**
     * @dataProvider extractionProvider
     */
    public function testExtract($file, $expectedResult)
    {
        $parser    = $this->getParser();
        $extractor = new Markdown($parser);

        $this->assertEquals($expectedResult, $extractor->extract($this->fs->url() . DIRECTORY_SEPARATOR . $file, 'md'));
    }

    public function extractionProvider()
    {
        $complete = [
            'title'         => 'Title',
            'creation_date' => \DateTime::createFromFormat('d-m-Y', '22-02-2015')->setTime(0, 0, 0),
            'content'       => 'This is my **markdown** content!',
        ];
        $noTitle = [
            'title'         => '',
            'creation_date' => \DateTime::createFromFormat('d-m-Y', '22-02-2015')->setTime(0, 0, 0),
            'content'       => 'This is my **markdown** content!',
        ];
        $noDate = [
            'title'         => 'Title',
            'creation_date' => null,
            'content'       => 'This is my **markdown** content!',
        ];

        return [
            ['complete.md',     $complete],
            ['no_title.md',     $noTitle],
            ['no_date.md',      $noDate],
            ['invalid_date.md', $noDate],
        ];
    }

    /**
     * @dataProvider validExtensionProvider
     */
    public function testSupportWithValidExtension($extension)
    {
        $parser    = $this->getParser();
        $extractor = new Markdown($parser);

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
        $parser    = $this->getParser();
        $extractor = new Markdown($parser);

        $this->assertFalse($extractor->supports($extension));
    }

    public function invalidExtensionProvider()
    {
        return [
            [''],
            ['foo'],
        ];
    }

    private function getParser()
    {
        return new \Kurenai\DocumentParser();
    }

    private function getCompleteMarkdownDocument()
    {
        return
'title: Title
date: 22-02-2015
-------
This is my **markdown** content!';
    }

    private function getNoTitleMarkdownDocument()
    {
        return
'date: 22-02-2015
-------
This is my **markdown** content!';
    }

    private function getNoDateMarkdownDocument()
    {
        return
'title: Title
-------
This is my **markdown** content!';
    }

    private function getInvalidDateMarkdownDocument()
    {
        return
'title: Title
date: invalid
-------
This is my **markdown** content!';
    }
}
