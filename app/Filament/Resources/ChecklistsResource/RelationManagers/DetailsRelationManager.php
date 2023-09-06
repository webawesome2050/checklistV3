<?php

namespace App\Filament\Resources\ChecklistsResource\RelationManagers;
 
use Filament\Forms;
use Filament\Tables;

use Filament\Forms\Form;
use Filament\Tables\Table;

// use Filament\Resources\Form;
// use Filament\Resources\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $title = 'Australian International Foods - Form Detail';

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('issued_at')->required(),  
                Forms\Components\TextInput::make('issues_version')
                    ->required()
                    ->maxLength(255),
                TimePicker::make('start_time'),             
                TimePicker::make('finish_time'),   
                Forms\Components\TextInput::make('inspected_by'), 
                Forms\Components\TextInput::make('approved_by')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('date_of_inspection')->required(),                
            ]);
    }

    public  function table(Table $table): Table
    {
        return $table
            ->columns([
              //  Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('issued_at'),
                Tables\Columns\TextColumn::make('issued_version'),
                Tables\Columns\TextColumn::make('date_of_inspection'),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('finish_time'),
                Tables\Columns\TextColumn::make('inspected_by'),
                Tables\Columns\TextColumn::make('approved_by'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
               // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
