<?php

namespace App\Filament\Resources\ATPFormS1Resource\Pages;

use App\Filament\Resources\ATPFormS1Resource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Database\Eloquent\Builder; 
use Filament\Resources\Pages\ListRecords\Tab;


class ListATPFormS1S extends ListRecords
{
    protected static string $resource = ATPFormS1Resource::class;

    protected static ?string $title = 'ATP check RLU';

    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create ATP check'),
        ];
    }


    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->icon('heroicon-m-check-badge'),
                    // ->badge(CheckList::query()->count()),
            'Approved' => Tab::make()->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', true)),
                // ->badge(CheckList::query()->where('is_approved', true)->count()),
            'Pending' => Tab::make()->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', false))
                // ->badge(CheckList::query()->where('is_approved', false)->count()),
        ];
    }
}
