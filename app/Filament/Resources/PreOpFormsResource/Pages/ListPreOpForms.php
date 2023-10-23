<?php

namespace App\Filament\Resources\PreOpFormsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\PreOpFormsResource;
use Illuminate\Database\Eloquent\Builder; 

class ListPreOpForms extends ListRecords
{
    protected static string $resource = PreOpFormsResource::class;

    protected static ?string $title = 'Pre-Op forms';
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Pre-Op forms'),
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
