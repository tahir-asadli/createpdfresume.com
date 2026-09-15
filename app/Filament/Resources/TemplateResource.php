<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Filament\Resources\TemplateResource\RelationManagers;
use App\Models\Plan;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function getNavigationLabel(): string
    {
        return 'Şablonlar';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Şablonlar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Şablonlar';
    }

    public static function getNavigationSort(): ?int
    {
        return 35;
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('plan_id', 'asc');
    }
    public static function form(Form $form): Form
    {
        $foldersRaw = collect(scandir(resource_path('templates')))->filter(fn($folder) =>
            !in_array($folder, ['.', '..']))->toArray();
        $folders = [];
        $plans = [];
        foreach ($foldersRaw as $k => $v) {
            $folders[$v] = $v;
        }
        foreach (Plan::active()->get() as $k => $v) {
            $plans[$v->id] = $v->name;
        }
        return $form
            ->schema([
                // Forms\Components\TextInput::make('uuid')
                //     ->label('UUID')
                //     ->default(Str::uuid()->toString())
                //     ->value
                //     ->required()
                //     ->hidden()
                //     ->maxLength(1024),
                Forms\Components\TextInput::make('name')->label('Şablon adı')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('colors')->label('Rənglər')->helperText('#000000,#ffffff'),
                Forms\Components\Textarea::make('styles')->label('Styles')->helperText('dark,light')
                    ->maxLength(255),
                // Forms\Components\TextInput::make('folder')
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\Select::make('folder')->label('Qovluq')->required()
                    ->options($folders),
                Forms\Components\Select::make('plan_id')->label('Plan')
                    ->options($plans),
                Forms\Components\TextInput::make('layout')->label('Layout')->helperText("Tailwind class")
                    ->required()
                    ->maxLength(255),
                // Forms\Components\Select::make('plan_id')->nullable()->rules(['nullable'])
                //     ->relationship('plan', 'name'),
                Forms\Components\FileUpload::make('image')->label('Şəkil')->disk('template')->image(),
                Forms\Components\Toggle::make('active')->label('Aktivdir')->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('uuid')
                //     ->label('UUID')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('name')->label('Şablon adı')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')->label('Şəkil')->disk('template')->height(100)
                    ->width(100),
                Tables\Columns\TextColumn::make('plan.name')->label('Plan adı'),
                Tables\Columns\TextColumn::make('folder')->label('Qovluq')
                    ->searchable(),
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
            ])->paginated([100, 200, 'all'])->defaultPaginationPageOption(100)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])->defaultSort('name', 'asc')
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
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
