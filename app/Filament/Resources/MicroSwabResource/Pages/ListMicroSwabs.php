<?php

namespace App\Filament\Resources\MicroSwabResource\Pages;

use Filament\Actions;
use Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

use App\Filament\Resources\MicroSwabResource;

class ListMicroSwabs extends ListRecords
{
    protected static string $resource = MicroSwabResource::class;

    protected static ?string $title = 'Micro SPC Swab Check';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Micro SPC Swab Check')
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
