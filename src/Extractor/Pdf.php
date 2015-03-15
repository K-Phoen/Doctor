<?php

namespace Doctor\Extractor;

use Smalot\PdfParser\Parser;

class Pdf extends AbstractExtractor
{
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritDoc}
     */
    public function extract($filePath, $extension)
    {
        $content = file_get_contents($filePath);
        $pdf     = $this->parser->parseContent($content);
        $details = $pdf->getDetails();

        return [
            'author'        => $this->extractString($details, 'Author'),
            'title'         => $this->extractString($details, 'Title'),
            'creation_date' => $this->stringToDate($this->extractString($details, 'CreationDate')),
            'keywords'      => $this->extractList($details, 'Keywords'),
            'content'       => $pdf->getText(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function supports($extension)
    {
        return $extension === 'pdf';
    }

    private function extractString(array $data, $key)
    {
        return !empty($data[$key]) ? $data[$key] : '';
    }

    private function extractList(array $data, $key)
    {
        $list = $this->extractString($data, $key);

        if (empty($list)) {
            return [];
        }

        return array_map('trim', explode(',', $list));
    }
}
