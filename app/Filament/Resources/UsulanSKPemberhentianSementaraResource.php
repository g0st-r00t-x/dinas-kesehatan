<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanSKPemberhentianSementaraResource\Pages;
use App\Filament\Resources\UsulanSKPemberhentianSementaraResource\RelationManagers;
use App\Models\UsulanSKPemberhentianSementara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsulanSKPemberhentianSementaraResource extends Resource
{
    protected static ?string $model = UsulanSKPemberhentianSementara::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Usulan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->label('Nama'),
                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->label('NIP')
                    ->unique(),
                Forms\Components\TextInput::make('unit_kerja')
                    ->required()
                    ->label('Unit Kerja'),
                Forms\Components\TextInput::make('pangkat_golongan')
                    ->required()
                    ->label('Pangkat/Golongan'),
                Forms\Components\DatePicker::make('tmt_sk_pangkat_terakhir')
                    ->required()
                    ->label('TMT SK Pangkat Terakhir'),
                Forms\Components\DatePicker::make('tmt_sk_jabatan_terakhir')
                    ->required()
                    ->label('TMT SK Jabatan Terakhir'),
                Forms\Components\FileUpload::make('file_sk_jabatan_fungsional_terakhir')
                    ->required()
                    ->label('File SK Jabatan Fungsional Terakhir'),
                Forms\Components\Select::make('alasan')
                    ->required()
                    ->label('Alasan')
                    ->options([
                        'tidak_melanjutkan_pendidikan' => 'Tidak Melanjutkan Pendidikan',
                        'melanjutkan_pendidikan' => 'Melanjutkan Pendidikan',
                    ]),
                Forms\Components\FileUpload::make('file_pak')
                    ->required()
                    ->label('File PAK'),
                Forms\Components\FileUpload::make('surat_pengantar')
                    ->required()
                    ->label('Surat Pengantar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit_kerja')
                    ->label('Unit Kerja'),
                TextColumn::make('pangkat_golongan')
                    ->label('Pangkat/Golongan'),
                TextColumn::make('tmt_sk_pangkat_terakhir')
                    ->label('TMT SK Pangkat Terakhir')
                    ->date(),
                TextColumn::make('tmt_sk_jabatan_terakhir')
                    ->label('TMT SK Jabatan Terakhir')
                    ->date(),
                TextColumn::make('alasan')
                    ->label('Alasan'),
            ])
            ->filters([
                SelectFilter::make('alasan')
                    ->label('Filter Alasan')
                    ->options([
                        'tidak_melanjutkan_pendidikan' => 'Tidak Melanjutkan Pendidikan',
                        'melanjutkan_pendidikan' => 'Melanjutkan Pendidikan',
                    ]),
            ])
            ->defaultSort('nama');
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
            'index' => Pages\ListUsulanSKPemberhentianSementaras::route('/'),
            'create' => Pages\CreateUsulanSKPemberhentianSementara::route('/create'),
            'edit' => Pages\EditUsulanSKPemberhentianSementara::route('/{record}/edit'),
        ];
    }
}
