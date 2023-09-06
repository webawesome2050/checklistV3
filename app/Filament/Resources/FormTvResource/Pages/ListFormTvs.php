<?php

namespace App\Filament\Resources\FormTvResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder; 
use App\Filament\Resources\FormTvResource;

class ListFormTvs extends ListRecords
{
    protected static string $resource = FormTvResource::class;
    
    protected static ?string $title = 'TV: Chiller and Freezer Temperature Daily Verification';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        return [
            'All' => Tab::make()->icon('heroicon-m-check-badge'),
            'Approved' => Tab::make()->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', true)),
            'Pending' => Tab::make()->icon('heroicon-m-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', false)),
        ];
    }

    
}
