<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('test');
});

Route::get('download/{path}', [FileController::class, 'download'])
    ->name('download.private.file')
    ->middleware('signed'); 
Route::get('pdf/{ajj}', DownloadPdfController::class)->name('pdf');

Route::get('/upload', [DocumentController::class, 'showForm'])->name('uploadForm');
Route::post('/process-document', [DocumentController::class, 'processDocument'])->name('processDocument');