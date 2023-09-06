<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use Filament\Pages\Actions;
// use Symfony\Component\Routing\Route;
use Illuminate\Support\Facades\Route;
use Filament\Resources\Pages\EditRecord;

use App\Filament\Resources\ChecklistsResource;
use App\Models\CheckListItemsEntry as Entries;

class EditChecklists extends EditRecord
{
    protected static string $resource = ChecklistsResource::class;

    protected static ?string $title = 'QC Forms';
    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();
        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');
            $data = $this->mutateFormDataBeforeSave($data);
            $this->callHook('beforeSave');
            // dd($data);
            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];
                if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                    $fieldName = $matches[1];
                    $checklistItemId = $matches[2];
            
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;
                } else {
                    dd('No Match Found !');
                }
            }
            // dd(@$dataByChecklistItem);
            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                $record = Entries::where('check_list_items_id', $checklistItemId)->first();
                if ($record) {
                    $this->handleRecordUpdate($record, $entryData);
                }
            }
     
            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }
    
        $this->getSavedNotification()?->send();
    
        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl);
        }
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    
        protected function getHeaderActions(): array
        {
            return [
                // Actions\DeleteAction::make(),
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
                    'action_taken'
                ];
        
                foreach ($fieldsToUpdate as $fieldName) {
                    $fullFieldName = "{$fieldName}_$checklistItemId";
                    $this->data[$fullFieldName] = $entry->$fieldName;
                }
            }
        }
}
