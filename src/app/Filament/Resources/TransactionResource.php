<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationLabel(): string
    {
        return 'Transaksiyalar';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Transaksiyalar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Abunəlik';
    }

    public static function getNavigationSort(): ?int
    {
        return 24;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('api_endpoint')->label('Epoint url')
                    ->maxLength(255),
                Forms\Components\Select::make('order_id')->label('Sifariş Nömrəsi')
                    ->relationship('order', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')->label('İstifadəçi')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('card_id')->label('Kart Nömrəsi')
                    ->relationship('card', 'card_id')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('subscription_id')->label('Abunəlik Nömrəsi')
                    ->relationship('subscription', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')->label('Status')
                    ->options(config('site.transactionStatuses', [])),
                Forms\Components\TextInput::make('code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('message')
                    ->maxLength(255),
                Forms\Components\TextInput::make('transaction')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_transaction')
                    ->maxLength(255),
                Forms\Components\Textarea::make('bank_response')->rows(10)
                    ->maxLength(1024)->columnSpan(2),
                Forms\Components\TextInput::make('card_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('card_mask')
                    ->maxLength(255),
                Forms\Components\TextInput::make('operation_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rrn')
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->maxLength(255),
                Forms\Components\TextInput::make('other_attr'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('api_endpoint')->label('Epoint url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order_id')->label('Sifariş Nömrəsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('İstifadəçi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card.card_id')->label('Kart Nömrəsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subscription_id')->label('Abunəlik Nömrəsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')->label('Qiymət')
                    ->money(divideBy: 100, locale: 'az', currency: 'AZN')
                    ->searchable(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
