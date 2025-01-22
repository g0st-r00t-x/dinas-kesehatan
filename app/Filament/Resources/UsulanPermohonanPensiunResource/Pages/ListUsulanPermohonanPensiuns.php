<?php

namespace App\Filament\Resources\UsulanPermohonanPensiunResource\Pages;

use App\Filament\Resources\UsulanPermohonanPensiunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsulanPermohonanPensiuns extends ListRecords
{
    protected static string $resource = UsulanPermohonanPensiunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
