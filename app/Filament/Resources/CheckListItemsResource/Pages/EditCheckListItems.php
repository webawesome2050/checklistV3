<?php

namespace App\Filament\Resources\CheckListItemsResource\Pages;

use App\Filament\Resources\CheckListItemsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCheckListItems extends EditRecord
{
    protected static string $resource = CheckListItemsResource::class;
    protected static ?string $title = 'Machine Parts';
    protected static ?string $breadcrumb = 'Machine Parts';
    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
