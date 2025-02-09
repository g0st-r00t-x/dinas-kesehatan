<?php

namespace App\Filament\Resources\UsulanPermohonanCutiResource\Pages;

use App\Filament\Resources\UsulanPermohonanCutiResource;
use App\Models\JenisSurat;
use App\Models\Pegawai;
use App\Models\PengajuanSurat;
use App\Models\PermohonanCuti;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUsulanPermohonanCuti extends CreateRecord
{
    protected static string $resource = UsulanPermohonanCutiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
