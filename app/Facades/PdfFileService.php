<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PdfFileService extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'serviceForPdfFiles';
    }
}
