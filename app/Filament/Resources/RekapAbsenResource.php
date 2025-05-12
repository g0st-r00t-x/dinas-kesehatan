<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekapAbsenResource\Pages;
use App\Filament\Resources\RekapAbsenResource\RelationManagers;
use App\Models\RekapAbsen;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekapAbsenResource extends Resource
{
    protected static ?string $model = RekapAbsen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?string $navigationLabel = 'Rekap Absen';
    protected static ?string $modelLabel = 'Rekap Absen';
    protected static ?string $label = 'Rekap Absen';
    protected static ?string $pluralLabel = 'Rekap Absen';
    protected static ?string $path = 'rekap-absen';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Rekap Absensi')
                ->schema([
                    Forms\Components\Select::make('bulan')
                        ->label('Bulan')
                        ->options([
                            'Januari' => 'Januari',
                            'Februari' => 'Februari',
                            'Maret' => 'Maret',
                            'April' => 'April',
                            'Mei' => 'Mei',
                            'Juni' => 'Juni',
                            'Juli' => 'Juli',
                            'Agustus' => 'Agustus',
                            'September' => 'September',
                            'Oktober' => 'Oktober',
                            'November' => 'November',
                            'Desember' => 'Desember',
                        ])
                        ->required(),

                    Forms\Components\Select::make('jenis_pegawai')
                    ->label('Jenis Pegawai')
                    ->options([
                        'PNS' => 'PNS',
                        'PPPK' => 'PPPK',
                    ])
                        ->required(),

                    Forms\Components\FileUpload::make('upload_excel')
                    ->label('Upload File Excel')
                    ->directory('rekap-absen')
                    ->acceptedFileTypes([
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ])
                        ->maxSize(5120) // 5MB
                        ->required()
                        ->downloadable()
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bulan')
                    ->label('Bulan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_pegawai')
                ->label('Jenis Pegawai')
                ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Upload')
                ->dateTime('d F Y, H:i')
                ->sortable(),

                Tables\Columns\TextColumn::make('upload_excel')
                ->label('File Excel')
                ->searchable(),
            ])
            ->filters([
                SelectFilter::make('bulan')
                    ->options([
                        'Januari' => 'Januari',
                        'Februari' => 'Februari',
                        'Maret' => 'Maret',
                        'April' => 'April',
                        'Mei' => 'Mei',
                        'Juni' => 'Juni',
                        'Juli' => 'Juli',
                        'Agustus' => 'Agustus',
                        'September' => 'September',
                        'Oktober' => 'Oktober',
                        'November' => 'November',
                        'Desember' => 'Desember',
                    ]),
                SelectFilter::make('jenis_pegawai')
                ->options([
                    'PNS' => 'PNS',
                    'PPPK' => 'PPPK',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('download')
                ->label('Download')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn(RekapAbsen $record) => asset('storage/' . $record->upload_excel))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListRekapAbsens::route('/'),
            'create' => Pages\CreateRekapAbsen::route('/create'),
            'edit' => Pages\EditRekapAbsen::route('/{record}/edit'),
        ];
    }
}
