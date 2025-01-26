<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanSKBerkalaResource\Pages;
use App\Filament\Resources\UsulanSKBerkalaResource\RelationManagers;
use App\Models\UsulanSKBerkala;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsulanSKBerkalaResource extends Resource
{
    protected static ?string $model = UsulanSKBerkala::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

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
                Forms\Components\TextInput::make('jabatan')
                    ->required()
                    ->label('Jabatan'),
                Forms\Components\DatePicker::make('tmt_sk_pangkat_terakhir')
                    ->required()
                    ->label('TMT SK Pangkat Terakhir'),
                Forms\Components\DatePicker::make('tanggal_penerbitan_pangkat_terakhir')
                    ->required()
                    ->label('Tanggal Penerbitan Pangkat Terakhir'),
                Forms\Components\DatePicker::make('tmt_sk_berkala_terakhir')
                    ->required()
                    ->label('TMT SK Berkala Terakhir'),
                Forms\Components\DatePicker::make('tanggal_penerbitan_sk_berkala_terakhir')
                    ->required()
                    ->label('Tanggal Penerbitan SK Berkala Terakhir'),
                Forms\Components\FileUpload::make('upload_sk_pangkat_terakhir')
                    ->required()
                    ->label('Upload SK Pangkat Terakhir'),
                Forms\Components\FileUpload::make('upload_sk_berkala_terakhir')
                    ->required()
                    ->label('Upload SK Berkala Terakhir'),
                Forms\Components\FileUpload::make('upload_surat_pengantar')
                    ->required()
                    ->label('Upload Surat Pengantar'),
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
                    ->label('Unit Kerja')
                    ->sortable(),
                TextColumn::make('pangkat_golongan')
                    ->label('Pangkat/Golongan'),
                TextColumn::make('jabatan')
                    ->label('Jabatan'),
                TextColumn::make('tmt_sk_pangkat_terakhir')
                    ->label('TMT SK Pangkat Terakhir')
                    ->date(),
                TextColumn::make('tanggal_penerbitan_pangkat_terakhir')
                    ->label('Tanggal Penerbitan Pangkat Terakhir')
                    ->date(),
                TextColumn::make('tmt_sk_berkala_terakhir')
                    ->label('TMT SK Berkala Terakhir')
                    ->date(),
                TextColumn::make('tanggal_penerbitan_sk_berkala_terakhir')
                    ->label('Tanggal Penerbitan SK Berkala Terakhir')
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('pangkat_golongan')
                    ->label('Filter Pangkat/Golongan')
                    ->options(function () {
                        return UsulanSkBerkala::query()
                            ->distinct()
                            ->pluck('pangkat_golongan', 'pangkat_golongan')
                            ->toArray();
                    }),
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
            'index' => Pages\ListUsulanSKBerkalas::route('/'),
            'create' => Pages\CreateUsulanSKBerkala::route('/create'),
            'edit' => Pages\EditUsulanSKBerkala::route('/{record}/edit'),
        ];
    }
}
