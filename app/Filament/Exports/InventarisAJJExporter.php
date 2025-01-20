<?php

namespace App\Filament\Exports;

use App\Models\InventarisAJJ;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class InventarisAJJExporter extends Exporter
{
    protected static ?string $model = InventarisAJJ::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('nama'),
            ExportColumn::make('nip'),
            ExportColumn::make('unit_kerja'),
            ExportColumn::make('tmt_pemberian_tunjangan'),
            ExportColumn::make('sk_jabatan'),
            ExportColumn::make('upload_berkas'),
            ExportColumn::make('surat_pengantar_unit_kerja'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your usulan penerbitan a j j export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
