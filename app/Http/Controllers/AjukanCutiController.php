<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use Filament\Notifications\Events\DatabaseNotificationsSent;
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
                'id_pemohon' => $record->id,
                'jenis_surat' => 'Surat Masuk',
                'perihal' => 'Permohonan Cuti ' . $pegawai->nama,
                'status_pengajuan' => 'Diajukan',
                'tgl_pengajuan' => now(),
            ]);

            // Kirim notifikasi ke operator yang memiliki permission view_any_pengajuan::surat
            $operators = User::permission('view_any_pengajuan::surat')->get();
            
            foreach ($operators as $operator) {
                Notification::make()
                    ->title('Pengajuan Cuti Baru')
                    ->body("Terdapat pengajuan cuti baru dari {$pegawai->nama} yang memerlukan persetujuan")
                    ->success()
                    ->sendToDatabase($operator, isEventDispatched: true);
                event(new DatabaseNotificationsSent($operator));
            }

            DB::commit();

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