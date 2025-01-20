<?php
namespace App\Filament\Imports;

use App\Models\InventarisAJJ;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Carbon\Carbon;

class InventarisAJJImporter extends Importer
{
    protected static ?string $model = InventarisAJJ::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nip')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('unit_kerja')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('tmt_pemberian_tunjangan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('sk_jabatan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('upload_berkas')
                ->rules(['max:255']),
            ImportColumn::make('surat_pengantar_unit_kerja')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?InventarisAJJ
    {
        return new InventarisAJJ();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your usulan penerbitan a j j import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';
        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }
        return $body;
    }
}