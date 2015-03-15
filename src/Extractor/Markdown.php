<?php

namespace Doctor\Extractor;

use Kurenai\DocumentParser;

class Markdown extends AbstractExtractor
{
    private static $MARKDOWN_EXTENSIONS = ['md', 'mkd', 'markdown'];

    /**
     * @var DocumentParser $parser
     */
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
            'creation_date' => $this->stringToDate($document->get('date')),
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
}
