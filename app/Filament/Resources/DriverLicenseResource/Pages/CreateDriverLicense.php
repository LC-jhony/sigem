<?php

namespace App\Filament\Resources\DriverLicenseResource\Pages;

use App\Filament\Resources\DriverLicenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDriverLicense extends CreateRecord
{
    protected static string $resource = DriverLicenseResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
