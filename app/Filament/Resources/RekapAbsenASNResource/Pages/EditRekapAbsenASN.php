<?php

namespace App\Filament\Resources\RekapAbsenASNResource\Pages;

use App\Filament\Resources\RekapAbsenASNResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRekapAbsenASN extends EditRecord
{
    protected static string $resource = RekapAbsenASNResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
