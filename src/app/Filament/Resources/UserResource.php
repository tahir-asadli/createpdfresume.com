<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Blacklist;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Filament\Tables\Actions\BulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return 'İstifadəçilər';
    }

    public static function getPluralModelLabel(): string
    {
        return 'İstifadəçilər';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Ad, Soyad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')->label('E-poçt')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ip')->label('IP'),
                Forms\Components\DateTimePicker::make('email_verified_at')->label('Təsdiqlənmə tarixi'),
                Forms\Components\TextInput::make('password')->label('Şifrə')
                    ->password()->revealable()
                    ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                    ->required(fn(string $context) => $context === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Ad, Soyad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip')->label('Ip')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')->label('E-poçt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->label('Təsdiqlənmə tarixi')
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
            ])->paginated([100, 200, 'all'])->defaultPaginationPageOption(100)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('add-to-blacklist')
                        ->label('Add to blacklist')
                        ->action(function (Collection $records) {
                            $records->each(function (User $user) {
                                if ($user->ip) {
                                    $blacklisted = Blacklist::where('ip', $user->ip)->first();
                                    if (!$blacklisted) {
                                        Blacklist::create([
                                            'ip' => $user->ip
                                        ]);
                                    }
                                }
                            });
                        })
                        ->requiresConfirmation()
                        ->color('warning')
                        ->icon('heroicon-o-user-plus'),
                    BulkAction::make('add-to-blacklist-delete')
                        ->label('Add to blacklist & delete')
                        ->action(function (Collection $records) {
                            $records->each(function (User $user) {
                                if ($user->ip) {
                                    $blacklisted = Blacklist::where('ip', $user->ip)->first();
                                    if (!$blacklisted) {
                                        Blacklist::create([
                                            'ip' => $user->ip
                                        ]);
                                    }
                                    $user->delete();
                                }
                            });
                        })
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-o-user-minus'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
