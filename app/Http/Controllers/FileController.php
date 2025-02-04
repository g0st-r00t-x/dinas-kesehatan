<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function download($path)
    {
        $decodedPath = base64_decode($path);
        
        if (!Storage::exists($decodedPath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Stream file PDF
        return response()->file(
            Storage::path($decodedPath),
            ['Content-Type' => 'application/pdf']
        );
    }
}