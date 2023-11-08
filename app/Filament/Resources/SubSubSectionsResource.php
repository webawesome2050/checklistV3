<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Models\SubSubSection as SubSubSections;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubSubSectionsResource\Pages;
use App\Filament\Resources\SubSubSectionsResource\RelationManagers;

class SubSubSectionsResource extends Resource
{
    protected static ?string $model = SubSubSections::class;

    // protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Machine Sections';

    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Machine Sections';
    protected static ?string $pluralModelLabel = 'Machine Sections';
    protected static ?string $breadcrumb = 'Machine Sections';

    protected static ?string $slug = 'machinery-sections';

   

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('sub_section_id')
                ->searchable()
                ->required()
                ->preload()
                ->relationship('subSection', 'name'),
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
                TextColumn::make('subSection.name')->searchable(),
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
            'index' => Pages\ListSubSubSections::route('/'),
            'create' => Pages\CreateSubSubSections::route('/create'),
            'edit' => Pages\EditSubSubSections::route('/{record}/edit'),
        ];
    }
}
