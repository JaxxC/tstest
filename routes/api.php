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
Route::get('/forms', 'Api\FormController@index');
Route::get('/form/{form}', 'Api\FormController@show');
Route::get('/file/download/{file}', 'Api\FormFileController@download');

Route::post('/file/upload', 'Api\FormFileController@upload');
Route::post('/form', 'Api\FormController@create');
