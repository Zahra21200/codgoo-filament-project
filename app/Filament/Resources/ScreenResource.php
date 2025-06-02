<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScreenResource\Pages;
use App\Filament\Resources\ScreenResource\RelationManagers;
use App\Models\Screen;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScreenResource extends Resource
{
    protected static ?string $model = Screen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->maxLength(255),
                Select::make('task_id')
                    ->label('Task')
                    ->relationship('task', 'label')
                    ->searchable()
                    ->required(),
                Toggle::make('dev_mode')->label('Development Mode'),
                Toggle::make('implemented')->label('Implemented'),
                Toggle::make('integrated')->label('Integrated'),
                TextInput::make('screen_code')->label('Screen Code')->maxLength(255),
                TextInput::make('estimated_hours')->numeric()->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('task.label')
                    ->label('Task')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('dev_mode')
                    ->boolean()
                    ->label('Development Mode')
                    ->sortable(),

                Tables\Columns\IconColumn::make('implemented')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\IconColumn::make('integrated')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('screen_code')
                    ->label('Screen Code')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estimated_hours')
                    ->label('Estimated Hours')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('dev_mode')
                    ->label('Development Mode')
                    ->query(fn ($query) => $query->where('dev_mode', true)),

                Tables\Filters\Filter::make('implemented')
                    ->query(fn ($query) => $query->where('implemented', true)),

                Tables\Filters\Filter::make('integrated')
                    ->query(fn ($query) => $query->where('integrated', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListScreens::route('/'),
            'create' => Pages\CreateScreen::route('/create'),
            'edit' => Pages\EditScreen::route('/{record}/edit'),
        ];
    }
}
