<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsulanRevisiSkPangkatResource\Pages;
use App\Filament\Resources\UsulanRevisiSkPangkatResource\RelationManagers;
use App\Models\UsulanRevisiSkPangkat;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class UsulanRevisiSkPangkatResource extends Resource
{
    protected static ?string $model = UsulanRevisiSkPangkat::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Usulan Revisi SK Pangkat';
    
    protected static ?string $modelLabel = 'Manajemen Data';

    protected static ?string $navigationGroup = 'Manajemen Data';

    protected static ?string $label = 'Usulan Revisi SK Pangkat';

    protected static ?string $pluralLabel = 'Usulan Revisi SK Pangkat';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pegawai')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nip')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('pangkat_golongan')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('no_wa')
                            ->required()
                            ->tel()
                            ->maxLength(20),
                    ])->columns(2),

                    Section::make('Detail Revisi')
                    ->schema([
                        Forms\Components\Textarea::make('alasan_revisi_sk')
                            ->required()
                            ->rows(3),
                        Forms\Components\Textarea::make('kesalahan_tertulis_sk')
                            ->required()
                            ->rows(3),
                    ])->columns(1),

                Section::make('Upload Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('upload_sk_salah')
                            ->required()
                            ->directory('sk_salah')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120), // 5MB
                        
                        Forms\Components\FileUpload::make('upload_data_dukung')
                            ->required()
                            ->directory('data_dukung')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120),
                            
                        Forms\Components\FileUpload::make('surat_pengantar')
                            ->required()
                            ->directory('surat_pengantar')
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('nip')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('pangkat_golongan')
                    ->sortable(),
                TextColumn::make('upload_sk_salah')
                    ->label('SK Salah')
                    ->url(fn ($record) => Storage::url($record->sk_jabatan))
                    ->openUrlInNewTab(),
                               
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Tanggal Pengajuan'),
                    
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsulanRevisiSkPangkats::route('/'),
            'create' => Pages\CreateUsulanRevisiSkPangkat::route('/create'),
            'edit' => Pages\EditUsulanRevisiSkPangkat::route('/{record}/edit'),
        ];
    }
}
