<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataSerkomResource\Pages;
use App\Filament\Resources\DataSerkomResource\RelationManagers;
use App\Models\DataSerkom;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataSerkomResource extends Resource
{
    protected static ?string $model = DataSerkom::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'view_own',
            'download_file',
            'create',
            'update',
            'delete',
            'delete_any',
            'kirim_notif'
        ];
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';


    protected static ?string $navigationGroup = 'Manajemen Data';

    protected static ?string $label = 'Data Serkom';

    protected static ?string $pluralLabel = 'Data Serkom';

    protected static ?string $path = 'data-serkom';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pegawai')
                    ->schema([
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nama_pegawai')
                            ->label('Nama Pegawai')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Detail Sertifikasi')
                    ->schema([
                        Forms\Components\TextInput::make('nama_sertifikasi')
                            ->label('Nama Sertifikasi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nomor_sertifikat')
                            ->label('Nomor Sertifikat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('lembaga_penerbit')
                            ->label('Lembaga Penerbit')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tanggal_terbit')
                            ->label('Tanggal Terbit')
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_kadaluarsa')
                            ->label('Tanggal Kadaluarsa')
                            ->required(),
                        Forms\Components\Select::make('status_validasi')
                            ->label('Status Validasi')
                            ->options([
                                'pending' => 'Pending',
                                'valid' => 'Valid',
                                'invalid' => 'Invalid',
                            ])
                            ->required(),
                        Forms\Components\FileUpload::make('file_sertifikat')
                            ->label('File Sertifikat')
                            ->required()
                            ->directory('sertifikat')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120) // 5MB
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_sertifikasi')
                    ->label('Nama Sertifikasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nomor_sertifikat')
                    ->label('Nomor Sertifikat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lembaga_penerbit')
                    ->label('Lembaga Penerbit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_terbit')
                    ->label('Tanggal Terbit')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_kadaluarsa')
                    ->label('Tanggal Kadaluarsa')
                    ->date()
                    ->sortable(),
            ])
            ->filters([])
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
            'index' => Pages\ListDataSerkoms::route('/'),
            'create' => Pages\CreateDataSerkom::route('/create'),
            'edit' => Pages\EditDataSerkom::route('/{record}/edit'),
        ];
    }
}
