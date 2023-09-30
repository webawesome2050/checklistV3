<?php

namespace App\Filament\Resources\ATPFormResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Builder; 
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ATPFormResource;

class ListATPForms extends ListRecords
{
    protected static string $resource = ATPFormResource::class;

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
