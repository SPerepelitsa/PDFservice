<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\PdfFileService;
use Illuminate\Support\Str;


class PdfFile extends Model
{
    /**
     * Save PDF file to storage and PDF file data to database.
     *
     * @param $file
     * @param $ownerId
     * @return bool
     */
    public function saveFileAndData($file, $ownerId)
    {
        $pdfService = new PdfFileService($file);
        //save pdf to storage
        $path = $pdfService->saveToStorageAndGetPath();
        // if file upload to storage fails
        if ($path === false) {
            return false;
        }

        $this->url_uuid = (string)Str::uuid();
        $this->title = $pdfService->getTitle();
        $this->description = $pdfService->getDescription();
        $this->key_words = $pdfService->getKeyWords();
        $this->metainfo = $pdfService->getFileMetaInfo();
        $this->name = basename($path);
        $this->user_id = $ownerId;

        // return bool
        return $this->save();
    }
}
