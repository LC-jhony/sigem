<?php

namespace App\Filament\Resources\DriverMineAssigmentMineResource\Pages;

use App\Filament\Resources\DriverMineAssigmentMineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDriverMineAssigmentMine extends EditRecord
{
    protected static string $resource = DriverMineAssigmentMineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
