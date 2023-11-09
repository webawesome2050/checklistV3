<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MicroSwabSiteOneResource\Pages;
use App\Filament\Resources\MicroSwabSiteOneResource\RelationManagers;


use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\CheckList;
use Filament\Tables\Table;
use App\Models\CheckListItem;
use App\Models\EntriesMaster;
use App\Models\SubSectionItem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Text;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;




use Illuminate\Database\Eloquent\SoftDeletingScope;

class MicroSwabSiteOneResource extends Resource
{
    protected static ?string $model = CheckList::class;   
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Site 34 Forms';
    protected static ?int $navigationSort = 14;
    protected static ?string $navigationLabel = 'Micro SPC Swab Check';
    protected static ?string $breadcrumb = 'Micro SPC Swab Check';


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('type_id',8)->count();
    }

    public static function form(Form $form): Form
    { 
 
       
        $optionsValue = [];
        for ($i = 0; $i <= 60; $i++) {
            $optionsValue[$i] = $i;
        }

        $url = request()->url(); 
        preg_match('/\/checklists\/(\d+)\/edit/', $url, $matches);
        if (isset($matches[1])) {
            $id = $matches[1];
            session(['checklist_id' => $id]); 
        } else {
            $id = session('checklist_id');
        } 

        
         
        $checklistItems = CheckListItem::where('check_list_id', 20)->get();
        $checklistItemsBySectionAndSubsection = $checklistItems->groupBy(['section_id', 'sub_section_id']);

        foreach ($checklistItemsBySectionAndSubsection as $sectionId => $subsectionGroups) {
            $sectionName = $subsectionGroups->first()->first()->section->name;
            $itemCount = count($subsectionGroups);
           // $sectionName =   $sectionName."-".$itemCount;
            $sectionName =   $sectionName;

            $sectionComponents = [];
            $subsectionNameArray  = [];
            foreach ($subsectionGroups as $subsectionId => $checklistItemsInSubsection) {
                $subsectionName = $checklistItemsInSubsection->first()->first()->subSection->name;
                // Retrieve sub-section items for the current sub-section
                $subSectionItems = SubSectionItem::where('sub_section_id', $subsectionId)->get();
                $formFields = [];
                // Check if there are sub-section items
                if ($subSectionItems->count() > 0) {
                    // There are sub-section items, so add radio buttons
                    $radioOptions = [];
                    foreach ($subSectionItems as $subSectionItem) {
                        // Add each sub-section item as an option for the radio button
                        // $radioOptions["sub_section_item_{$subSectionItem->id}"] = $subSectionItem->name;
                        $radioOptions[$subSectionItem->name] = $subSectionItem->name;
                    }
                    // \Log::info($radioOptions);
                    // $itemCode = Radio::make("sub_section_items_$subsectionId")
                    //     ->label('')
                    //     ->options($radioOptions)
                    //     ->inline();
                    $itemCode = CheckboxList::make("sub_section_items_$subsectionId")
                        ->label('')
                        ->options($radioOptions);
                } else {
                    $formFields = [];
                }
                
                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });
                $matchingItem = $checklistItemsInSubsection->first(function ($item) use ($subsectionId) {
                    return $item->sub_section_id === $subsectionId;
                });

                if ($matchingItem) {
                    $subsectionName = $matchingItem->subSection->name;  
                    $subsectionSection = Section::make($subsectionName)
                    ->extraAttributes([
                        'class' => 'section-portion cursor-pointer'
                    ])
                    ->collapsible()
                    ->description($matchingItem->subSection->atp_frequency ? 'ATP check RLU Frequency => '.$matchingItem->subSection->atp_frequency : '' )
                    ->columns(4)
                    ->compact();
                    
                } else {
                    $subsectionSection = Section::make('Section')
                    ->columns(4)
                    ->compact()
                    ->collapsed(); // Set the section to be collapsed by default
                }
                if(count($subSectionItems) > 0) {
                    // \Log::info($formFields);
                } 
                // $formFields = [];
                $stepFields = [];
                if(count($subSectionItems) > 0) {
                    $stepFields[]   =  
                    Section::make('Select machine number')
                    ->extraAttributes([
                        'class' => 'machine-section'
                    ])
                    ->icon('heroicon-m-megaphone')
                    // ->aside()
                   ->schema([ 
                    $formFields[] = $itemCode
                    ]);
                }

                foreach ($checklistItemsInSubsection as $checklistItem) {
                   
                    $description = $checklistItem->m_frequency ? 'M Frequency =>'.$checklistItem->m_frequency : '';
                    $description.=$checklistItem->c_frequency ? ' ---- C. Frequency =>'.$checklistItem->c_frequency : '';
                    $description.=$checklistItem->a_frequency ? ' ---- A. Frequency =>'.$checklistItem->a_frequency : '';

                    $stepFields[]   =  
                 Section::make($checklistItem->name)
                 ->description($description)
                 ->aside()
                ->schema([ 
                        $formFields[] =  Textinput::make("micro_SPC_swab_{$checklistItem->id}")
                        // $formFields[] =  Select::make("micro_SPC_swab_{$checklistItem->id}")
                        ->label('Micro SPC swab Check'),
                        $formFields[] = Textarea::make("comments_corrective_actions_$checklistItem->id")->label('Comments & Corrective Actions')->name('comments_corrective_actions')
                        ->rows(1),  
                        Radio::make("action_taken_$checklistItem->id")->label('Is Testing Done')
                        ->options([
                            'Yes' => 'Yes',
                            'No' => 'No'
                        ]), 
                        $formFields[] =  Hidden::make("entry_id_$checklistItem->id"),
                        
                    ])->columns(3)->compact(); 
                } 
                $subsectionSection->schema($stepFields); 
                $sectionComponents[] = $subsectionSection; 
            }
            $sectionStep = Tab::make($sectionName)->schema($sectionComponents);
            $wizardSteps[] = $sectionStep;
        }
        

        
 

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Tabs::make('Label')->tabs($wizardSteps)
                    ])
                    ->columnSpan(['lg' =>2]),

                Forms\Components\Section::make()
                    ->schema([
                        Hidden::make('id'),
                        Forms\Components\TextInput::make('person_name')
                        ->label('Person Name')
                        ->maxLength(255)
                        ->required(), 
                        DateTimePicker::make('entry_detail')
                        ->label('Entry Detail')
                        ->native(false),
                        // DateTimePicker::make('next_inspection_detail')
                        // ->label('Next Inspectin Date')
                        // ->native(false),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    { 
        return $table
            ->columns([
                //
                // TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('created_at')
                ->dateTime()
                ->label('Created on'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
                TextColumn::make('')
                ->label('Status')
                ->description(function (CheckList $record) {
                    $res= $record->is_approved == 0 ;
                    if ($res){
                        return "Pending";
                    }
                    else{
                        return "Approved";
                    }
                }),
                TextColumn::make('site.name')
            ])
            ->striped()
            ->filters([
            ])
            ->actions([

                Action::make('Download Report')->label('Download Report')
                ->url(fn (CheckList $record): string => route('generate.micro', $record))
                ->openUrlInNewTab()
                ->visible(function (CheckList $record): bool {
                    return ($record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || ($record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                })
                ->icon('heroicon-m-arrow-down-on-square'),
                Tables\Actions\ViewAction::make()->label('View and Approve')
                ->visible(function (CheckList $record): bool {
                    return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                }),
                Tables\Actions\EditAction::make()
                ->visible(function (CheckList $record): bool {
                    return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]
        )
        ->modifyQueryUsing(fn (Builder $query) => $query->where('type_id', 8));
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
            'index' => Pages\ListMicroSwabSiteOnes::route('/'),
            'create' => Pages\CreateMicroSwabSiteOne::route('/create'),
            'view' => Pages\ViewMicroSwabSiteOne::route('/{record}'),
            'edit' => Pages\EditMicroSwabSiteOne::route('/{record}/edit'),
        ];
    }    
}
