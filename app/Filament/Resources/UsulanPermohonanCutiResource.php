<?php

namespace App\Filament\Resources;

use App\Filament\Imports\PermohonanCutiImporter;
use App\Filament\Resources\UsulanPermohonanCutiResource\Pages;
use App\Models\Pegawai;
use Filament\Tables\Actions\ImportAction;
use App\Models\PermohonanCuti;
use App\Models\JenisCuti;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;

class UsulanPermohonanCutiResource extends Resource
{
    protected static ?string $model = PermohonanCuti::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?string $label = 'Permohonan Cuti';
    protected static ?string $pluralLabel = 'Permohonan Cuti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Permohonan Cuti')
                    ->schema([
                    Forms\Components\Select::make('pegawai_id')
                        ->label('Pegawai')
                        ->relationship('pegawai', 'nama')
                        ->searchable()
                        ->required()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('nip')
                                ->required()
                                ->unique(),
                            Forms\Components\TextInput::make('nama')
                                ->required(),
                            Forms\Components\Select::make('unit_kerja_id')
                                ->relationship('unitKerja', 'nama')
                                ->required(),
                            Forms\Components\TextInput::make('jabatan'),
                            Forms\Components\Select::make('status_kepegawaian')
                                ->options([
                                    'PNS' => 'PNS',
                                    'PPPK' => 'PPPK',
                                    'Honorer' => 'Honorer'
                                ])
                        ]),
                        
                        Forms\Components\Select::make('jenis_cuti_id')
                            ->label('Jenis Cuti')
                            ->relationship('jenisCuti', 'nama')
                            ->required(),
                        
                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->required(),
                        
                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->required(),
                        
                        Forms\Components\Textarea::make('alasan')
                            ->maxLength(500),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'diajukan' => 'Diajukan',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak'
                            ])
                            ->default('diajukan')
                            ->required(),

                        Repeater::make('dokumenCuti')
                            ->relationship('dokumenCuti')
                            ->schema([
                                Forms\Components\TextInput::make('jenis_dokumen')
                                    ->required(),
                                Forms\Components\FileUpload::make('path_file')
                                    ->directory('dokumen-cuti')
                                    ->preserveFilenames()
                                    ->required(),
                            ])
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
                ->headerActions([
            ImportAction::make()
                ->importer(PermohonanCutiImporter::class)
        ])
            ->columns([
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Nama Pegawai')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('jenisCuti.nama')
                    ->label('Jenis Cuti'),
                
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date(),
                
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diajukan' => 'warning',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_cuti_id')
                    ->relationship('jenisCuti', 'nama')
                    ->label('Jenis Cuti'),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'disetujui' => 'Disetujui', 
                        'ditolak' => 'Ditolak'
                    ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsulanPermohonanCutis::route('/'),
            'create' => Pages\CreateUsulanPermohonanCuti::route('/create'),
            'edit' => Pages\EditUsulanPermohonanCuti::route('/{record}/edit'),
        ];
    }
}