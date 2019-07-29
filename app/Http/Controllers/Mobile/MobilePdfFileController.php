<?php

namespace App\Http\Controllers\Mobile;

use Storage;
use App\Http\Controllers\Controller;
use App\PdfFile;

class MobilePdfFileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['show', 'download']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $pdfFiles = PdfFile::where('user_id', auth('api')->id())->get();

        return response()->json(['pdf_files' => $pdfFiles], 200);
    }

    /**
     * Display single pdf file info.
     *
     * @param  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $file = PdfFile::where('url_uuid', $uuid)->firstOrFail();
        if ($file) {
            $fileDownloadLink = url("api/mobile/pdf/download/{$file->name}");
            return response()->json(['file_info' => $file->metainfo, 'download_link' => $fileDownloadLink], 200);
        } else {
            return response()->json(['error' => 'File does not exist or has been deleted'], 404);
        }
    }

    /**
     * Pdf file download by name.
     *
     * @param  $fileName
     * @return \Illuminate\Http\Response
     */
    public function download($fileName)
    {
        if (Storage::disk('public_pdf')->exists($fileName)) {
            $filePath = Storage::disk('public_pdf')->path($fileName);
            return response()->download($filePath);
        }
        return response()->json(['error' => 'File does not exist or has been deleted'], 404);
    }
}
