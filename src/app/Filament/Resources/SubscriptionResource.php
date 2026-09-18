<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function getNavigationLabel(): string
    {
        return 'Abunəliklər';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Abunəliklər';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Abunəlik';
    }

    public static function getNavigationSort(): ?int
    {
        return 34;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('plan_name')->label('Plan adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('plan_id')->label('Plan adı')
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload()
                    ->afterStateUpdated(function ($set, $state) {
                        $plan = Plan::find($state);
                        if ($plan) {
                            $set('plan_name', $plan->name);
                        }
                    })->reactive()
                    ->required(),
                Forms\Components\DateTimePicker::make('trial_ends_at')->label('Sınaq bitmə vaxtı'),
                Forms\Components\DateTimePicker::make('ends_at')->label('Bitmə vaxtı'),
                Forms\Components\Select::make('status')->label('Status')
                    ->options(config('site.subscriptionStatuses', [])),
                Forms\Components\Select::make('user_id')->label('İstifadəçi')
                    ->relationship('user', 'name')
                    ->searchable(['id', 'name'])
                    ->getOptionLabelFromRecordUsing(fn(User $user) => "{$user->id}:  {$user->name}")
                    ->preload()
                    ->required(),
                Forms\Components\Toggle::make('renew')->label('Yenilənmə')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plan.name')->label('Plan adı')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('İstifadəçi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('renew')->label('Yenilənmə')->boolean(),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->formatStateUsing(function ($state) {
                        $statuses = config('site.subscriptionStatuses', []);
                        return isset($statuses[$state]) ? $statuses[$state] : $state . ' N/A';
                    }),
                Tables\Columns\TextColumn::make('trial_ends_at')->label('Sınaq bitmə vaxtı')
                    ->dateTime()
                    ->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ends_at')->label('Bitmə vaxtı')
                    ->dateTime()
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
