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
use Illuminate\Database\Eloquent\Builder;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?string $label = 'Pegawai';
    protected static ?string $pluralLabel = 'Pegawai';
    
    protected static ?string $path = 'pegawai';

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
                        'Aktif' => 'Aktif',
                        'Non-Aktif' => 'Non-Aktif',
                    ])
                    ->required()
                    ->label('Status Kepegawaian'),
                DatePicker::make('tanggal_lahir')
                    ->required()
                    ->label('Tanggal Lahir'),
                Radio::make('jenis_kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
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
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('unitKerja', function ($query) use ($search) {
                            $query->where('nama', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('jabatan')
                    ->label('Jabatan'),
                TextColumn::make('pangkat_golongan')
                    ->label('Pangkat/Golongan'),
                TextColumn::make('email')
            ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('no_telepon')
            ->toggleable(isToggledHiddenByDefault: true)
                    ->label('No. Telepon'),
                TextColumn::make('status_kepegawaian')
            ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Status Kepegawaian'),
                TextColumn::make('tanggal_lahir')
            ->toggleable(isToggledHiddenByDefault: true)
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
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama', 'nip'];
    }
}