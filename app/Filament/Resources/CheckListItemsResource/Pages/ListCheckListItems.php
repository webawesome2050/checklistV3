<?php

namespace App\Filament\Resources\CheckListItemsResource\Pages;

use App\Filament\Resources\CheckListItemsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;


class ListCheckListItems extends ListRecords
{
    protected static string $resource = CheckListItemsResource::class;
    protected static ?string $title = 'Machine Parts';

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
