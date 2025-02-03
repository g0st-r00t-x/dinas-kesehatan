<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AjukanCutiController extends Controller
{
    public function handle(PermohonanCuti $record)
    {
        DB::beginTransaction();
        try {
            // Ambil data pegawai berdasarkan NIP
            $pegawai = Pegawai::where('nip', $record->pegawai_nip)->first();
            if (!$pegawai) {
                throw new Exception('Pegawai tidak ditemukan.');
            }

            // Buat pengajuan surat
            $pengajuanSurat = PengajuanSurat::create([
                'id_pemohon' => $record->id, // Gunakan id yang sudah ada
                'jenis_surat' => 'Surat Masuk',
                'perihal' => 'Permohonan Cuti ' . $pegawai->nama,
                'status_pengajuan' => 'Diajukan',
                'tgl_pengajuan' => now(),
            ]);

            DB::commit();

            // Notifikasi sukses
            Notification::make()
                ->title('Pengajuan Cuti Berhasil')
                ->success()
                ->body("Pengajuan cuti untuk {$pegawai->nama} telah berhasil diajukan.")
                ->send();
        } catch (Exception $e) {
            DB::rollBack();

            // Notifikasi error
            Notification::make()
                ->title('Gagal Mengajukan Cuti')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
}
