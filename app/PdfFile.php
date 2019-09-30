<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PdfFileService;

class PdfFile extends Model
{
    /**
     * Save PDF file to storage and PDF file data to database.
     *
     * @param $file
     * @param $ownerId
     * @return bool
     * @throws \Exception
     */
    public function saveFileAndData($file, $ownerId)
    {
        $pdfFileDTO = PdfFileService::getFileAttributes($file);
        //save pdf to storage
        $path = PdfFileService::saveToStorageAndGetPath($file);
        // if file upload to storage fails
        if ($path === false) {
            return false;
        }

        $this->url_uuid = (string)Str::uuid();
        $this->title = $pdfFileDTO->getTitle();
        $this->description = $pdfFileDTO->getDescription();
        $this->key_words = $pdfFileDTO->getKeyWords();
        $this->metainfo = $pdfFileDTO->getMetaInfo();
        $this->name = basename($path);
        $this->user_id = $ownerId;

        // return bool
        return $this->save();
    }
}
