<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
// use Filament\Forms\Form;
use Filament\Forms\Form;
use Filament\Tables\Table;
// use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CheckListItem as CheckListItems;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CheckListItemsResource\Pages;
use App\Filament\Resources\CheckListItemsResource\RelationManagers;

class CheckListItemsResource extends Resource
{
    protected static ?string $model = CheckListItems::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'master';
    protected static ?string $navigationLabel = 'Checklist item master';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                ->required(),
                Select::make('section_id')
                ->searchable()
                ->required()
                ->relationship('section', 'name'),
                Select::make('sub_section_id')
                ->searchable()
                // ->required()
                ->relationship('subSection', 'name'),
                Select::make('sub_sub_section_id')
                ->searchable()
                ->relationship('subSubSection', 'name'),
                Select::make('check_list_id')
                ->searchable()
                ->required()
                ->relationship('checkList', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('section.name')->searchable(),
                TextColumn::make('subSection.name')->searchable(),
                TextColumn::make('subSubSection.name')->searchable(),
                TextColumn::make('checkList.name')->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCheckListItems::route('/'),
            'create' => Pages\CreateCheckListItems::route('/create'),
            'edit' => Pages\EditCheckListItems::route('/{record}/edit'),
        ];
    }
}
