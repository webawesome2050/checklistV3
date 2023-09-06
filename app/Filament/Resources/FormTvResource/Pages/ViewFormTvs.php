<?php

namespace App\Filament\Resources\FormTvResource\Pages;

use App\Filament\Resources\FormTvResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;


use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;



class ViewFormTvs extends ViewRecord
{
    protected static string $resource = FormTvResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Actions\Action::make('Approve Form')
                ->modalHeading('Approve this Checklist Form')
                ->modalSubmitActionLabel('Approve')
                ->modalIcon('heroicon-o-bolt')
                ->form([ 
                        Toggle::make('is_approved')->label('Approve'),
                    TextArea::make('comments')
                        ->rows(10) 
                ])
                ->action(function (array $data): void { 
                    $this->record->comments = $data['comments'];
                    $this->record->is_approved = true; // $data['status']; 
                    $this->record->save();
                })
                // ->slideOver()
                // ->visible(auth()->user()->))
        ];
    } 
}
