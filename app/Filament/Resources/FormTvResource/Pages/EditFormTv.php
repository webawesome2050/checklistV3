<?php

namespace App\Filament\Resources\FormTvResource\Pages;

use App\Filament\Resources\FormTvResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormTv extends EditRecord
{
    protected static string $resource = FormTvResource::class;

    protected static ?string $title = 'TV: Temperature Daily Verification';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
