<?php

namespace Doctor\Tests\Integration;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

abstract class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function getDocumentsDirectory();
    abstract protected function getExtractor();

    /**
     * @dataProvider documentsProvider
     */
    public function testExtract($file, $extension, $expectedData)
    {
        $extractor = $this->getExtractor();

        $this->assertEquals($expectedData, $extractor->extract($file, $extension));
    }

    public function documentsProvider()
    {
        $documentsDir = $this->getBaseDocumentsDirectory() . '/' . $this->getDocumentsDirectory();

        $documents = Finder::create()
            ->files()
            ->notName('*.yml')
            ->in($documentsDir);

        $testData = [];
        foreach ($documents as $document) {
            $filePath  = $document->getRealPath();
            $extension = $document->getExtension();

            $referenceFile = $document->getBasename('.'.$extension).'.metadata.yml';
            $referenceData = Yaml::parse($documentsDir . '/' . $referenceFile);

            if (!empty($referenceData['creation_date'])) {
                $referenceData['creation_date'] = \DateTime::createFromFormat(\DateTime::ISO8601, $referenceData['creation_date']);
            }

            $testData[] = [$filePath, $extension, $referenceData];
        }

        return $testData;
    }

    private function getBaseDocumentsDirectory()
    {
        return __DIR__ . '/../Fixtures';
    }
}
