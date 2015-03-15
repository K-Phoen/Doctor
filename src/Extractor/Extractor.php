<?php

namespace Doctor\Extractor;

interface Extractor
{
    /**
     * Extracts the metadata contained in the given file.
     *
     * @param string $filePath  The path to the file.
     * @param string $extension The file's extension.
     *
     * @return array
     */
    public function extract($filePath, $extension);

    /**
     * Indicates whether the given extension is supported or not.
     *
     * @param string $extension The extension to test.
     *
     * @return bool
     */
    public function supports($extension);
}
