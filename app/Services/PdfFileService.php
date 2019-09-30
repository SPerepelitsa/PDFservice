<?php

namespace App\Services;

use App\DTO\PdfFileDTO;
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

    public function __construct()
    {
        //
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
     * @param $parsedPdf
     * @return mixed
     * @throws \Exception
     */
    private function getFileInfo($parsedPdf)
    {
        $fileInfo = $parsedPdf->getDetails();

        if (is_array($fileInfo) && !empty($fileInfo)) {
            return $fileInfo;
        } else {
            throw new \Exception('Can not get file info');
        }
    }

    /**
     * @param $fileInfo
     * @return string
     */
    private function getTitle($fileInfo)
    {
        return $fileInfo[self::FILE_ATTRIBUTES['title']] ?? self::NO_VALUE;
    }

    /**
     * @param $fileInfo
     * @return string
     */
    private function getKeyWords($fileInfo)
    {
        return $fileInfo[self::FILE_ATTRIBUTES['key_words']] ?? self::NO_VALUE;
    }

    /**
     * @param $fileInfo
     * @param $parsedPdf
     * @return string
     */
    private function getDescription($fileInfo, $parsedPdf)
    {
        $description = $fileInfo[self::FILE_ATTRIBUTES['description']] ?? self::NO_VALUE;
        // if there is no description, take first 250 symbols of file text(body)
        if ($description == self::NO_VALUE) {
            $pages = $parsedPdf->getPages();
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
     * @param $fileInfo
     * @return string
     */
    private function getMetaInfo($fileInfo)
    {
        $metaInfo = $fileInfo;

        return !empty($metaInfo) ? json_encode($metaInfo) : json_encode(self::NO_VALUE);
    }

    /**
     * @param $pdfFile
     * @return bool
     */
    public function saveToStorageAndGetPath($pdfFile)
    {
        $path = Storage::putFile('public/pdf', $pdfFile);

        return $path ?: false;
    }

    /**
     * @param $pdfFile
     * @return PdfFileDTO
     * @throws \Exception
     */
    public function getFileAttributes($pdfFile)
    {
        $parsedPdf = $this->parsePdf($pdfFile);
        $allFileInfo = $this->getFileInfo($parsedPdf);
        $title = $this->getTitle($allFileInfo);
        $description = $this->getDescription($allFileInfo, $parsedPdf);
        $keyWords = $this->getKeyWords($allFileInfo);
        $metaInfo = $this->getMetaInfo($allFileInfo);

        return new PdfFileDTO($title, $description, $keyWords, $metaInfo);
    }
}
