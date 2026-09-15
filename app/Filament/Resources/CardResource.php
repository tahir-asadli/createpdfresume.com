<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CardResource\Pages;
use App\Filament\Resources\CardResource\RelationManagers;
use App\Models\Card;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CardResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function getNavigationLabel(): string
    {
        return 'Kartlar';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Kartlar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Abunəlik';
    }

    public static function getNavigationSort(): ?int
    {
        return 21;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make(
                    'Məlumatlar'
                )->schema([
                            Forms\Components\TextInput::make('card_id')->label('Kart Nömrəsi')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\DatePicker::make('expires_at')->label('Bitmə tarixi'),

                            Forms\Components\Select::make('user_id')->label('İstifadəçi')
                                ->relationship('user', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                        ])->columns(3),
                Forms\Components\Card::make(
                    'Təsdiq və Aktiv'
                )->schema([
                            Forms\Components\Toggle::make('verified')->label('Təsdqilənib')
                                ->required(),
                            Forms\Components\Toggle::make('active')->label('Aktivdir')
                                ->required(),
                        ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_id')->label('Kart Nömrəsi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')->label('Aktivdir')
                    ->boolean(),
                Tables\Columns\IconColumn::make('verified')->label('Təsdqilənib')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expires_at')->label('Bitmə tarixi')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('İstifadəçi')
                    ->numeric()
                    ->sortable(),
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
            ])->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListCards::route('/'),
            'create' => Pages\CreateCard::route('/create'),
            'edit' => Pages\EditCard::route('/{record}/edit'),
        ];
    }
}
