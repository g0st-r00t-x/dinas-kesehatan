<?php

namespace App\Filament\Imports;

use App\Models\UsulanPermohonanPensiun;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PermohonanPensiunImporter extends Importer
{
    protected static ?string $model = UsulanPermohonanPensiun::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?UsulanPermohonanPensiun
    {
        // return UsulanPermohonanPensiun::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new UsulanPermohonanPensiun();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your permohonan pensiun import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
