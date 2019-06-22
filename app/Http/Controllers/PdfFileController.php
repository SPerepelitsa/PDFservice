<?php

namespace App\Http\Controllers;

use App\PdfFile;
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
        //
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
            "file" => "required|mimes:pdf|max:16000"
        ],
        [
            'file.required' => 'You have to choose the file!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PdfFile  $pdfFile
     * @return \Illuminate\Http\Response
     */
    public function show(PdfFile $pdfFile)
    {
        //
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
    public function destroy(PdfFile $pdfFile)
    {
        //
    }
}
