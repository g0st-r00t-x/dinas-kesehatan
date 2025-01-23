<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanRekomendasiPenelitianResource\Pages;
use App\Filament\Resources\UsulanRekomendasiPenelitianResource\RelationManagers;
use App\Models\UsulanRekomendasiPenelitian;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class UsulanRekomendasiPenelitianResource extends Resource
{
    protected static ?string $model = UsulanRekomendasiPenelitian::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Usulan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Peneliti'),
                
                TextInput::make('judul_penelitian')
                    ->required()
                    ->maxLength(255)
                    ->label('Judul Penelitian'),
                
                TextInput::make('asal_lembaga_pendidikan')
                    ->required()
                    ->maxLength(255)
                    ->label('Asal Lembaga Pendidikan'),
                
                Select::make('tujuan_penelitian')
                    ->required()
                    ->options([
                        'magang_pkl' => 'Magang/PKL',
                        'penyusunan_tesis' => 'Penyusunan Tesis',
                        'penyusunan_skripsi' => 'Penyusunan Skripsi',
                        'penyusunan_riset' => 'Penyusunan Riset'
                    ])
                    ->label('Tujuan Penelitian'),
                
                TextInput::make('nim_nip')
                    ->required()
                    ->maxLength(255)
                    ->label('NIM/NIP'),
                
                FileUpload::make('surat_pengantar_path')
                    ->directory('rekomendasi-penelitian/surat-pengantar')
                    ->visibility('private')
                    ->label('Surat Pengantar')
                    ->preserveFilenames(),
                
                TextInput::make('no_telp_wa')
                    ->required()
                    ->tel()
                    ->maxLength(20)
                    ->label('Nomor WhatsApp'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->label('Nama Peneliti'),
                
                TextColumn::make('judul_penelitian')
                    ->searchable()
                    ->label('Judul Penelitian'),
                
                TextColumn::make('asal_lembaga_pendidikan')
                    ->searchable()
                    ->label('Asal Lembaga'),
                
                TextColumn::make('tujuan_penelitian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'magang_pkl' => 'info',
                        'penyusunan_tesis' => 'success',
                        'penyusunan_skripsi' => 'warning',
                        'penyusunan_riset' => 'primary',
                    })
                    ->label('Tujuan Penelitian'),
                
                TextColumn::make('nim_nip')
                    ->searchable()
                    ->label('NIM/NIP'),
                
                TextColumn::make('no_telp_wa')
                    ->searchable()
                    ->label('Kontak WA'),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Pengajuan'),
                TextColumn::make('surat_pengantar_path')
                    ->label('Surat Pengantar Unit Kerja')
                    ->url(fn ($record) => Storage::url($record->surat_pengantar_path))
                    ->openUrlInNewTab(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tujuan_penelitian')
                    ->options([
                        'magang_pkl' => 'Magang/PKL',
                        'penyusunan_tesis' => 'Penyusunan Tesis',
                        'penyusunan_skripsi' => 'Penyusunan Skripsi',
                        'penyusunan_riset' => 'Penyusunan Riset'
                    ])
                    ->label('Filter Tujuan Penelitian')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download_surat')
                    ->label('Unduh Surat')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        if ($record->surat_pengantar_path) {
                            return Storage::download($record->surat_pengantar_path);
                        }
                    })
                    ->visible(fn ($record) => !empty($record->surat_pengantar_path))
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
            'index' => Pages\ListUsulanRekomendasiPenelitians::route('/'),
            'create' => Pages\CreateUsulanRekomendasiPenelitian::route('/create'),
            'edit' => Pages\EditUsulanRekomendasiPenelitian::route('/{record}/edit'),
        ];
    }
}
