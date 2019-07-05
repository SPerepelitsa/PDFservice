<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PdfFile;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pdfFiles = PdfFile::where('user_id', Auth::id())->get();

        return view('home')->with('files', $pdfFiles);
    }
}
