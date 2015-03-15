<?php

namespace Doctor;

use Doctor\Exception\ExtractorNotFoundException;
use Doctor\Extractor\Extractor;

class Doctor
{
    /**
     * @var array<Extractor> $extractors
     */
    private $extractors = [];

    /**
     * @param array<Extractor> $extractors
     */
    public function __construct(array $extractors = [])
    {
        foreach ($extractors as $extractor) {
            $this->registerExtractor($extractor);
        }
    }

    /**
     * Register a new extractor.
     *
     * @param Extractor $extractor The extractor.
     */
    public function registerExtractor(Extractor $extractor)
    {
        $this->extractors[] = $extractor;
    }

    /**
     * Extract the metadata from a file.
     *
     * @param string $filePath  The path to the file.
     * @param string $extension The file's extension. If null, will be guessed using the path.
     *
     * @throws ExtractorNotFoundException If the metadata can not be extracted.
     *
     * @return array The extracted metadata
     */
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
