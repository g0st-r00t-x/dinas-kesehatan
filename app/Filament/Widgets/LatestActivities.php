<?php

namespace App\Filament\Widgets;

use App\Models\SuratKeluar;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestActivities extends BaseWidget
{
    protected int|string|array $columnSpan = 'full'; // Agar tabel lebih luas

    public function table(Table $table): Table
    {
        return $table
            ->query(SuratKeluar::latest()->limit(10)) // Menampilkan 10 data terbaru dari surat_keluar
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                ->label('Nomor Surat')
                ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('perihal')
                ->label('Perihal')
                ->sortable(),
                Tables\Columns\TextColumn::make('tujuan_surat')
                ->label('Tujuan Surat')
                ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_surat')
                ->label('Tanggal Surat')
                ->date(),
                Tables\Columns\TextColumn::make('file_surat')
                ->label('File Surat')
                ->url(fn($record) => asset('storage/' . $record->file_surat))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('tanggal_surat', 'desc'); // Urutan dari yang terbaru
    }
    public static function getSort(): int
    {
        return 99; // Semakin besar angkanya, semakin ke bawah letaknya
    }
}
