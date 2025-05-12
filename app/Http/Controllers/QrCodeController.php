<?php

namespace App\Http\Controllers;

use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class QrCodeController extends Controller
{
    public function generate(Request $request)
    {
        $text = $request->input('text', 'Default QR Code Content');

        $qrCode = FacadesQrCode::format('png')
            ->size(60)
            ->generate($text);

        return $qrCode;
    }
}
