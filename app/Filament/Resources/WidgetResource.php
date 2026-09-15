<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WidgetResource\Pages;
use App\Filament\Resources\WidgetResource\RelationManagers;
use App\Models\Widget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WidgetResource extends Resource
{
    protected static ?string $model = Widget::class;


    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';

    public static function getNavigationLabel(): string
    {
        return 'Vidcetlər';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Vidcetlər';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Şablonlar';
    }

    public static function getNavigationSort(): ?int
    {
        return 35;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->label('Vidcet adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')->label('Vidcet Maşın adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('multiple')->label('Təkrarlanan')
                    ->required(),
                Forms\Components\Toggle::make('active')->label('Aktiv')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Vidcet adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Vidcet Maşın adı')
                    ->searchable(),
                Tables\Columns\IconColumn::make('multiple')->label('Təkrarlanan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('active')->label('Aktiv')
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])->defaultSort('name', 'asc')->paginated([100, 200, 'all'])->defaultPaginationPageOption(100)
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
            'index' => Pages\ListWidgets::route('/'),
            'create' => Pages\CreateWidget::route('/create'),
            'edit' => Pages\EditWidget::route('/{record}/edit'),
        ];
    }
}
