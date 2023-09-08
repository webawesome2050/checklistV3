<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use App\Models\CheckList;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Database\Eloquent\Builder; 
use Filament\Resources\Pages\ListRecords\Tab;
use App\Filament\Resources\ChecklistsResource;

class ListChecklists extends ListRecords
{
    protected static string $resource = ChecklistsResource::class;

    protected static ?string $title = 'Hygiene swab and Pre Op Checklist';

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
            'Approved' => Tab::make()->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', true)),
                // ->badge(CheckList::query()->where('is_approved', true)->count()),
            'Pending' => Tab::make()->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', false))
                // ->badge(CheckList::query()->where('is_approved', false)->count()),
        ];
    }




    public function mount(): void
    {
        
        static::authorizeResourceAccess();

        if (
            blank($this->activeTab) &&
            count($tabs = $this->getTabs())
        ) {
            $this->activeTab = array_key_first($tabs);
        }
    }

 


}
