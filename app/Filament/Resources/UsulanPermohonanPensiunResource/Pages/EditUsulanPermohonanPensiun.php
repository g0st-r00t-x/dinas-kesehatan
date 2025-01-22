<?php

namespace App\Filament\Resources\UsulanPermohonanPensiunResource\Pages;

use App\Filament\Resources\UsulanPermohonanPensiunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsulanPermohonanPensiun extends EditRecord
{
    protected static string $resource = UsulanPermohonanPensiunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
