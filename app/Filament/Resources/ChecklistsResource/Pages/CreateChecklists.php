<?php

namespace App\Filament\Resources\ChecklistsResource\Pages;

use App\Filament\Resources\ChecklistsResource;
use App\Models\CheckList;
use App\Models\CheckListItemsEntry;
use App\Models\SubSectionItem;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Actions\Action as Action2;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChecklists extends CreateRecord
{
    protected static string $resource = ChecklistsResource::class;

    protected static ?string $title = 'Pre-Op forms';

    // protected function getCreateFormAction(): Action
    // {
    //     return Action::make('create')
    //         ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
    //         ->submit('create')
    //         ->keyBindings(['mod+s']);
    // }

    public function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            Action::make('saveAnother')
                ->label('Submit and Continue')
                ->action('createAnother')
                ->keyBindings(['mod+shift+s'])
                ->color('gray'),
            // $this->getCancelFormAction(),
        ];
    }

    public function getCreateFormAction(): Action
    {
        return Action::make('create')
        // ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->label('Submit')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();
        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            // dd($data);

            if (! $another) {
                $checkList = CheckList::create([
                    'name' => 'Form SW1 - Hygiene swab and Pre - Op checklist'.'_'.now(),
                    'site_id' => 2,
                    'type_id' => 1,
                    // 'entry_detail' => $data['entry_detail'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'finish_time' => $data['finish_time'],
                    'person_name' => $data['person_name'],
                    'inspected_by' => $data['inspected_by'],
                    'status' => 1,
                ]);
            } else {
                $checkList = CheckList::create([
                    'name' => 'Form SW1 - Hygiene swab and Pre - Op checklist'.'_'.now(),
                    'site_id' => 2,
                    'type_id' => 1,
                    // 'entry_detail' => $data['entry_detail'],
                    'date' => $data['date'],
                    'time' => $data['time'],
                    'finish_time' => $data['finish_time'],
                    'person_name' => $data['person_name'],
                    'inspected_by' => $data['inspected_by'],
                    'status' => 0,
                ]);
            }

            $entryId = $checkList->id;

            $this->callHook('beforeCreate');

            foreach ($data as $fieldKey => $fieldValue) {
                $matches = [];

                if (preg_match('/^(.+)_(\d+)$/', $fieldKey, $matches)) {
                    $fieldName = $matches[1];
                    $checklistItemId = $matches[2];
                    $dataByChecklistItem[$checklistItemId]['check_list_items_id'] = $checklistItemId;
                    $dataByChecklistItem[$checklistItemId]['entry_id'] = $entryId;
                    $dataByChecklistItem[$checklistItemId][$fieldName] = $fieldValue;

                    // Retrieve and include subsection item information
                    $subsectionId = $this->getSubsectionIdFromFieldName($fieldName);
                    $dataByChecklistItem[$checklistItemId]['sub_section_item_id'] = $this->getSubsectionItemId($subsectionId);

                } else {
                    // dd('No Match Found !');
                }
            }

            foreach ($dataByChecklistItem as $checklistItemId => $entryData) {
                // \Log::info('$entryData',$entryData);
                if (is_array($entryData) && array_key_exists('sub_section_items', $entryData)) {
                    // \Log::info('$entryData[sub_section_items]',$entryData['sub_section_items']);
                    $entryData['sub_section_items'] = implode(', ', $entryData['sub_section_items']);
                }
                CheckListItemsEntry::create($entryData);
            }

            if (! $another) {
                $recipient = User::whereHas('sites', function ($query) {
                    $query->where('site_id', 2);
                })
                    ->whereHas('roles', function ($query) {
                        $query->where('role_id', 3);
                    })->get();
                Notification::make()
                    ->title('Pre-Op forms Submitted!')
                    ->success()
                        // ->url(fn (CheckList $record): string => route('generate.pdf', $record))
                    ->body('Created Pre-Op forms Form, kindly view and approve')
                    ->actions([
                        Action2::make('View and Approve')
                            ->button()
                            ->url('/checklists/'.$entryId)
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($recipient);
            }

            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->getCreatedNotification()?->send();

        // if ($another) {
        //     $this->form->model($this->record::class);
        //     $this->record = null;
        //     $this->fillForm();
        //     return;
        // }

        // $this->redirect($this->getRedirectUrl());

        $this->redirect('/checklists');

    }

    public function mount(): void
    {
        // dd('form');

        $this->authorizeAccess();

        $this->fillForm();

        $this->previousUrl = url()->previous();
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
}
