<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSuratResource\Pages;
use App\Filament\Resources\PengajuanSuratResource\RelationManagers;
use App\Models\PengajuanSurat;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanSuratResource extends Resource
{
    protected static ?string $model = PengajuanSurat::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_own',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'kirim_notif'
        ];
    }

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Pengajuan Surat';
    protected static ?string $modelLabel = 'Pengajuan Surat';
    protected static ?string $label = 'Pengajuan Surat';
    protected static ?string $pluralLabel = 'Pengajuan Surat';
    protected static ?string $path = 'pengajuan-surat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor_sk')
                    ->label('Nomor SK')
                    ->required()
                    ->maxLength(255)
                    ->suffixAction(
                        Action::make('generateSk')
                            ->label('Generate SK')
                            ->icon('heroicon-o-arrow-path')
                            ->action(function (Forms\Set $set) {
                                // Ambil nomor SK terakhir dari database
                                $lastSk = PengajuanSurat::orderBy('nomor_sk', 'desc')->first();
                                
                                // Ekstrak nomor urut dari nomor SK terakhir
                                if ($lastSk && preg_match('/800\/(\d{2,})\/DINKES\/\d{4}/', $lastSk->nomor_sk, $matches)) {
                                    $newSkNumber = intval($matches[1]) + 1; // Tingkatkan nomor SK
                                } else {
                                    $newSkNumber = 1; // Mulai dari 01 jika tidak ada data
                                }

                                // Format nomor SK baru (misal: 800/01/DINKES/2024)
                                $formattedSk = sprintf("800/%02d/DINKES/%s", $newSkNumber, date('Y'));

                                // Set field nomor_sk dengan nomor baru
                                $set('nomor_sk', $formattedSk);
                            })
                        ),
                
                Select::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->options([
                        'Surat Masuk' => 'Surat Masuk',
                        'Surat Keluar' => 'Surat Keluar',
                    ])
                    ->required(),
                
                TextInput::make('perihal')
                    ->label('Perihal')
                    ->required()
                    ->maxLength(255),
                
                Select::make('status_pengajuan')
                    ->label('Status Pengajuan')
                    ->options([
                        'Diajukan' => 'Diajukan',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                    ])
                    ->required(),
                
                DateTimePicker::make('tgl_pengajuan')
                    ->label('Tanggal Pengajuan')
                    ->required(),
                
                DateTimePicker::make('tgl_diterima')
                    ->label('Tanggal Diterima')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_sk')
                    ->label('Nomor SK')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Surat Masuk' => 'success',
                        'Surat Keluar' => 'warning',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('perihal')
                    ->label('Perihal')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('status_pengajuan')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Diajukan' => 'warning',
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        default => 'info',
                    }),
                
                Tables\Columns\TextColumn::make('tgl_pengajuan')
                    ->label('Tanggal Pengajuan')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tgl_diterima')
                    ->label('Tanggal Diterima')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_surat')
                    ->label('Jenis Surat')
                    ->options([
                        'Surat Masuk' => 'Surat Masuk',
                        'Surat Keluar' => 'Surat Keluar',
                    ]),
                
                Tables\Filters\SelectFilter::make('status_pengajuan')
                    ->label('Status Pengajuan')
                    ->options([
                        'Diajukan' => 'Diajukan',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanSurats::route('/'),
            'create' => Pages\CreatePengajuanSurat::route('/create'),
            'edit' => Pages\EditPengajuanSurat::route('/{record}/edit'),
        ];
    }
}
