<?php

namespace App;

use App\Services\PdfFileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PdfFile extends Model
{
    /**
     * @param PdfFileService $pdfService
     * @param UploadedFile $file
     * @param int $ownerId
     * @return bool
     * @throws \Exception
     */
    public function saveFileAndData(PdfFileService $pdfService, UploadedFile $file, int $ownerId): bool
    {
        $pdfFileDTO = $pdfService->getFileAttributes($file);
        //save pdf to storage
        $path = $pdfService->saveToStorageAndGetPath($file);
        // if file upload to storage fails
        if ($path === null) {
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
