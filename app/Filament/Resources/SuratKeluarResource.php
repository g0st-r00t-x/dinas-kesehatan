<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratKeluarResource\Pages;
use App\Http\Controllers\PengajuanSuratController;
use App\Models\JenisSurat;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class SuratKeluarResource extends Resource
{
    protected static ?string $model = SuratKeluar::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'Surat Keluar';
    protected static ?string $modelLabel = 'Surat Keluar';
    protected static ?string $navigationGroup = 'Arsip Surat';
    protected static ?string $pluralLabel = 'Surat Keluar';
    protected static ?string $path = 'surat-keluar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Data Surat')
                        ->columnSpan(['lg' => 1])
                        ->schema([
                            Forms\Components\Select::make('id_pegawai')
                            ->relationship('pegawai', 'nama')
                                ->required()
                                ->afterStateUpdated(fn($state, Forms\Set $set) => self::updatePegawaiData($state, $set)),

                            Forms\Components\Select::make('id_jenis_surat')
                            ->relationship('jenisSurat', 'nama')
                                ->required()
                                ->live(),

                            Forms\Components\TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->required()
                                ->maxLength(255)
                                ->suffixAction(
                                    Action::make('generateSk')
                                    ->label('Generate SK')
                                    ->icon('heroicon-o-arrow-path')
                                    ->action(function (Forms\Get $get, Forms\Set $set) {
                                        $jenisSuratId = $get('id_jenis_surat');

                                        if (!$jenisSuratId) {
                                            Notification::make()
                                                ->warning()
                                                ->title('Pilih Jenis Surat terlebih dahulu')
                                                ->send();
                                            return;
                                        }

                                        $jenisSurat = JenisSurat::find($jenisSuratId);
                                        if (!$jenisSurat) {
                                            return;
                                        }

                                        self::generateSkNumber($set, $jenisSurat->kode);
                                    })
                                ),

                            Forms\Components\Textarea::make('perihal')
                            ->maxLength(65535),

                            Forms\Components\TextInput::make('tujuan_surat')
                            ->maxLength(200),

                            Forms\Components\DatePicker::make('tanggal_surat')
                            ->required(),

                            Forms\Components\FileUpload::make('file_surat')
                            ->directory('surat-keluar')
                            ->preserveFilenames()
                                ->required()
                                ->label("Template Surat")
                                ->visible(function (Forms\Get $get) {
                                    $jenisSuratId = $get('id_jenis_surat');
                                    $jenisSurat = JenisSurat::find($jenisSuratId);
                                    return $jenisSurat?->nama === 'Lainnya';
                                })
                                ->maxSize(5120),
                        ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenisSurat.nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nomor_surat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('perihal')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('pengajuanSurat.status_pengajuan')
                    ->label('Status Pengajuan')
            ->badge()
                ->color(fn(string $state): string => match ($state) {
                    'Diajukan' => 'warning',
                    'Diterima' => 'success',
                    'Ditolak' => 'danger',
                'Belum Diajukan' => 'info',
                }),

                Tables\Columns\TextColumn::make('file_surat')
                    ->label('Upload Berkas')
                    ->icon('heroicon-o-eye')
                    ->formatStateUsing(fn($state) => $state ? 'Lihat File' : '-')
                    ->url(fn($record) => self::getFileUrl($record))
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('tujuan_surat')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                TableAction::make('ajukan_surat')
                    ->label('Ajukan Surat')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(fn($record) => $record->pengajuanSurat?->status_pengajuan === 'Ditolak')
                    ->action(fn(SuratKeluar $record) => self::handleSuratSubmission($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'edit' => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }

    // Private helper methods
    private static function updatePegawaiData($state, Forms\Set $set): void
    {
        if ($state) {
            $pegawai = Pegawai::find($state);
            if ($pegawai) {
                $set('pegawai_nama', $pegawai->nama);
                $set('pegawai_jabatan', $pegawai->jabatan);
            }
        }
    }

    private static function generateSkNumber(Forms\Set $set, $kode): void
    {
        $lastSk = SuratKeluar::orderBy('nomor_surat', 'desc')->first();

        $newSkNumber = 1;
        if ($lastSk && preg_match('/800\/(\d{2,})\/'.$kode.'\/\d{4}/', $lastSk->nomor_surat, $matches)) {
            $newSkNumber = intval($matches[1]) + 1;
        }

        $formattedSk = sprintf("800/%02d/%s/%s", $newSkNumber, $kode, date('Y'));
        $set('nomor_sk', $formattedSk);
    }

    private static function getFileUrl($record): ?string
    {
        if (!$record->file_surat) {
            return null;
        }

        return str_starts_with($record->file_surat, 'http')
            ? $record->file_surat
            : Storage::url($record->file_surat);
    }

    private static function handleSuratSubmission(SuratKeluar $record): void
    {
        $controller = new PengajuanSuratController();
        $controller->handle($record);
    }
}
