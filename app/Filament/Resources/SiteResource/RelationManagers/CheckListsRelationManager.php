<?php

namespace App\Filament\Resources\SiteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CheckListsRelationManager extends RelationManager
{
    protected static string $relationship = 'checkLists';
    protected static ?string $title = 'QC Forms';
    protected static ?string $recordTitleAttribute = 'name';

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public  function table(Table $table): Table
    {
        // dd($table);
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->label('Created on'),
                // Tables\Columns\TextColumn::make('Qc Form Link')->label('QC Form Link')
                // ->sortable()
                // ->searchable(isIndividual: true)
                // ->description('Continue your auditing')
                // ->url('/admin/check-lists/1/edit', true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()->label('Continue'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make()->label('Continue'),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
