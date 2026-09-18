<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResumeResource\Pages;
use App\Filament\Resources\ResumeResource\RelationManagers;
use App\Models\Resume;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResumeResource extends Resource
{
    protected static ?string $model = Resume::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function getNavigationLabel(): string
    {
        return 'CV-lər';
    }

    public static function getPluralModelLabel(): string
    {
        return 'CV-lər';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Şablonlar';
    }

    public static function getNavigationSort(): ?int
    {
        return 33;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('uuid')
                    ->label('UUID')->readOnly(true)
                    ->maxLength(36),
                Forms\Components\TextInput::make('name')->label('CV adı')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('style')->label('Style')
                    ->required()
                    ->maxLength(1024),
                Forms\Components\Select::make('template_id')->label('Şablon')
                    ->relationship('template', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('user_id')->label('İstifadəçi')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')->label('CV adı')
                    ->url(fn(Resume $resume) => $resume->uuid ? $resume->getViewURL() : null, true)
                    ->color(fn(Resume $resume) => $resume->uuid ? 'primary' : '')
                    ->icon(fn(Resume $resume) => $resume->uuid ? 'heroicon-o-eye' : '')
                    ->searchable(),
                Tables\Columns\TextColumn::make('template.name')->label('Şablon')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('İstifadəçi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.ip')->label('Ip')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Yaradılma tarixi')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListResumes::route('/'),
            'create' => Pages\CreateResume::route('/create'),
            'edit' => Pages\EditResume::route('/{record}/edit'),
        ];
    }
}
