<?php

namespace App\Filament\Resources\CheckListItemsResource\Pages;

use App\Filament\Resources\CheckListItemsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder; 

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


    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->icon('heroicon-m-check-badge'),
                    // ->badge(CheckList::query()->count()),
            'Site 34' => Tab::make()->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('site_id', 1)),
                // ->badge(CheckList::query()->where('is_approved', true)->count()),
            'Site 1263' => Tab::make()->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('site_id', 2))
                // ->badge(CheckList::query()->where('is_approved', false)->count()),
        ];
    }

}
