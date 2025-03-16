<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'System';

    // Tambahkan ini untuk menonaktifkan global search
    protected static bool $shouldRegisterNavigation = true;
    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function table(Table $table): Table
    {
        return $table
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Filter berdasarkan jenis event
                SelectFilter::make('event')
                    ->options([
                        'created' => 'Dibuat',
                        'updated' => 'Diperbarui',
                        'deleted' => 'Dihapus',
                    ]),

                // Filter berdasarkan tanggal
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                // Filter berdasarkan jenis record
                SelectFilter::make('subject_type')
                    ->label('Tipe Record')
                    ->options(function () {
                        return ActivityLog::distinct()
                            ->pluck('subject_type')
                            ->mapWithKeys(function ($type) {
                                return [$type => class_basename($type)];
                            })
                            ->toArray();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }

    // Tambahan untuk label navigasi
    public static function getNavigationLabel(): string
    {
        return 'Log Aktivitas';
    }

    public static function getPluralLabel(): string
    {
        return 'Log Aktivitas';
    }

    public static function getModelLabel(): string
    {
        return 'Log Aktivitas';
    }
}
