<?php

namespace App\Filament\Resources\UsulanSkBerkalaResource\Pages;

use App\Filament\Resources\UsulanSkBerkalaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsulanSkBerkala extends EditRecord
{
    protected static string $resource = UsulanSkBerkalaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
