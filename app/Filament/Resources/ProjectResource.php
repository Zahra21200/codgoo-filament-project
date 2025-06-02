<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['created_by_type'] = \App\Models\Client::class;
    //     return $data;
    // }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->maxLength(65535),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->minValue(0)
                ->required(false),

            Forms\Components\Textarea::make('note'),

            Forms\Components\Select::make('status')
                ->options([
                    'requested' => 'Requested',
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    'reject' => 'Reject',
                ])
                ->default('requested')
                ->required(),

            Forms\Components\Select::make('product_id')
                ->label('Product')
                ->options(Product::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(false),

            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->options(Category::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),



            Forms\Components\MultiSelect::make('addons')
                ->relationship('addons', 'name')
                ->preload(),

                // Forms\Components\Select::make('created_by_id')
                // ->label('Client')
                // ->options(\App\Models\Client::all()->pluck('name', 'id')->toArray())
                // ->searchable()
                // ->required(),
            
            // Forms\Components\TextInput::make('created_by_type')
            //     ->disabled(),

            // Forms\Components\TextInput::make('created_by_id')
            //     ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable(),

                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'requested' => 'gray',
                    'ongoing' => 'info',
                    'completed' => 'success',
                    'reject' => 'danger',
                    default => 'secondary',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'requested' => 'Requested',
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    'reject' => 'Rejected',
                    default => ucfirst($state),
                })
                ->sortable(),
            

            Tables\Columns\TextColumn::make('price')
                ->money('usd', true)
                ->sortable(),

            Tables\Columns\TextColumn::make('product.name')
                ->label('Product')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
