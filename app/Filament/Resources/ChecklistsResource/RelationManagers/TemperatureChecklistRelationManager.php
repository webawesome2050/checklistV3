<?php

namespace App\Filament\Resources\ChecklistsResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TemperatureChecklistRelationManager extends RelationManager
{
    protected static string $relationship = 'TemperatureChecklist';

    protected static ?string $recordTitleAttribute = 'id';

    public  function form(Form $form): Form
    {
        $id = Route::current()->parameter('record');
        // dd($id);
        if($id != 3) {
        $form
            ->schema([ 
                // Forms\Components\DatePicker::make('date'),
                // Forms\Components\TimePicker::make('time')->label('Time'),
                Forms\Components\TextInput::make('freezer1')->label('Base Area Finished Goods Freezer 1 & Freezer 2 new F1'),
                Forms\Components\TextInput::make('freezer2')->label('Base Area Finished Goods Freezer 1 & Freezer 2 new F2'),
                Forms\Components\TextInput::make('cool_room')->label('Base Area    Main Cool Room'),
                Forms\Components\TextInput::make('raw_material_chiller')->label('Base Area Raw Material Chiller'),
                Forms\Components\TextInput::make('WIP_freezer')->label('Medium Area WIP Freezer'),
                Forms\Components\TextInput::make('blast_chiller_1')->label('Medium Area Blast Chiller 1'),
                Forms\Components\TextInput::make('blast_chiller_2')->label('Medium Area Blast Chiller 2'),
                Forms\Components\TextInput::make('cooked_WIP_chiller')->label('Medium Area Cooked WIP Chille'),
                Forms\Components\TextInput::make('raw_WIP_chiller')->label('Medium Area Raw WIP Chiller'),
                Forms\Components\TextInput::make('blast_freezer')->label('High Risk Area- Blast Freezer'),
                Forms\Components\TextInput::make('high_risk_area_chiller')->label('High Risk Area- Chiller'),
                Forms\Components\TextInput::make('outer_cartooning_room')->label('Outer cartooning room'),
                Forms\Components\TextInput::make('factory_lunch_room_fridge')->label('Factory Lunch Room Fridge'),
                Forms\Components\TextInput::make('office_staff_lunch_room_fridge')->label('Office Staff Lunch Room Fridge'), 
            ]);
        }
        return  $form;
    }


    

    public  function table(Table $table): Table
    {

        $id = Route::current()->parameter('record');

        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('date'),
                // Tables\Columns\TextColumn::make('time')->label('Time'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label('Date and Time'),
                Tables\Columns\TextColumn::make('freezer1')->label('Base Area Finished Goods Freezer 1 & 2 F1'),
                Tables\Columns\TextColumn::make('freezer2')->label('Goods Freezer 1 & 2 F2'),
                Tables\Columns\TextColumn::make('cool_room')->label(' Main Cool Room'),
                Tables\Columns\TextColumn::make('raw_material_chiller')->label('Raw Material Chiller'),
                Tables\Columns\TextColumn::make('WIP_freezer')->label('Medium Area WIP Freezer'),
                Tables\Columns\TextColumn::make('blast_chiller_1')->label('Blast Chiller 1'),
                Tables\Columns\TextColumn::make('blast_chiller_2')->label('Blast Chiller 2'),
                Tables\Columns\TextColumn::make('cooked_WIP_chiller')->label('Cooked WIP Chille'),
                Tables\Columns\TextColumn::make('raw_WIP_chiller')->label('Raw WIP Chiller'),
                Tables\Columns\TextColumn::make('blast_freezer')->label('High Risk Area- Blast Freezer'),
                Tables\Columns\TextColumn::make('high_risk_area_chiller')->label('Chiller'),
                Tables\Columns\TextColumn::make('outer_cartooning_room')->label('Outer cartooning room'),
                Tables\Columns\TextColumn::make('factory_lunch_room_fridge')->label('Factory Lunch Room Fridge'),
                Tables\Columns\TextColumn::make('office_staff_lunch_room_fridge')->label('Office Staff Lunch Room Fridge'),
                 // Tables\Columns\TextColumn::make('freezer1')->label('Base Area Finished Goods Freezer 1 & Freezer 2 new F1'),
                // Tables\Columns\TextColumn::make('freezer2')->label('Base Area Finished Goods Freezer 1 & Freezer 2 new F2'),
                // Tables\Columns\TextColumn::make('cool_room')->label('Base Area    Main Cool Room'),
                // Tables\Columns\TextColumn::make('raw_material_chiller')->label('Base Area Raw Material Chiller'),
                // Tables\Columns\TextColumn::make('WIP_freezer')->label('Medium Area WIP Freezer'),
                // Tables\Columns\TextColumn::make('blast_chiller_1')->label('Medium Area Blast Chiller 1'),
                // Tables\Columns\TextColumn::make('blast_chiller_2')->label('Medium Area Blast Chiller 2'),
                // Tables\Columns\TextColumn::make('cooked_WIP_chiller')->label('Medium Area Cooked WIP Chille'),
                // Tables\Columns\TextColumn::make('raw_WIP_chiller')->label('Medium Area Raw WIP Chiller'),
                // Tables\Columns\TextColumn::make('blast_freezer')->label('High Risk Area- Blast Freezer'),
                // Tables\Columns\TextColumn::make('high_risk_area_chiller')->label('High Risk Area- Chiller'),
                // Tables\Columns\TextColumn::make('outer_cartooning_room')->label('Outer cartooning room'),
                // Tables\Columns\TextColumn::make('factory_lunch_room_fridge')->label('Factory Lunch Room Fridge'),
                // Tables\Columns\TextColumn::make('office_staff_lunch_room_fridge')->label('Office Staff Lunch Room Fridge'),
             
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }   
    
    
}
