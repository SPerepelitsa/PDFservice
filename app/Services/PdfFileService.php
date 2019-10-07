<?php

namespace App\Services;

use App\DTO\PdfFileDTO;
use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;
use Smalot\PdfParser\Document;
use Storage;

class PdfFileService
{
    private const FILE_ATTRIBUTES = [
        'title' => 'Title',
        'description' => 'Subject',
        'key_words' => 'Keywords'
    ];

    private const NO_VALUE = "None";

    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param UploadedFile $pdfFile
     * @return Document
     * @throws \Exception
     */
    private function parsePdf(UploadedFile $pdfFile): Document
    {
        $parser = $this->parser;

        return $parser->parseFile($pdfFile);
    }

    /**
     * @param Document $parsedPdf
     * @return array
     * @throws \Exception
     */
    private function getFileInfo(Document $parsedPdf): array
    {
        $fileInfo = $parsedPdf->getDetails();

        if (is_array($fileInfo) && !empty($fileInfo)) {
            return $fileInfo;
        } else {
            throw new \Exception('Can not get file info');
        }
    }

    /**
     * @param array $fileInfo
     * @return string
     */
    private function getTitle(array $fileInfo): string
    {
        return $fileInfo[self::FILE_ATTRIBUTES['title']] ?? self::NO_VALUE;
    }

    /**
     * @param array $fileInfo
     * @return string
     */
    private function getKeyWords(array $fileInfo): string
    {
        return $fileInfo[self::FILE_ATTRIBUTES['key_words']] ?? self::NO_VALUE;
    }

    /**
     * @param array $fileInfo
     * @param Document $parsedPdf
     * @return string
     * @throws \Exception
     */
    private function getDescription(array $fileInfo, Document $parsedPdf): string
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
     * @param array $fileInfo
     * @return string
     */
    private function getMetaInfo(array $fileInfo): string
    {
        $metaInfo = $fileInfo;

        return !empty($metaInfo) ? json_encode($metaInfo) : json_encode(self::NO_VALUE);
    }

    /**
     * @param UploadedFile $pdfFile
     * @return null|string
     */
    public function saveToStorageAndGetPath(UploadedFile $pdfFile): ?string
    {
        $path = Storage::putFile('public/pdf', $pdfFile);

        return $path ?: null;
    }

    /**
     * @param UploadedFile $pdfFile
     * @return PdfFileDTO
     * @throws \Exception
     */
    public function getFileAttributes(UploadedFile $pdfFile): PdfFileDTO
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
