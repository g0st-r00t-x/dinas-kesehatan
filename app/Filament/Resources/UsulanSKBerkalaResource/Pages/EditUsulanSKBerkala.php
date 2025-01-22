<?php

namespace App\Filament\Resources\UsulanSKBerkalaResource\Pages;

use App\Filament\Resources\UsulanSKBerkalaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsulanSKBerkala extends EditRecord
{
    protected static string $resource = UsulanSKBerkalaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
