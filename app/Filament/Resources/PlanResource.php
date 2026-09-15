<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function getNavigationLabel(): string
    {
        return 'Planlar';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Planlar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Abunəlik';
    }

    public static function getNavigationSort(): ?int
    {
        return 22;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Card::make(
                    'Ad, Qiymət və məlumat'
                )->schema([
                            Forms\Components\TextInput::make('name')->label('Planın adı')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('slug')->label('URL qısa adı')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('description')->label('Təsvir')
                                ->maxLength(1024),
                            Forms\Components\TextInput::make('price')->label('Qiymət')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->prefix('qəpik'),
                            Forms\Components\TextInput::make('usd')->label('USD')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->prefix('cent'),
                            Forms\Components\Toggle::make('active')->label('Aktivdir')
                                ->required(),
                        ])->columns(4),
                Forms\Components\Card::make(
                    'Plan daxil olanlar - yazı ilə'
                )->schema([
                            Forms\Components\Textarea::make('features')->rows(5)->label('İmkanlar')
                                ->maxLength(1024),
                        ])->columns(1),
                Forms\Components\Card::make(
                    'Limitlər'
                )->schema([
                            Forms\Components\TextInput::make('day_count')->label('Gün limiti')
                                ->required()
                                ->numeric()
                                ->default(null),
                            Forms\Components\TextInput::make('resume_count')->label('CV limiti')
                                ->required()
                                ->numeric()
                                ->default(null),
                            Forms\Components\TextInput::make('generation_count')->label('PDF limiti')
                                ->required()
                                ->numeric()
                                ->default(null),
                            Forms\Components\TextInput::make('page_count')->label('Səhifə limiti')
                                ->required()
                                ->numeric()
                                ->default(null),
                        ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Planın adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('URL qısa adı')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Təsvir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')->label('Qiymət')
                    ->money(divideBy: 100, locale: 'az', currency: 'AZN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('usd')->label('USD')
                    ->money(divideBy: 100, locale: 'en', currency: 'USD')
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
