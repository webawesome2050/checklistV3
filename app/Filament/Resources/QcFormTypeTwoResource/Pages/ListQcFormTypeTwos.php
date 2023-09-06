<?php

namespace App\Filament\Resources\QcFormTypeTwoResource\Pages;

use App\Filament\Resources\QcFormTypeTwoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder; 

class ListQcFormTypeTwos extends ListRecords
{
    protected static string $resource = QcFormTypeTwoResource::class;

    protected static ?string $title = 'GMP Checklist';

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
