<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventarisAJJ;
use Barryvdh\DomPDF\Facade\Pdf;
 
class DownloadPdfController extends Controller
{
    public function __invoke(InventarisAJJ $ajj)
    {
        $pdf = Pdf::loadView('pdf', ['record' => $ajj]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Times-Roman',
            'isPhpEnabled' => true,
            'autoScriptToLang' => true,
            'defaultPaperSize' => 'a4',
            'dpi' => 150,
            'defaultEncoding' => 'UTF-8',
        ]);
        return $pdf
                ->download($ajj->pegawai_nip . '-' . $ajj->tmt_pemberian_tunjangan . '.pdf');
    }
}