<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use App\Filament\Resources\ChecklistsResource;
use App\Models\CheckList;
use App\Models\CheckListItemsEntry as Entries;
// use Symfony\Component\Routing\Route;
use App\Models\SubSectionItem;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as SendNote;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Route;

class EditChecklists extends EditRecord
{
    protected static string $resource = ChecklistsResource::class;

    protected static ?string $title = 'QC Forms';

    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            Action::make('saveAnother')
                ->label('Submit and Continue')
                ->action('saveAnother')
                ->keyBindings(['mod+shift+s'])
                ->color('gray'),
            // $this->getCancelFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Submit')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function saveAnother(bool $shouldRedirect = true): void
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
                    // \Log::info('$fieldName',$fieldName);
                    //  \Log::info('$fieldName');
                    // $subsectionId = $this->getSubsectionIdFromFieldName($fieldName);
                    // $dataByChecklistItem[$checklistItemId]['sub_section_item_id'] = $this->getSubsectionItemId($subsectionId);
                } else {
                    // dd('No Match Found !');
                }
            }

            $checkList = CheckList::find($data['id']);
            $checkList->update([
                // 'entry_detail' => $data['entry_detail'],
                'person_name' => $data['person_name'],
                'date' => $data['date'],
                'time' => $data['time'],
                'finish_time' => $data['finish_time'],
                'inspected_by' => $data['inspected_by'],
                // 'next_inspection_detail' => $data['next_inspection_detail'],
            ]);

            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                // $record = Entries::
                // where('check_list_items_id', $checklistItemId)
                // ->where('entry_id', $id)
                // ->first();

                // if (is_array($entryData) && array_key_exists('sub_section_items', $entryData)) {
                //     $entryData['sub_section_items'] = implode(', ', $entryData['sub_section_items']);
                // }

                // \Log::info($entryData['sub_section_items']);
                // dd('wait');
                if (is_array($entryData) && array_key_exists('sub_section_items', $entryData) && $entryData['sub_section_items'] != null) {
                    $entryData['sub_section_items'] = implode(', ', $entryData['sub_section_items']);
                }
                \Log::info('entryData', $entryData);

                $query = Entries::where('check_list_items_id', $checklistItemId)
                    ->where('entry_id', $entryData['entry_id']);
                $record = $query->first();

                if ($record) {
                    $this->handleRecordUpdate($record, $entryData);
                }
            }

            $recipient = User::whereHas('sites', function ($query) {
                $query->where('site_id', 2);
            })
                ->whereHas('roles', function ($query) {
                    $query->where('role_id', 3);
                })->get();

            \Log::info($recipient);

            // Notification::make()
            // ->title('Hygiene Submitted!')
            // ->success()
            // ->body('Updated Pre-Op form, kindly view and approve')
            // ->actions([
            //     Action::make('View and Approve')
            //         ->button()
            //         ->url('/checklists/'.$this->record->id)
            //         ->markAsRead(),
            // ])
            // ->sendToDatabase($recipient);

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->getSavedNotification()?->send();

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl);
        }
    }

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
                    // dd('No Match Found !');
                }
            }

            $checkList = CheckList::find($data['id']);
            $checkList->update([
                // 'entry_detail' => $data['entry_detail'],
                'person_name' => $data['person_name'],
                'date' => $data['date'],
                'time' => $data['time'],
                'finish_time' => $data['finish_time'],
                'inspected_by' => $data['inspected_by'],
                // 'next_inspection_detail' => $data['next_inspection_detail'],
            ]);

            // foreach ($fieldsToUpdate as $fieldName) {
            //     $fullFieldName = "{$fieldName}_$checklistItemId";
            //     if ($fieldName === 'sub_section_items' && is_string($entry->$fieldName) && $entry->$fieldName != '') {
            //         $this->data[$fullFieldName] = explode(', ', $entry->$fieldName);
            //     } else {
            //         $this->data[$fullFieldName] = $entry->$fieldName;
            //     }
            // }
            // dd($dataByChecklistItem);

            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                if (is_array($entryData) && array_key_exists('sub_section_items', $entryData) && $entryData['sub_section_items'] != null) {
                    // \Log::info('before entryData', $entryData['sub_section_items']);
                    $entryData['sub_section_items'] = implode(', ', $entryData['sub_section_items']);
                }
                \Log::info('entryData', $entryData);
                $query = Entries::where('check_list_items_id', $checklistItemId)
                    ->where('entry_id', $entryData['entry_id']);
                $record = $query->first();

                if ($record) {
                    $this->handleRecordUpdate($record, $entryData);
                    \Log::info('entryData', $entryData);
                }
            }

            $recipient = User::whereHas('sites', function ($query) {
                $query->where('site_id', 2);
            })
                ->whereHas('roles', function ($query) {
                    $query->where('role_id', 3);
                })->get();

            \Log::info($recipient);
            // update Checklsit status
            CheckList::where('id', $data['id'])->update(['status' => 1]);
            Notification::make()
                ->title('Hygiene Submitted!')
                ->success()
                ->body('Updated Pre-Op form, kindly view and approve')
                ->actions([
                    SendNote::make('View and Approve')
                        ->button()
                        ->url('/checklists/'.$this->record->id)
                        ->markAsRead(),
                ])
                ->sendToDatabase($recipient);

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

    // Helper function to extract subsection ID from field name
    private function getSubsectionIdFromFieldName($fieldName)
    {
        // Extract subsection ID from the field name using a regular expression
        $matches = [];
        if (preg_match('/^subsection_(\d+)_/', $fieldName, $matches)) {
            return $matches[1];
        }

        return null; // Return null if no match is found
    }

    // Helper function to retrieve subsection item ID
    private function getSubsectionItemId($subsectionId)
    {
        // Query your database to retrieve the subsection item ID based on the subsection ID
        // Replace 'SubSectionItem' with the actual model name for your subsection items
        $subsectionItem = SubSectionItem::where('sub_section_id', $subsectionId)->first();

        if ($subsectionItem) {
            return $subsectionItem->id;
        }

        return null; // Return null if no subsection item is found for the subsection ID
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
                'finish_time',
                'inspected_by',
                'status',
            ];

            foreach ($fieldsToUpdate as $fieldName) {
                $fullFieldName = "{$fieldName}_$checklistItemId";
                if ($fieldName === 'sub_section_items' && is_string($entry->$fieldName) && $entry->$fieldName != null) {
                    $this->data[$fullFieldName] = explode(', ', $entry->$fieldName);
                } elseif ($fieldName === 'sub_section_items' && $entry->$fieldName == null) {
                    $this->data[$fullFieldName] = []; //explode(', ', $entry->$fieldName);
                } else {
                    $this->data[$fullFieldName] = $entry->$fieldName;
                }
            }
        }
        // dd($this->data);
    }
}
