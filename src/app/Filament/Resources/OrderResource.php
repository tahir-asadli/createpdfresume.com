<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationLabel(): string
    {
        return 'Sifarişlər';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Sifarişlər';
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
                    'İstifadəçi və Status'
                )->schema([
                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')->label('İstifadəçi')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('status')->label('Status')
                                ->options(config('site.orderStatuses', [])),
                        ])->columns(2),
                Forms\Components\Card::make(
                    'Sifariş məlumatları'
                )->schema([
                            Forms\Components\Textarea::make('data')->readOnly(true),
                        ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('İstifadəçi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan_name')->label('Planın adı'),
                Tables\Columns\TextColumn::make('amount')->label('Qiymət')
                    ->money(divideBy: 100, locale: 'az', currency: 'AZN')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->formatStateUsing(function ($state) {
                        $statuses = config('site.orderStatuses', []);
                        return isset($statuses[$state]) ? $statuses[$state] : $state . ' N/A';
                    }),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
