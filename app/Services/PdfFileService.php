<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfFileService
{
    private const NO_VALUE = "None";

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

    public function getFileAttribute($attribute)
    {
        return array_key_exists($attribute, $this->fileInfo) ? $this->fileInfo[$attribute]: self::NO_VALUE;
    }

    public function getFileMetaInfo()
    {
        $metaInfo = $this->fileInfo;

        return !empty($metaInfo) ? json_encode($metaInfo) : json_encode(self::NO_VALUE);
    }

}
