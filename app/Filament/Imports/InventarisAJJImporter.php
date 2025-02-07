<?php
namespace App\Filament\Imports;

use App\Models\InventarisAJJ;
use App\Models\Pegawai;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// a.	Nama
// b.	NIP
// c.	UNIT Kerja
// d.	SK Jabatan
// e.	TMT Pemberian Tunjangan
// f.	UPLOAD BERKAS ( Jika Tidak memiliki SK Jabatan Fungsional Maka Upload SK Pemberian Tunjangan )
// g.	Surat Pengantar Unit Kerja


class InventarisAJJImporter extends Importer
{
    protected static ?string $model = InventarisAJJ::class;

    // Override mutateBeforeCreate untuk memastikan data diproses dengan benar
    public static function mutateBeforeCreate(array $data): array
    {
        Log::info('Data before mutation:', $data);
        return $data;
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('pegawai_nip')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('tmt_pemberian_tunjangan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('sk_jabatan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('upload_berkas')
                ->rules(['nullable']),
            ImportColumn::make('surat_pengantar_unit_kerja')
                ->rules(['nullable']),
        ];
    }

    public function resolve(array $data): array
    {
        Log::info('Resolving data:', $data);
        return $data;
    }

    public function resolveRecord(): ?InventarisAJJ
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            Log::info('Starting resolveRecord with data:', $this->data);

            // Validasi data yang diperlukan
            if (!isset($this->data['pegawai_nip'], $this->data['nama'], $this->data['unit_kerja_id'])) {
                Log::error('Missing required data', $this->data);
                DB::rollBack();
                return null;
            }

            // Cari atau buat Pegawai
            $pegawai = Pegawai::firstOrCreate(
                ['nip' => $this->data['pegawai_nip']],
                [
                    'nama' => $this->data['nama'],
                    'unit_kerja_id' => $this->data['unit_kerja_id'],
                ]
            );

            Log::info('Pegawai created/updated:', ['pegawai' => $pegawai->toArray()]);

            // Format tanggal
            $tmtDate = $this->data['tmt_pemberian_tunjangan'];

            if (!$tmtDate instanceof Carbon) {
                try {
                    $tmtDate = Carbon::parse($tmtDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::error('Error parsing date:', ['date' => $tmtDate, 'error' => $e->getMessage()]);
                    DB::rollBack();
                    return null;
                }
            } else {
                $tmtDate = $tmtDate->format('Y-m-d');
            }


            // Buat record InventarisAJJ
            $inventarisAJJ = new InventarisAJJ();
            $inventarisAJJ->pegawai_nip = $pegawai->nip;
            $inventarisAJJ->user_id = $user->id;
            $inventarisAJJ->tmt_pemberian_tunjangan = $tmtDate;
            $inventarisAJJ->sk_jabatan = $this->data['sk_jabatan'];
            $inventarisAJJ->upload_berkas = $this->data['upload_berkas'] ?? null;
            $inventarisAJJ->surat_pengantar_unit_kerja = $this->data['surat_pengantar_unit_kerja'] ?? null;

            $saved = $inventarisAJJ->save();
            
            if (!$saved) {
                Log::error('Failed to save InventarisAJJ');
                DB::rollBack();
                return null;
            }

            Log::info('InventarisAJJ saved successfully:', ['inventarisAJJ' => $inventarisAJJ->toArray()]);
            
            DB::commit();
            return $inventarisAJJ;

        } catch (\Exception $e) {
            Log::error('Error in resolveRecord:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $this->data
            ]);
            DB::rollBack();
            throw $e;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import data Usulan Penerbitan AJJ telah selesai dengan ' . 
                number_format($import->successful_rows) . ' ' . 
                str('data')->plural($import->successful_rows) . ' berhasil diimport.';
                
        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . 
                    str('data')->plural($failedRowsCount) . ' gagal diimport.';
        }
        
        return $body;
    }
}