<?php

namespace App\Filament\Resources\UsulanPermohonanCutiResource\Pages;

use App\Filament\Resources\UsulanPermohonanCutiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsulanPermohonanCuti extends EditRecord
{
    protected static string $resource = UsulanPermohonanCutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
