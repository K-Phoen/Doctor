<?php

namespace Doctor\Extractor;

use PhpOffice\PhpWord\IOFactory as PhpWordReaderFactory;

class Word implements Extractor
{
    private static $EXTENSIONS_MAP = [
        'doc'  => 'MsDoc',
        'docx' => 'Word2007',
        'rtf'  => 'RTF',
        'odt'  => 'ODText',
    ];

    /**
     * {@inheritDoc}
     */
    public function extract($filePath, $extension)
    {
        $phpWord = PhpWordReaderFactory::load($filePath, self::$EXTENSIONS_MAP[$extension]);
        $details = $phpWord->getDocInfo();

        return [
            'author'        => $details->getCreator(),
            'title'         => $details->getTitle(),
            'creation_date' => $this->timestampToDateTime($details->getCreated()),
            'keywords'      => array_map('trim', explode(',', $details->getKeywords())),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports($extension)
    {
        return in_array($extension, array_keys(self::$EXTENSIONS_MAP));
    }

    private function timestampToDateTime($timestamp)
    {
        $date = new \DateTime();
        $date->setTimestamp($timestamp);

        return $date;
    }
}
