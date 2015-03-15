<?php

namespace Doctor;

use Doctor\Exception\ExtractorNotFoundException;
use Doctor\Extractor\Extractor;

class Doctor
{
    private $extractors = [];

    public function __construct(array $extractors = [])
    {
        foreach ($extractors as $extractor) {
            $this->registerExtractor($extractor);
        }
    }

    public function registerExtractor(Extractor $extractor)
    {
        $this->extractors[] = $extractor;
    }

    public function extract($filePath, $extension = null)
    {
        $extension = $extension ?: $this->getExtensionFromPath($filePath);
        $extractor = $this->findExtractor($filePath, $extension);
        $metadata  = $extractor->extract($filePath, $extension);

        return array_merge($this->getDefaultMetadata(), $metadata); // @todo: transform the metadata array into an object
    }

    private function getDefaultMetadata()
    {
        return [
            'author'        => '',
            'title'         => '',
            'creation_date' => null,
            'keywords'      => [],
            'content'       => '',
        ];
    }

    private function findExtractor($filePath, $extension)
    {
        foreach ($this->extractors as $extractor) {
            if ($extractor->supports($extension)) {
                return $extractor;
            }
        }

        throw new ExtractorNotFoundException('No extractor found for file: '.$filePath);
    }

    private function getExtensionFromPath($path)
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }
}
