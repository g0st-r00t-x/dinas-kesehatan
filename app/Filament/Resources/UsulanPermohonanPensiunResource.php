<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PermohonanPensiunExporter;
use App\Filament\Imports\PermohonanPensiunImporter;
use App\Filament\Resources\UsulanPermohonanPensiunResource\Pages;
use App\Filament\Resources\UsulanPermohonanPensiunResource\RelationManagers;
use App\Models\UsulanPermohonanPensiun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class UsulanPermohonanPensiunResource extends Resource
{
    protected static ?string $model = UsulanPermohonanPensiun::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Usulan';
    protected static ?string $navigationLabel = 'Permohonan Pensiun';
    protected static ?string $modelLabel = 'Permohonan Pensiun';
    protected static ?string $pluralModelLabel = 'Permohonan Pensiun';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nip')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pangkat_golongan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('jabatan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nomor_telepon')
                            ->required()
                            ->tel()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Dokumen Wajib')
                    ->schema([
                        Forms\Components\FileUpload::make('surat_pengantar_unit')
                            ->required()
                            ->directory('pensiun_files/surat-pengantar'),
                        Forms\Components\FileUpload::make('sk_pangkat_terakhir')
                            ->required()
                            ->directory('pensiun_files/sk-pangkat'),
                        Forms\Components\FileUpload::make('sk_cpcns')
                            ->required()
                            ->directory('pensiun_files/sk-cpcns'),
                        Forms\Components\FileUpload::make('sk_pns')
                            ->required()
                            ->directory('pensiun_files/sk-pns'),
                        Forms\Components\FileUpload::make('sk_berkala_terakhir')
                            ->required()
                            ->directory('pensiun_files/sk-berkala'),
                        Forms\Components\FileUpload::make('skp_terakhir')
                            ->required()
                            ->directory('pensiun_files/skp'),
                    ])->columns(2),

                Forms\Components\Section::make('Dokumen Pendukung')
                    ->schema([
                        Forms\Components\FileUpload::make('akte_nikah')
                            ->required()
                            ->directory('pensiun_files/akte-nikah'),
                        Forms\Components\FileUpload::make('ktp_pasangan')
                            ->required()
                            ->directory('pensiun_files/ktp'),
                        Forms\Components\FileUpload::make('karis_karsu')
                            ->required()
                            ->directory('pensiun_files/karis-karsu'),
                        Forms\Components\FileUpload::make('akte_anak')
                            ->directory('pensiun_files/akte-anak'),
                        Forms\Components\FileUpload::make('surat_kuliah')
                            ->directory('pensiun_files/surat-kuliah'),
                        Forms\Components\FileUpload::make('akte_kematian')
                            ->directory('pensiun_files/akte-kematian'),
                    ])->columns(2),

                Forms\Components\Section::make('Data Bank')
                    ->schema([
                        Forms\Components\TextInput::make('nama_bank')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nomor_rekening')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('npwp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('foto')
                            ->required()
                            ->directory('pensiun_files/foto')
                            ->image(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->headerActions([
            ImportAction::make()
                ->importer(PermohonanPensiunImporter::class),
            ExportAction::make()
            
                ->exporter(PermohonanPensiunExporter::class)
        ])
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pangkat_golongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jabatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('surat_pengantar_unit')
                     ->url(fn ($record) => Storage::url($record->surat_pengantar_unit))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsulanPermohonanPensiuns::route('/'),
            'create' => Pages\CreateUsulanPermohonanPensiun::route('/create'),
            'edit' => Pages\EditUsulanPermohonanPensiun::route('/{record}/edit'),
        ];
    }
}
