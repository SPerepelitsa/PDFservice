<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PdfFile extends Model
{
    /**
     * Save PDF file to storage and PDF file data to database.
     *
     * @param $pdfService
     * @param $file
     * @param $ownerId
     * @return bool
     */
    public function saveFileAndData($pdfService, $file, $ownerId)
    {
        $pdfFileDTO = $pdfService->getFileAttributes($file);
        //save pdf to storage
        $path = $pdfService->saveToStorageAndGetPath($file);
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
