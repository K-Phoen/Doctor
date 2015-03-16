<?php

namespace Doctor\Tests\Extractor;

use org\bovigo\vfs\vfsStream;

use Doctor\Extractor\Word;

class WordTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory $fs
     */
    protected $fs;

    public function setUp()
    {
        // and initialize the virtual filesystem
        $this->fs = vfsStream::setup('doctor', null, [
        ]);
    }

    /**
     * @dataProvider validExtensionProvider
     */
    public function testSupportWithValidExtension($extension)
    {
        $extractor = new Word();

        $this->assertTrue($extractor->supports($extension));
    }

    public function validExtensionProvider()
    {
        return [
            ['doc'],
            ['docx'],
            ['rtf'],
            ['odt'],
        ];
    }

    /**
     * @dataProvider invalidExtensionProvider
     */
    public function testSupportWithInvalidExtension($extension)
    {
        $extractor = new Word();

        $this->assertFalse($extractor->supports($extension));
    }

    public function invalidExtensionProvider()
    {
        return [
            [''],
            ['txt'],
            ['xls'],
        ];
    }
}
