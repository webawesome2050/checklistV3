<?php

namespace App\Filament\Resources\MicroSwabSiteOneResource\Pages;

use App\Filament\Resources\MicroSwabSiteOneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMicroSwabSiteOne extends EditRecord
{
    protected static string $resource = MicroSwabSiteOneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
