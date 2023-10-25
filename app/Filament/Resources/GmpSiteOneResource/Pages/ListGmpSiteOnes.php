<?php

namespace App\Filament\Resources\GmpSiteOneResource\Pages;

use App\Filament\Resources\GmpSiteOneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder; 

class ListGmpSiteOnes extends ListRecords
{
    protected static string $resource = GmpSiteOneResource::class;

    protected static ?string $title = 'GMP';


    
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create GMP Checklist'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make()->icon('heroicon-m-check-badge'),
            'Approved' => Tab::make()->icon('heroicon-m-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', true)),
            // 'Pending' => Tab::make()->icon('heroicon-m-x-circle')
            //     ->modifyQueryUsing(fn (Builder $query) => $query->where('is_approved', false)),
        ];
    }
}
