<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\CheckListItem;
use App\Models\EntriesMaster;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EntriesMasterResource\Pages;
use App\Filament\Resources\EntriesMasterResource\RelationManagers;

class EntriesMasterResource extends Resource
{
    protected static ?string $model = EntriesMaster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';

    
    public static function form(Form $form): Form
    {
        
        return $form
            ->schema([
                //
                TextInput::make('name')
                ->required()
            ]);
        
        //$checklistItems = CheckListItem::all(); 
                // $checklistItemsBySection = $checklistItems->groupBy('section_id'); 
                // $wizardSteps = [];
         
                // foreach ($checklistItemsBySection as $sectionId => $checklistItemsInSection) {
                //     $stepFields = [];
         
                //     foreach ($checklistItemsInSection as $checklistItem) { 
                //         $stepFields[]   =   Section::make($checklistItem->name)
                //         // ->description('Description')
                //         ->schema([
                           
                        //  $formFields[] =  
                        //  TextInput::make("check_list_item_$checklistItem->id")
                        //  ->label($checklistItem->id)
                        //  ->name("check_list_item_$checklistItem->id")
                        //  ->default($checklistItem->id),
                        //  ->name('check_list_item'),

                        //  Select::make("visual_insp_allergen_free_$checklistItem->id")
                        //     ->options([
                        //         'A' => 'Accept',
                        //         'R' => 'Reject',
                        //         'NA' => 'Not in Use'
                        //     ]),

                        //     Select::make("action_taken_$checklistItem->id")
                        //     ->label('Action Taken')
                        //     ->options([
                        //         'yes' => 'Yes',
                        //         'no' => 'No'
                        //     ]),

                        // $formFields[] = TextInput::make("micro_SPC_swab[$checklistItem->id]")->label('Micro SPC Swab *')->name('micro_SPC_swab'),
                        // $formFields[] = TextInput::make("chemical_residue_check[$checklistItem->id]")->label('Chemical Residue Check')->name('chemical_residue_check'),
                        // $formFields[] = TextInput::make("TP_check_RLU[$checklistItem->id]")->label('ATP check RLU Pass/Fail')->name('TP_check_RLU'),
                        // $formFields[] = TextInput::make("action_taken[$checklistItem->id]")->label('Action Taken (Yes/ No)')->name('action_taken'),
                       
                    //     $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                    //     ->rows(2),                        
                    //         ])->columns(4)->collapsible(); 

                    // } 

                    // $sectionName = $checklistItemsInSection->first()->section->name;
                    // $itemCount = count($checklistItemsInSection);
                    // $sectionName =   $sectionName."-".$itemCount;
 
                    // $wizardSteps[] = Wizard\Step::make($sectionName)->schema($stepFields);
                // }
          
                // $form->schema([
                //     Wizard::make($wizardSteps)->skippable(),
                // ])->columns(1);
                return $form;
                // return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id'),
                TextColumn::make('name')
            ])
            ->striped()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\ChecklistsRelationManager::class,
        ];
    }
    

//     public static function edit($recordId)
// {
//     // Assuming you have the ID of the record you want to edit (e.g., $recordId)
//     $existingRecord = CheckListItemsEntry::find($recordId);

//     // Return the view with the existing record data
//     return view('edit_entries', compact('existingRecord'));
// }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntriesMasters::route('/'),
            // 'create' => Pages\CreateEntriesMaster::route('/create'),
            'edit' => Pages\EditEntriesMaster::route('/{record}/edit'),
        ];
    }    
}
