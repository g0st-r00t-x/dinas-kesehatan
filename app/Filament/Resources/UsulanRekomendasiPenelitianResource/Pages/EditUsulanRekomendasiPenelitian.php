<?php

namespace App\Filament\Resources\UsulanRekomendasiPenelitianResource\Pages;

use App\Filament\Resources\UsulanRekomendasiPenelitianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsulanRekomendasiPenelitian extends EditRecord
{
    protected static string $resource = UsulanRekomendasiPenelitianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
