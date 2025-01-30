<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapAbsenASNResource\Pages;
use App\Filament\Resources\RekapAbsenASNResource\RelationManagers;
use App\Models\RekapAbsenASN;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class RekapAbsenASNResource extends Resource
{
    protected static ?string $model = RekapAbsenASN::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Manajemen Data';

    protected static ?string $label = 'Rekap Absen ASN';

    protected static ?string $pluralLabel = 'Rekap Absen ASN';

    protected static ?string $path = 'rekap-absen-asn';

    public static function form(Form $form): Form
    {
       return $form
            ->schema([
                Forms\Components\TextInput::make('bulan')
                    ->required()
                    ->label('Bulan'),
                Forms\Components\FileUpload::make('upload_excel')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        return now()->timestamp . '_' . $file->getClientOriginalName();
                    })
                    ->required()
                    ->label('Upload Excel'),
            ]);
    }

    public static function table(Table $table): Table
    {
         return $table
            ->columns([
                TextColumn::make('bulan')
                    ->label('Bulan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('upload_excel')
                    ->label('Upload Excel')
                    ->url(fn ($record) => asset('storage/' . $record->upload_excel))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('bulan');
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
            'index' => Pages\ListRekapAbsenASNS::route('/'),
            'create' => Pages\CreateRekapAbsenASN::route('/create'),
            'edit' => Pages\EditRekapAbsenASN::route('/{record}/edit'),
        ];
    }
}
