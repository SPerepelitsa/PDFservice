<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfFileService
{
    const NO_VALUE = "None";

    private $parsedPdf;

    private $fileInfo;

    public function __construct($pdfFile)
    {
        $this->parsedPdf = $this->parsePdf($pdfFile);
        $this->fileInfo = $this->setFileInfo();
    }

    private function parsePdf($pdfFile)
    {
        $parser = new Parser();

        return $parser->parseFile($pdfFile);
    }

    private function setFileInfo()
    {
        $fileInfo = $this->parsedPdf->getDetails();

        return is_array($fileInfo) ? $fileInfo : [];
    }

    public function getFileTitle()
    {
        return array_key_exists('Title', $this->fileInfo) ? $this->fileInfo['Title']: self::NO_VALUE;
    }

    public function getFileDescription()
    {
        return array_key_exists('Subject', $this->fileInfo) ? $this->fileInfo['Subject']: self::NO_VALUE;
    }

    public function getKeyWords()
    {
        return array_key_exists('Keywords', $this->fileInfo) ? $this->fileInfo['Keywords']: self::NO_VALUE;
    }

    public function getFileMetaInfo()
    {
        $metaInfo = $this->fileInfo;

        return !empty($metaInfo) ? json_encode($metaInfo) : json_encode(self::NO_VALUE);
    }

}
