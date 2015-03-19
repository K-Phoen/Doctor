<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Doctor\Doctor;
use Doctor\Extractor;

class FeatureContext implements Context, SnippetAcceptingContext
{
    private $documentsDirectory = '';
    private $metadatas          = [];
    private $doctor;

    public function __construct()
    {
        $this->initDoctor();
    }

    private function initDoctor()
    {
        $markdownExtractor = new Extractor\Markdown(new \Kurenai\DocumentParser());
        $pdfExtractor      = new Extractor\Pdf(new \Smalot\PdfParser\Parser());
        $wordExtractor     = new Extractor\Word();

        $this->doctor = new Doctor([
            $markdownExtractor,
            $pdfExtractor,
            $wordExtractor,
        ]);
    }

    /**
     * @Given the files are located in the :directory directory
     */
    public function theFilesAreLocatedInTheDirectory($directory)
    {
        $this->documentsDirectory = __DIR__ . '/../../documents/' . $directory;
    }

    /**
     * @When I extract metadata from :file
     */
    public function iExtractMetadataFrom($file)
    {
        $this->metadatas = $this->doctor->extract($this->documentsDirectory . '/' . $file);
    }

    /**
     * @Then the creation date should be :value
     */
    public function theCreationDateShouldBe($value)
    {
        $date = isset($this->metadatas['creation_date']) ? $this->metadatas['creation_date']->format('Y-m-d H:i:s') : '';

        \PHPUnit_Framework_Assert::assertEquals($value, $date);
    }

    /**
     * @Then the keywords :keywords should be found
     */
    public function theKeywordsShouldBeFound($keywords)
    {
        $keywords = array_map('trim', explode(',', $keywords));

        \PHPUnit_Framework_Assert::assertEquals($keywords, $this->metadatas['keywords']);
    }

    /**
     * @Then the :metadata should be :value
     */
    public function theMetadataShouldBe($metadata, $value)
    {
        \PHPUnit_Framework_Assert::assertSame($value, $this->metadatas[$metadata]);
    }
}
