<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use Illuminate\Http\Request;

class SavePdfController extends Controller
{
    //Ini akan dipanggil saat pengajuan_surat dikonfirmasi oleh admin menjadi diterima dan nomor_sk sudah dibuatkan
    public function savePdf(Request $request)
    {   
        $permohonaCuti = PermohonanCuti::find($request->id);
        $pegawai = Pegawai::where('nip', $request->pegawai_nip)->first();
        $id_pengajuan_surat = $request->id_pengajuan_surat;
        $nomor_sk = $request->nomor_sk;
        $pengajuan_surat = PengajuanSurat::find($id_pengajuan_surat);
        $pengajuan_surat->nomor_sk = $nomor_sk;
        $pengajuan_surat->save();
        return redirect()->route('pengajuan_surat.index')->with('success', 'Nomor SK berhasil disimpan');
    }
}
