<?php

namespace App\Filament\Widgets;

use App\Models\ActivityLog;
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
            ->query(ActivityLog::query()->latest()->limit(10))  // Wrap with query()
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('properties')
                    ->label('Detail')
                    ->wrap()
                    ->limit(50),
            ])
            ->defaultSort('created_at', 'desc'); // Urutan dari yang terbaru
    }
    public static function getSort(): int
    {
        return 99; // Semakin besar angkanya, semakin ke bawah letaknya
    }
}
