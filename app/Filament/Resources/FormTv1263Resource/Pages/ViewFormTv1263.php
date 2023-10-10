<?php

namespace App\Filament\Resources\FormTv1263Resource\Pages;

use App\Filament\Resources\FormTv1263Resource;
use Filament\Actions; 
use Actions\CreateAction; 
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords\Tab;

use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextArea; 

class ViewFormTv1263 extends ViewRecord
{
    protected static string $resource = FormTv1263Resource::class;

    protected static ?string $title = 'TV: Chiller and Freezer Temperature Daily Verification';


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
                    $this->redirect('/form-tv1263s');
                })
                // ->slideOver()
                // ->visible(auth()->user()->hasRole(Role::ROLES['approver']))
                ->visible(function (array $data) { 
                    return !$this->record->is_approved;
                })
        ];
    } 

}
