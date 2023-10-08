<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;

use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\FormTvSite2 as FormTv;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FormTv1263Resource\Pages;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use App\Filament\Resources\FormTv1263Resource\RelationManagers;

class FormTv1263Resource extends Resource
{
    protected static ?string $model = FormTv::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Site 1263';

    protected static ?string $navigationLabel = 'TV';

    protected static ?string $breadcrumb = 'Site 1263 - TV';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('version')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\TextInput::make('issues_by')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\RichEditor::make('notes')
                    ->maxLength(65535)
                    ->required()
                    ->columnSpanFull(),
                    Repeater::make('tv_lines')
            // TableRepeater::make('tv_lines')
            ->relationship()
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
                // TextInput::make('is_verified'),
                // TextInput::make('is_verified'),
            ])
            ->label('Temperature Daily Verification')
            // ->grid(3)
            ->columns(6)
            ->columnSpan('full')
            ]);
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
                    Tables\Actions\EditAction::make()->label('View and Approve')
                    ->hidden(!auth()->user()->hasRole(Role::ROLES['approver'])),
                     Tables\Actions\EditAction::make()
                     ->hidden(auth()->user()->hasRole(Role::ROLES['approver'])), 
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
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->label('New Temperature Verification'),
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
        ];
    }    
}
