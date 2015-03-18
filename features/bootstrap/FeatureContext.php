<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Doctor\Doctor;
use Doctor\Extractor\Markdown;

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
        $markdownExtractor = new Markdown(new \Kurenai\DocumentParser());

        $this->doctor = new Doctor([
            $markdownExtractor,
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
     * @Then the :metadata should be :value
     */
    public function theMetadataShouldBe($metadata, $value)
    {
        \PHPUnit_Framework_Assert::assertSame($this->metadatas[$metadata], $value);
    }
}
