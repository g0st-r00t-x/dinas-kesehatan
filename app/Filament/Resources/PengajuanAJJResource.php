<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanAJJResource\Pages;
use App\Models\PengajuanAJJ;
use App\Models\UsulanPenerbitanAjj;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PengajuanAJJResource extends Resource
{
    protected static ?string $model = UsulanPenerbitanAjj::class;


    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $label = 'Usulan Penerbitan AJJ';

    protected static ?string $pluralLabel = 'Usulan Penerbitan AJJ';

    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama')
                    ->required(),

                TextInput::make('nip')
                    ->label('NIP')
                    ->required(),

                TextInput::make('unit_kerja')
                    ->label('Unit Kerja')
                    ->required(),

                DatePicker::make('tmt_pemberian_tunjangan')
                    ->label('TMT Pemberian Tunjangan')
                    ->required(),

                Select::make('SK Jabatan')
                ->options([
                    'ada' => 'Ada',
                    'tidak ada' => 'Tidak Ada',
                ]),

                FileUpload::make('upload_berkas')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return now()->timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->label('Upload Berkas')
                    ->helperText(str('**Jika Tidak memiliki SK Jabatan Fungsional Maka Upload SK Pemberian Tunjangan**')->inlineMarkdown()->toHtmlString())
                    ->directory('upload_berkas')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(1024)
                    ->required(),
                    

                FileUpload::make('surat_pengantar_unit_kerja')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return now()->timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->label('Surat Pengantar Unit Kerja')
                    ->directory('surat_pengantar_unit_kerja')
                    ->disk('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(512)
                    ->required(),

                

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('nip')->label('NIP'),
                TextColumn::make('unit_kerja')->label('Unit Kerja'),
                TextColumn::make('tmt_pemberian_tunjangan')->label('TMT Pemberian Tunjangan'),
                TextColumn::make('sk_jabatan')
                    ->label('SK Jabatan')
                    ->url(fn ($record) => Storage::url($record->sk_jabatan))
                    ->openUrlInNewTab(),

                TextColumn::make('upload_berkas')
                    ->label('Upload Berkas')
                    ->url(fn ($record) => Storage::url($record->upload_berkas))
                    ->openUrlInNewTab(),

                TextColumn::make('surat_pengantar_unit_kerja')
                    ->label('Surat Pengantar Unit Kerja')
                    ->url(fn ($record) => Storage::url($record->surat_pengantar_unit_kerja))
                    ->openUrlInNewTab(),
            ])
            ->filters([
                // Add filters here if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPengajuanAJJS::route('/'),
            'create' => Pages\CreatePengajuanAJJ::route('/create'),
            'edit' => Pages\EditPengajuanAJJ::route('/{record}/edit'),
        ];
    }
}
