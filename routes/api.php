<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// < ========== React API routes ========== >

// Auth React using JWT Token
Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('logout', 'API\AuthController@logout');
Route::post('refresh', 'API\AuthController@refresh');

// Pdffiles React
Route::prefix('pdf')->group(function () {
    Route::get('/files', 'API\ApiPdfFileController@getAll');
    Route::get('/upload', 'API\ApiPdfFileController@uploadForm');
    Route::post('/store', 'API\ApiPdfFileController@store');
    Route::get('/show/{uuid}', 'API\ApiPdfFileController@show');
    Route::get('/download/{filename}', 'API\ApiPdfFileController@download');
});

// < ========== Mobile API routes ========== >

// Auth API Mobile using access Token
Route::prefix('mobile')->group(function () {
    Route::post('login', 'Mobile\AuthController@login');
    Route::post('register', 'Mobile\AuthController@register');
    Route::post('logout', 'Mobile\AuthController@logout');
});

// Pdffiles Mobile
Route::prefix('mobile/pdf')->group(function () {
    Route::get('/files', 'Mobile\MobilePdfFileController@getAll');
    Route::get('/show/{uuid}', 'Mobile\MobilePdfFileController@show');
    Route::get('/download/{filename}', 'Mobile\MobilePdfFileController@download');
});


