<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Placeholder;


use App\Filament\Resources\ChecklistsResource;
use App\Models\CheckListItemsEntry as Entries;


class ViewChecklists extends ViewRecord
{
    protected static string $resource = ChecklistsResource::class;


    protected function getHeaderActions(): array
    {
        return [
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
                    $user = Auth::user();
                    $this->record->comments = $data['comments'];
                    $this->record->is_approved = true; // $data['status']; 
                    $this->record->approved_by = $user->name;
                    $this->record->save();
                    $this->redirect('/checklists');
                })
                ->visible(function (array $data) { 
                    return !$this->record->is_approved;
                })
        ];
    } 
    

    public function mount($record): void
    {

        // dd($record);

        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();

        $this->fillForm();

        $this->fillExistingEntries(); // Add this line to fill existing entries

        $this->previousUrl = url()->previous();
    } 



    protected function fillExistingEntries(): void
    {
        $id = Route::current()->parameter('record');
        // dd($id);
        $existingEntries = Entries::where('entry_id', $id)->get();
        // dd($existingEntries);
    
        foreach ($existingEntries as $entry) {
            $checklistItemId = $entry->check_list_items_id;
            $fieldsToUpdate = [
                'visual_insp_allergen_free',
                'micro_SPC_swab',
                'chemical_residue_check',
                'TP_check_RLU',
                'comments_corrective_actions',
                'action_taken',
                'sub_section_items',
                'entry_id',
                'ATP_check_RLU',
                'person_name',
                'entry_detail',
                'date',
                'time',
                'inspected_by'
            ];
    
            // foreach ($fieldsToUpdate as $fieldName) {
            //     $fullFieldName = "{$fieldName}_$checklistItemId";
            //     $this->data[$fullFieldName] = $entry->$fieldName;
            // }

            foreach ($fieldsToUpdate as $fieldName) {
                $fullFieldName = "{$fieldName}_$checklistItemId";
                if ($fieldName === 'sub_section_items' && is_string($entry->$fieldName) && $entry->$fieldName != '') {
                    $this->data[$fullFieldName] = explode(', ', $entry->$fieldName);
                } else {
                    $this->data[$fullFieldName] = $entry->$fieldName;
                }
            }

            
        }
    }
    
}
