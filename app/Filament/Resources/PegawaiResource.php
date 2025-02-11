<?php


namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use App\Models\PermohonanCuti;
use App\Models\DokumenCuti;
use App\Models\JenisCuti;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Kepegawaian';

    public static function form(Form $form): Form
    {
                return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->label('Nama'),
                TextInput::make('nip')
                    ->required()
                    ->label('NIP'),
                Select::make('unit_kerja_id')
                    ->relationship('unitKerja', 'nama')
                    ->required()
                    ->label('Unit Kerja'),
                TextInput::make('pangkat_golongan')
                    ->label('Pangkat/Golongan'),
                TextInput::make('jabatan')
                    ->required()
                    ->label('Jabatan'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),
                TextInput::make('no_telepon')
                    ->tel()
                    ->required()
                    ->label('No. Telepon'),
                Select::make('status_kepegawaian')
                    ->options([
                        'aktif' => 'Aktif',
                        'non-aktif' => 'Non-Aktif',
                    ])
                    ->required()
                    ->label('Status Kepegawaian'),
                DatePicker::make('tanggal_lahir')
                    ->required()
                    ->label('Tanggal Lahir'),
                Radio::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required()
                    ->label('Jenis Kelamin'),
                Textarea::make('alamat')
                    ->label('Alamat'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('unitKerja.nama')
                    ->label('Unit Kerja')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jabatan')
                    ->label('Jabatan'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('no_telepon')
                    ->label('No. Telepon'),
                TextColumn::make('status_kepegawaian')
                    ->label('Status Kepegawaian'),
                TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->date(),
                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unit_kerja_id')
                    ->relationship('unitKerja', 'nama')
                    ->label('Unit Kerja'),
                Tables\Filters\SelectFilter::make('status_kepegawaian')
                    ->options([
                        'aktif' => 'Aktif',
                        'non-aktif' => 'Non-Aktif',
                    ])
                    ->label('Status Kepegawaian'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unit_kerja_id')
                    ->relationship('unitKerja', 'nama')
                    ->label('Unit Kerja'),
                Tables\Filters\SelectFilter::make('status_kepegawaian')
                    ->options([
                        'aktif' => 'Aktif',
                        'non-aktif' => 'Non-Aktif',
                    ])
                    ->label('Status Kepegawaian'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unit_kerja_id')
                    ->relationship('unitKerja', 'nama')
                    ->label('Unit Kerja'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Action::make('Ajukan Cuti')
                    ->icon('heroicon-o-document-plus')
                    ->url(fn (Pegawai $record) => static::getUrl('edit', ['record' => $record]) . '?activeTab=permohonan-cuti')
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}