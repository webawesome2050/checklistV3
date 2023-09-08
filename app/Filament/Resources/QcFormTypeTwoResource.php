<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CheckListItem;
use App\Models\EntriesMaster;
use Filament\Resources\Resource;
use Filament\Forms\Components\Text;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CheckList as QcFormTypeTwo;
use App\Models\CheckListItemsEntry as Entries;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\QcFormTypeTwoResource\Pages;
use App\Filament\Resources\QcFormTypeTwoResource\RelationManagers;
use App\Filament\Resources\QcFormTypeTwoResource\RelationManagers\DetailsRelationManager;
use App\Filament\Resources\QcFormTypeTwoResource\RelationManagers\TemperatureChecklistRelationManager;
 
class QcFormTypeTwoResource extends Resource
{
    protected static ?string $model = QcFormTypeTwo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'QC Forms';

    protected static ?string $navigationLabel = 'GMP';
     

    public static function form(Form $form): Form
    { 


        // $currentUrl = request()->url();
        // $parts = explode('/', $currentUrl);
        // $checkListId = end($parts);
        // $id = 2;
        // $id = Route::current()->parameter('record');

        $url = request()->url(); 
        preg_match('/\/checklists\/(\d+)\/edit/', $url, $matches);
        if (isset($matches[1])) {
            $id = $matches[1];
            session(['checklist_id' => $id]); 
        } else {
            $id = session('checklist_id');
        } 

        $checklistItems = CheckListItem::where('check_list_id', 2)->get();
 
            $checklistItemsBySectionAndSubsection = $checklistItems->groupBy(['section_id', 'sub_section_id']);
        foreach ($checklistItemsBySectionAndSubsection as $sectionId => $subsectionGroups) {
            $sectionName = $subsectionGroups->first()->first()->section->name;
            $itemCount = count($subsectionGroups); 
            $sectionName =   $sectionName;

            $sectionComponents = [];
            $subsectionNameArray  = [];
            foreach ($subsectionGroups as $subsectionId => $checklistItemsInSubsection) {
                $subsectionName = $checklistItemsInSubsection->first()->first()->subSection->name; 

                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                if ($matchingItem) {
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    // ->description('Step Description')
                    ->columns(4)
                    ->compact()
                    ->collapsed(); // Set the section to be collapsed by default
                    
                } else {
                    $subsectionSection = Section::make($sectionName)
                    // ->description('Step Description')
                    ->columns(4)
                    ->compact();
                    // ->collapsed(); // Set the section to be collapsed by default
                }
                
                $formFields = [];
                $stepFields = [];
                foreach ($checklistItemsInSubsection as $checklistItem) {
                    $stepFields[]   =  
                 Section::make($checklistItem->name)
                //  ->aside()
                ->description($checklistItem->is_approved ? '' : 'Pending') 
                ->schema([ 
                     $formFields[] = Select::make("visual_insp_allergen_free_{$checklistItem->id}")
                        ->label('Condition')
                        ->options([
                            'Accept' => 'Yes',
                            'Reject' => 'No',
                            'Not in Use' => 'Not in Use'
                        ]),
                        $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        ->rows(2),  
                        $formFields[] = TextInput::make("micro_SPC_swab_$checklistItem->id")->label('Person Responsible')->name('micro_SPC_swab'),
                        // $formFields[] = TextInput::make("chemical_residue_check_$checklistItem->id")->label('Chemical Residue Check')->name('chemical_residue_check'),
                        // $formFields[] = Select::make("TP_check_RLU_$checklistItem->id")
                        //     ->label('ATP check RLU')
                        //     ->options([
                        //         'Pass' => 'Pass',
                        //         'Fail' => 'Fail'
                        //     ]),
                        $formFields[] = Select::make("action_taken_$checklistItem->id")
                            ->label('Action Taken')
                            ->options([
                                'Yes' => 'Yes',
                                'No' => 'No'
                            ]),
                                              
                    ])->columns(4)->compact(); 
                } 

                $subsectionSection->schema($stepFields); 
                $sectionComponents[] = $subsectionSection; 
            }
            $sectionStep = Wizard\Step::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }
        $form->schema([
            Wizard::make($wizardSteps)->skippable(),
        ])->columns(1);
         
        

        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                // TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('site.name'),
                Tables\Columns\IconColumn::make('is_approved')
                ->boolean(),
                // TextColumn::make('')
                // ->label('Status')
                // ->description(function (QcFormTypeTwo $record) {
                //     $res= $record->is_approved == false;
                //     if ($res){
                //         return "Pending";
                //     }
                //     else{
                //         return "Approved";
                //     }
                // }),
                TextColumn::make('created_at')
                ->dateTime()
                ->label('Created on')
                ->sortable(),
                // TextColumn::make('checklist.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('View and Approve')
                    ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
                     Tables\Actions\EditAction::make()
                     ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            ])
            

            // ->actions([
            //     Tables\Actions\ViewAction::make()->label('View and Approve')
            //     ->hidden(function (QcFormTypeTwo $record) {
            //         return $record->is_approved == true && auth()->user()->hasRole(Role::ROLES['approver']);
            //     }),
            //          Tables\Actions\EditAction::make()
            //          ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            // ])

            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]
        )
        ->modifyQueryUsing(fn (Builder $query) => $query->where('type_id', 2));
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQcFormTypeTwos::route('/'),
            'create' => Pages\CreateQcFormTypeTwo::route('/create'),
            'edit' => Pages\EditQcFormTypeTwo::route('/{record}/edit'),
            'view' => Pages\ViewQcFormTypeTwo::route('/{record}'),
        ];
    }    
}
