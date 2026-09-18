<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectionResource\Pages;
use App\Filament\Resources\SectionResource\RelationManagers;
use App\Filament\Resources\SectionResource\Widgets\WidgetHelperInfo;
use App\Models\Section;
use App\Enums\Section as SectionNames;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    public static function getNavigationLabel(): string
    {
        return 'Bölmələr';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Bölmələr';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Şablonlar';
    }

    public static function getNavigationSort(): ?int
    {
        return 33;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('template_id', 'desc');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Bölmə adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('section')->label('Bölmə maşın adı')
                    ->options(SectionNames::class),
                Forms\Components\Textarea::make('default_widgets')->label('Vidcetlər')
                    ->maxLength(512),
                Forms\Components\Select::make('template_id')->label('Şablon')->required()
                    ->relationship('template', 'name'),
                Forms\Components\Toggle::make('active')->label('Aktivdir')->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Bölmə adı')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('section')->label('Bölmə maşın adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('template.name')->label('Şablon adı')
                    ->numeric()
                    ->sortable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')->label('Aktivdir')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Yaradılma tarixi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->label('Dəyişdirilmə tarixi')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])->paginated([100, 200, 'all'])->defaultPaginationPageOption(100)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])->defaultSort('template.name', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            WidgetHelperInfo::class
        ];
    }
}
