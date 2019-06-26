<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// pdffiles
Route::prefix('pdf')->group(function () {
    Route::get('/create', 'PdfFileController@create')->name('upload-form');
    Route::post('/store', 'PdfFileController@store')->name('upload-file');
    Route::get('/show/{uuid}', 'PdfFileController@show')->name('show');
    Route::delete('/delete/{id}', 'PdfFileController@destroy')->name('delete');
    Route::get('/download/{filename}', 'PdfFileController@download')->name('download');
});
