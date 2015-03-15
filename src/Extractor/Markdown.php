<?php

namespace Doctor\Extractor;

use Kurenai\Document;
use Kurenai\DocumentParser;

class Markdown implements Extractor
{
    private static $MARKDOWN_EXTENSIONS = ['md', 'mkd', 'markdown'];

    private $parser;

    public function __construct(DocumentParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($filePath, $extension)
    {
        $source   = file_get_contents($filePath);
        $document = $this->parser->parse($source);

        return [
            'title'         => $document->get('title'),
            'creation_date' => $this->extractDate($document, 'date'),
            'content'       => $document->getContent(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports($extension)
    {
        return in_array($extension, self::$MARKDOWN_EXTENSIONS);
    }

    private function extractDate(Document $document, $key)
    {
        $dateAsString = $document->get($key);

        if (empty($dateAsString)) {
            return null;
        }

        $time = strtotime($dateAsString);

        if ($time === false) {
            return null;
        }

        $date = new \DateTime();
        $date->setTimestamp($time);

        return $date;
    }
}
