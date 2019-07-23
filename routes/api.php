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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@register');
Route::post('logout', 'API\AuthController@logout');
Route::post('refresh', 'API\AuthController@refresh');

// pdffiles
Route::prefix('pdf')->group(function () {
    Route::get('/files', 'API\ApiPdfFileController@getAll')->middleware('auth:api');
    Route::get('/show/{uuid}', 'API\ApiPdfFileController@show');
    Route::get('/download/{filename}', 'API\ApiPdfFileController@download');
});
