<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Mail\CustomerEmail;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;


    public static function getNavigationLabel(): string
    {
        return 'Müştərilər';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Müştərilər';
    }
    public static function getNavigationSort(): ?int
    {
        return 21;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Linklər';
    }
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'desc');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_sent_at'),
                Forms\Components\TextInput::make('token')
                    ->maxLength(36),
                Forms\Components\Toggle::make('unsubscribed')->label('Unsubscribed')->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_sent_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                // ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('unsubscribed')->label('Unsubscribed')
                    ->boolean(),
                // ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])->paginated([100, 200, 'all'])->defaultPaginationPageOption(100)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('emailen')
                        ->label('Send email, language: English')
                        ->action(function (Collection $records) {
                            $records->each(function (Customer $customer) {
                                if (!$customer->email_sent_at && !$customer->unsubscribed) {
                                    Mail::to($customer->email)->queue(new CustomerEmail($customer));
                                    $customer->update([
                                        'email_sent_at' => now()
                                    ]);
                                }
                            });
                        })
                        ->requiresConfirmation()
                        ->color('info')
                        ->icon('heroicon-o-envelope'),
                    BulkAction::make('emailaz')
                        ->label('Send email, language: Azərbaycanca')
                        ->action(function (Collection $records) {
                            $records->each(function (Customer $customer) {
                                if (!$customer->email_sent_at && !$customer->unsubscribed) {
                                    Mail::to($customer->email)->queue(new CustomerEmail($customer, 'az'));
                                    $customer->update([
                                        'email_sent_at' => now()
                                    ]);
                                }
                            });
                        })
                        ->color('warning')
                        ->icon('heroicon-o-envelope'),
                    BulkAction::make('emailtr')
                        ->label('Send email, language: Turkish')
                        ->action(function (Collection $records) {
                            $records->each(function (Customer $customer) {
                                if (!$customer->email_sent_at && !$customer->unsubscribed) {
                                    Mail::to($customer->email)->queue(new CustomerEmail($customer, 'tr'));
                                    $customer->update([
                                        'email_sent_at' => now()
                                    ]);
                                }
                            });
                        })
                        ->color('success')
                        ->icon('heroicon-o-envelope'),
                    BulkAction::make('addtoken')
                        ->label('Add tokens')
                        ->action(function (Collection $records) {
                            $records->each(function (Customer $customer) {
                                if ($customer->token == null) {
                                    $customer->token = Str::uuid();
                                    $customer->save();
                                }
                            });
                        })
                        ->requiresConfirmation()
                        ->color('info')
                        ->icon('heroicon-o-envelope'),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
