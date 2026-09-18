<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ViewResource\Pages;
use App\Filament\Resources\ViewResource\RelationManagers;
use App\Models\View;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ViewResource extends Resource
{
    protected static ?string $model = View::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('gid')
                    ->maxLength(255),
                Forms\Components\TextInput::make('uid')
                    ->maxLength(255),
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ip')
                    ->maxLength(255),
                Forms\Components\TextInput::make('method')
                    ->maxLength(255),
                Forms\Components\TextInput::make('uri')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ref')
                    ->maxLength(255),
                Forms\Components\Toggle::make('bot')
                    ->required(),
                Forms\Components\Textarea::make('agent')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country')
                    ->formatStateUsing(function (View $view) {
                        return countryNameByCode($view->country);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip')
                    ->formatStateUsing(function (View $view) {
                        return Str::limit($view->ip, 15);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ref')
                    ->formatStateUsing(function (View $view) {
                        return strip_domain_name($view->ref);
                    })->tooltip(function (View $view) {
                        return $view->ref;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(function (View $view) {
                        return formatDuration($view->duration);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('duration')
                    ->options([
                        'zero' => 'Zero Duration',
                        'positive' => 'Positive Duration',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value']) || $data['value'] === 'all') {
                            return $query; // No filter applied, show all
                        }

                        if ($data['value'] === 'zero') {
                            return $query->where('duration', 0);
                        }

                        if ($data['value'] === 'positive') {
                            return $query->where('duration', '>', 0);
                        }

                        return $query; // Fallback, though ideally handled by above conditions
                    })
                    ->default('positive')
                    ->label('Durations')
            ])->paginated([100, 200, 'all'])->defaultPaginationPageOption(100)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->paginated([10, 25, 50, 100, 'all'])->defaultPaginationPageOption(100);
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
            'index' => Pages\ListViews::route('/'),
            'create' => Pages\CreateView::route('/create'),
            'edit' => Pages\EditView::route('/{record}/edit'),
        ];
    }
}
