<?php

namespace App\Filament\Resources\PengajuanAJJResource\Pages;

use App\Filament\Resources\PengajuanAJJResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanAJJ extends EditRecord
{
    protected static string $resource = PengajuanAJJResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
