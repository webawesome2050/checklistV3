<?php

namespace App\Filament\Resources\FormHatResource\Pages;

use App\Filament\Resources\FormHatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormHat extends EditRecord
{
    protected static string $resource = FormHatResource::class;

    protected static ?string $title = 'Form HAT: High Area Temperature and Air Pressure Check';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
