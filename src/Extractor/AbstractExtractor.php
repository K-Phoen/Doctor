<?php

namespace Doctor\Extractor;

abstract class AbstractExtractor implements Extractor
{
    /**
     * Transforms a string to a DateTime object.
     *
     * @param string $dateAsString The date, in any format.
     *
     * @return \DateTime|null The date object or null if it fails.
     */
    protected function stringToDate($dateAsString)
    {
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
