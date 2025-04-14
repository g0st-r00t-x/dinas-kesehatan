<?php

namespace App\Filament\Resources\RekapAbsenResource\Pages;

use App\Filament\Resources\RekapAbsenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekapAbsen extends EditRecord
{
    protected static string $resource = RekapAbsenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
