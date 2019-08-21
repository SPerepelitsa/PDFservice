<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use App\PdfFile;

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

    public function getDescription()
    {
       $description = $this->getFileAttribute(PdfFile::ATTRIBUTES['description']);
       // if there is no description, take first 250 symbols of file text(body)
       if ($description == self::NO_VALUE) {
           $pages = $this->parsedPdf->getPages();
           foreach ($pages as $page) {
               // check every page until we got one(not empty) with a text and break the loop
                if($page->getText() !== ' ') {
                    $fileText =$page->getText();
                    break;
                }
           }
           $description = mb_strimwidth($fileText, 0, 250, "...");
       }

       return strip_tags(preg_replace("#[^а-яА-ЯA-Za-z;:_.,? -]+#u", '',  $description));
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
