<?php

namespace App\Filament\Resources\FormTv1263Resource\Pages;

use App\Filament\Resources\FormTv1263Resource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormTv1263 extends EditRecord
{
    protected static string $resource = FormTv1263Resource::class;

    protected static ?string $title = 'TV: Temperature Daily Verification';
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
        {
            return $this->getResource()::getUrl('index');
        }
}
