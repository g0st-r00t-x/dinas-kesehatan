<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DownloadPdfController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PenerimaanPengajuan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use LaravelQRCode\Facades\QRCode;

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
    return view('app');
});
Route::get('/test', function () {
    return view('home');
});

Route::get('download/{path}', [FileController::class, 'download'])
    ->name('download.private.file')
    ->middleware('signed'); 
Route::get('pdf/{ajj}', DownloadPdfController::class)->name('pdf');

Route::post('/pengajuan/{pengajuan}/izinkan', [PenerimaanPengajuan::class, 'izinkan']);

Route::get('/upload', [DocumentController::class, 'showForm'])->name('uploadForm');
Route::post('/process-document', [DocumentController::class, 'processDocument'])->name('processDocument');

Route::get('/info', function () {
    phpinfo();
});
