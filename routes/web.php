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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/exportExcel', 'ExcelController@exportExcel');
Route::get('/importExcel', 'ExcelController@importExcel');
Route::get('/bladeToExcel', 'ExcelController@bladeToExcel');

Route::post('/importExcelFromFile', 'ExcelController@importFromFile');






