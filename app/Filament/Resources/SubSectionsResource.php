<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use App\Models\SubSection as SubSections;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubSectionsResource\Pages;
use App\Filament\Resources\SubSectionsResource\RelationManagers;
use Filament\Forms\Components\Select;

class SubSectionsResource extends Resource
{
    protected static ?string $model = SubSections::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Machine Name / GMP QC';

    
    protected static ?string $modelLabel = 'Machine Name / GMP QC';
    protected static ?string $pluralModelLabel = 'Machine Name / GMP QC';
    protected static ?string $breadcrumb = 'Machine Name / GMP QC';

    public static function form(Form $form): Form
    {
      

        return $form
        ->schema([
            //
            Select::make('section_id')
            ->label('Area')
            ->searchable()
            ->required()
            ->preload()
            ->native(false)
            ->relationship('section', 'name'),
            TextInput::make('name')
            ->required(),
           
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('section.name')->searchable()

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
            RelationManagers\SubSectionItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubSections::route('/'),
            'create' => Pages\CreateSubSections::route('/create'),
            'edit' => Pages\EditSubSections::route('/{record}/edit'),
        ];
    }
}
