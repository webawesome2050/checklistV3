<?php

namespace App\Filament\Resources;


use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use Faker\Core\Number;
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
use Filament\Forms\Components\DateTimePicker;

use Filament\Infolists;
use Tables\Actions\ViewAction;
use Filament\Infolists\Infolist;
use App\Models\FormTvSite2 as FormTv;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormTv1263Resource\Pages;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use App\Filament\Resources\FormTv1263Resource\RelationManagers;

class FormTv1263Resource extends Resource
{
    protected static ?string $model = FormTv::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Site 1263';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'TV';

    protected static ?string $breadcrumb = 'Site 1263 - TV';
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                // Forms\Components\TextInput::make('version')
                //     ->maxLength(255)
                //     ->required(),
                // Forms\Components\TextInput::make('issues_by')
                //     ->maxLength(255)
                //     ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Comments')
                    ->maxLength(123)
                    ->required()
                    // ->columnSpanFull(),
                ])
                ->columnSpan(['lg' =>3]),

            Forms\Components\Section::make()
                ->schema([
                    DatePicker::make('date'),
                    TimePicker::make('time'),  
    
                    TextInput::make('base_area_f1')->label('BA F1'),
                    TextInput::make('base_area_f2')->label('BA F2'),
                    TextInput::make('base_area_cool_room')->label('BA Cool Room'),
                    TextInput::make('medium_area_cool_freezer')->label('BA Cool Freezer'),
                    TextInput::make('medium_area_cool_chiller1')->label('MA Cool Chiller'),
                    TextInput::make('medium_area_cool_chiller2')->label('MA Cool Chiller2'),
                    TextInput::make('medium_area_cool_cooked_wip_chiller')->label('MA Cooked WIP Chiller'),
                    TextInput::make('medium_area_cool_wip_chiller')->label('MA Cooked WIP Chiller'),
                    TextInput::make('high_area_cool_freezer')->label('HA cool freezer'),
                    TextInput::make('high_area_cool_chiller')->label('HA cool chiller'),
                    TextInput::make('outer_cartooning_room')->label('Outer cartooning room'),
                    TextInput::make('factory_lunch_room_fridge')->label('Factory lunch room fridge'),
                    TextInput::make('office_staff_lunch_room_fridge')->label('Staff lunch room fridge'),
                    TextInput::make('verified_by')->label('Approved By'),
                    Toggle::make('is_verified')
                    ->visible(auth()->user()->hasRole(Role::ROLES['approver'])), 
                ])
                ->columns(4)
                ->columnSpan(['lg' => 9]),
        ])
        ->columns(12); 
          
    }

    public static function table(Table $table): Table
    {

        //   dd(auth()->user()->hasRole(Role::ROLES['approver']));


        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('issues_by')
                //     ->searchable(),
                //     Tables\Columns\IconColumn::make('is_approved')
                //     ->boolean(),

                // Tables\Columns\TextColumn::make('')
                // ->label('Status')
                // ->description(function (FormTv $record) {
                //     $res= $record->is_approved == false ;
                //     if ($res){
                //         return "Pending";
                //     }
                //     else{
                //         return "Approved";
                //     }
                // }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->striped()
            ->filters([
                //
            ])
            ->actions([  

                Action::make('Download Report')->label('Download Report')
                ->url(fn (FormTv $record): string => route('generate.atp', $record))
                ->openUrlInNewTab()
                ->visible(function (FormTv $record): bool {
                    return ($record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || ($record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                })
                ->icon('heroicon-m-arrow-down-on-square'),
                Tables\Actions\ViewAction::make()->label('View and Approve')
                ->visible(function (FormTv $record): bool {
                    return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                }),
                Tables\Actions\EditAction::make()
                ->visible(function (FormTv $record): bool {
                    return (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['approver'])) || (!$record->is_approved && auth()->user()->hasRole(Role::ROLES['admin']));
                }),

                    // Tables\Actions\EditAction::make()->label('View and Approve')
                    // ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
                    //  Tables\Actions\EditAction::make()
                    //  ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])), 
            ])

            // ->actions([
            //     Tables\Actions\ViewAction::make()->label('View and Approve')
            //     ->hidden(function (FormTv $record) {
            //         return $record->is_approved == true && auth()->user()->hasRole(Role::ROLES['approver']);
            //     }),
            //          Tables\Actions\EditAction::make()
            //          ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])),
            // ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make()->label('New Temperature Verification'),
            ]);
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
        'index' => Pages\ListFormTv1263s::route('/'),
            'create' => Pages\CreateFormTv1263::route('/create'),
            'edit' => Pages\EditFormTv1263::route('/{record}/edit'),
            'view' => Pages\ViewFormTv1263::route('/{record}'),
        ];
    }    
}
