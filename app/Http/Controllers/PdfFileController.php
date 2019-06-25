<?php

namespace App\Http\Controllers;

use Storage;
use App\PdfFile;
use App\Services\PdfFileService;
use Illuminate\Http\Request;


class PdfFileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('download-form');
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

        $pdf->title = $pdfService->getFileAttribute(PdfFile::ATTRIBUTES['title']);
        $pdf->description = $pdfService->getFileAttribute(PdfFile::ATTRIBUTES['description']);
        $pdf->key_words = $pdfService->getFileAttribute(PdfFile::ATTRIBUTES['key_words']);
        $pdf->metainfo = $pdfService->getFileMetaInfo();
        $pdf->name = $path ? basename($path) : null;

        $pdf->save();

        return redirect('home')
            ->with('success','You have successfully upload file.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PdfFile  $pdfFile
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = PdfFile::findOrFail($id);

        $metainfo = json_decode($file->metainfo);

        return view('pdf.show')->with('metainfo', $metainfo);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PdfFile  $pdfFile
     * @return \Illuminate\Http\Response
     */
    public function edit(PdfFile $pdfFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PdfFile  $pdfFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PdfFile $pdfFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
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
