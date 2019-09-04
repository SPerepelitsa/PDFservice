<?php

namespace App\Http\Controllers\Mobile;

use Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\PdfFile;
use App\Services\PdfFileService;
use Illuminate\Support\Facades\Validator;

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
     *  Store pdf file to storage and write file info to DB
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->file(), [
            "file" => "required|file|mimes:pdf|max:16000"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $pdf = new PdfFile();
        $ownerId = auth('api')->id();
        $save = $pdf->saveFileAndData($request->file, $ownerId);

        if ($save) {
            return response()->json(['is_success' => true, 'message' =>'File has been successfully uploaded.'], 200);
        } else {
            return response()->json(['is_success' => false, 'message' => 'Upload: Failed to save file to storage'], 400);
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
