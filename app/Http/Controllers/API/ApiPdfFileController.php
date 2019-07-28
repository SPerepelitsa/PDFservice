<?php

namespace App\Http\Controllers\API;

use Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\PdfFile;

class ApiPdfFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['show', 'download']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $pdfFiles = PdfFile::where('user_id', Auth::id())->get();

        return response()->json(['pdf_files' => $pdfFiles], 200);
    }

    /**
     * Show the form for file upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        return response()->json(['is_success' => true, 'message' => 'All Good'], 200);
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
            $fileDownloadLink = url("api/pdf/download/{$file->name}");

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
