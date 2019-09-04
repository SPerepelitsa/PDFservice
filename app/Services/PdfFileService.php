<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use Storage;

class PdfFileService
{
    private const FILE_ATTRIBUTES = [
        'title' => 'Title',
        'description' => 'Subject',
        'key_words' => 'Keywords'
    ];

    private const NO_VALUE = "None";

    private $pdfFile;

    private $parsedPdf;

    private $fileInfo;

    public function __construct($pdfFile)
    {
        $this->pdfFile = $pdfFile;
        $this->parsedPdf = $this->parsePdf($pdfFile);
        $this->fileInfo = $this->setFileInfo();
    }

    /**
     * @param $pdfFile
     * @return \Smalot\PdfParser\Document
     * @throws \Exception
     */
    private function parsePdf($pdfFile)
    {
        $parser = new Parser();

        return $parser->parseFile($pdfFile);
    }

    /**
     * @return array
     */
    private function setFileInfo()
    {
        $fileInfo = $this->parsedPdf->getDetails();

        return is_array($fileInfo) ? $fileInfo : [];
    }

    /**
     * @param $attribute
     * @return mixed|string
     */
    private function getFileAttribute($attribute)
    {
        return array_key_exists($attribute, $this->fileInfo) ? $this->fileInfo[$attribute] : self::NO_VALUE;
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->getFileAttribute(self::FILE_ATTRIBUTES['title']);
    }

    /**
     * @return mixed|string
     */
    public function getKeyWords()
    {
        return $this->getFileAttribute(self::FILE_ATTRIBUTES['key_words']);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getDescription()
    {
        $description = $this->getFileAttribute(self::FILE_ATTRIBUTES['description']);
        // if there is no description, take first 250 symbols of file text(body)
        if ($description == self::NO_VALUE) {
            $pages = $this->parsedPdf->getPages();
            foreach ($pages as $page) {
                // check every page until we got one(not empty) with a text and break the loop
                if ($page->getText() !== ' ') {
                    $fileText = $page->getText();
                    break;
                }
            }
            $description = mb_strimwidth($fileText, 0, 250, "...");
        }

        return strip_tags(preg_replace("#[^а-яА-ЯA-Za-z0-9;:_.,? -]+#u", '', $description));
    }

    /**
     * @return string
     */
    public function getFileMetaInfo()
    {
        $metaInfo = $this->fileInfo;

        return !empty($metaInfo) ? json_encode($metaInfo) : json_encode(self::NO_VALUE);
    }

    /**
     * @return bool|string
     */
    public function saveToStorageAndGetPath()
    {
        $path = Storage::putFile('public/pdf', $this->pdfFile);

        return $path ?: false;
    }
}
