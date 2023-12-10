<?php

namespace App\Filament\Resources\GmpSiteOneResource\Pages;

use App\Filament\Resources\GmpSiteOneResource;
use App\Models\CheckListItemsEntry as Entries;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class ViewGmpSiteOne extends ViewRecord
{
    protected static string $resource = GmpSiteOneResource::class;

    protected static ?string $title = 'GMP';

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
                        ->rows(10),
                ])
                ->action(function (array $data): void {
                    $user = Auth::user();
                    $this->record->approved_by = $user->name;
                    $this->record->comments = $data['comments'];
                    $this->record->is_approved = true; // $data['status'];
                    $this->record->save();
                    $this->redirect('/gmp-site-ones');
                })
                ->visible(function (array $data) {
                    return ! $this->record->is_approved;
                }),
            // ->slideOver()
            // ->visible(auth()->user()->))
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
                'entry_id',
                'entry_detail',
                'person_name',
                'date',
                'time',
            ];

            foreach ($fieldsToUpdate as $fieldName) {
                $fullFieldName = "{$fieldName}_$checklistItemId";
                $this->data[$fullFieldName] = $entry->$fieldName;
            }
        }
    }
}
