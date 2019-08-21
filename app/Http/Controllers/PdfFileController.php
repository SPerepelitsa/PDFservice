<?php

namespace App\Http\Controllers;

use Storage;
use App\PdfFile;
use App\Services\PdfFileService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PdfFileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'download']]);
    }


    /**
     * Show the form for file upload.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pdf.download-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "file" => "required|file|mimes:pdf|max:16000"
        ],
        [
            'file.required' => 'You have to choose the file!',
        ]);

        $pdfService = new PdfFileService($request->file);
        $pdf = new PdfFile();

        // store file to local storage and get path
        $path = Storage::putFile('public/pdf', $request->file('file'));

        $pdf->url_uuid = (string) Str::uuid();
        $pdf->title = $pdfService->getFileAttribute(PdfFile::ATTRIBUTES['title']);
        $pdf->description = $pdfService->getDescription();
        $pdf->key_words = $pdfService->getFileAttribute(PdfFile::ATTRIBUTES['key_words']);
        $pdf->metainfo = $pdfService->getFileMetaInfo();
        $pdf->name = $path ? basename($path) : null;
        $pdf->user_id = Auth::id();

        $pdf->save();

        return redirect('home')
            ->with('success','You have successfully upload file.');
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

        $metainfo = json_decode($file->metainfo);
        $fileName = $file->name;

        return view('pdf.show')->with('metainfo', $metainfo)->with('filename', $fileName);
    }

    /**
     * Pdf file download by name.
     *
     * @param  $fileName
     * @return \Illuminate\Http\Response
     */
    public function download($fileName)
    {
        return Storage::disk('public_pdf')->download($fileName);
    }

    /**
     * Remove the pdf file from DB and storage.
     *
     * @param  \App\PdfFile  $pdfFile
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = PdfFile::findOrFail($id);
        if (Storage::disk('public_pdf')->exists($file->name)) {
            Storage::disk('public_pdf')->delete($file->name);
        }
        $file->delete();

        return back()
            ->with('success','You have successfully delete file.');
    }
}
