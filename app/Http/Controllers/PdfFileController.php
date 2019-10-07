<?php

namespace App\Http\Controllers;

use App\Services\PdfFileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Storage;
use App\PdfFile;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;


class PdfFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'download']]);
    }


    /**
     *  Show the form for file upload.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('pdf.download-form');
    }

    /**
     * @param PdfFileService $pdfService
     * @param Request $request
     * @param PdfFile $pdf
     * @return RedirectResponse
     */
    public function store(PdfFileService $pdfService, Request $request, PdfFile $pdf): RedirectResponse
    {
        $request->validate([
            "file" => "required|file|mimes:pdf|max:16000"
        ],
        [
            'file.required' => 'You have to choose the file!',
        ]);

        $ownerId = Auth::id();
        try {
            $save = $pdf->saveFileAndData($pdfService, $request->file, $ownerId);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        if ($save) {
            return redirect('home')
                ->with('success', 'You have successfully upload file.');
        } else {
            return back()->withErrors('Upload: Failed to save file to storage');
        }
    }

    /**
     * Display single pdf file info.
     *
     * @param string $uuid
     * @return View
     */
    public function show(string $uuid): View
    {
        $file = PdfFile::where('url_uuid', $uuid)->firstOrFail();

        $metainfo = json_decode($file->metainfo);
        $fileName = $file->name;

        return view('pdf.show')->with('metainfo', $metainfo)->with('filename', $fileName);
    }

    /**
     * Pdf file download by name.
     *
     * @param string $fileName
     * @return StreamedResponse
     */
    public function download(string $fileName): StreamedResponse
    {
        return Storage::disk('public_pdf')->download($fileName);
    }

    /**
     * Remove the pdf file from DB and storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $file = PdfFile::findOrFail($id);
        if (Storage::disk('public_pdf')->exists($file->name)) {
            Storage::disk('public_pdf')->delete($file->name);
        }
        $file->delete();

        return back()
            ->with('success', 'You have successfully delete file.');
    }
}
