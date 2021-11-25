<?php

use Illuminate\Support\Facades\Route;

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
    return view('app');
});


Auth::routes();

Route::get('/scan', 'App\Http\Controllers\ScannerController@index')->name('scan');
Route::get('/scan-otp', 'App\Http\Controllers\ScannerController@scan_otp')->name('scan_otp');
Route::get('/request-otp', 'App\Http\Controllers\ScannerController@request_otp')->name('request');
Route::get('/fetch-subjects', 'App\Http\Controllers\ScannerController@fetch_subjects')->name('fetch');
Route::get('/search', 'App\Http\Controllers\ScannerController@search')->name('search');