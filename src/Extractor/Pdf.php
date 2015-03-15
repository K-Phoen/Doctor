<?php

namespace Doctor\Extractor;

use Smalot\PdfParser\Parser;

class Pdf implements Extractor
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
            'creation_date' => $this->extractDate($details, 'CreationDate'),
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

    private function extractDate(array $data, $key)
    {
        $dateAsString = $this->extractString($data, $key);

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
