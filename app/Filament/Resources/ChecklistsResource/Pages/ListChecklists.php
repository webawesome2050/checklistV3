<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use App\Filament\Resources\ChecklistsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder; 

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
            'Approved' => Tab::make()->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', true)),
            'Pending' => Tab::make()->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', false)),
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
