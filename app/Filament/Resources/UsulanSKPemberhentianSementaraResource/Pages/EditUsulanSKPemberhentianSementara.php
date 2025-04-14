<?php

namespace App\Filament\Resources\UsulanSKPemberhentianSementaraResource\Pages;

use App\Filament\Resources\UsulanSKPemberhentianSementaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsulanSKPemberhentianSementara extends EditRecord
{
    protected static string $resource = UsulanSKPemberhentianSementaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
