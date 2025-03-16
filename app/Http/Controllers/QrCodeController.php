<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelQRCode\Facades\QRCode;

class QrCodeController extends Controller
{
    public function generate(Request $request)
    {
        $text = $request->input('text', 'Default QR Code Content');

        $qrCode = QRCode::format('png')
            ->size(300)
            ->generate($text);

        return $qrCode;
    }
}
