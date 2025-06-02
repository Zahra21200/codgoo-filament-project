<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AvailableSlotResource\Pages;
use App\Filament\Resources\AvailableSlotResource\RelationManagers;
use App\Models\AvailableSlot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvailableSlotResource extends Resource
{
    protected static ?string $model = AvailableSlot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('date')
                ->required(),

            Forms\Components\TimePicker::make('start_time')
                ->required(),

            Forms\Components\TimePicker::make('end_time')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('created_at')->since(),
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
            'index' => Pages\ListAvailableSlots::route('/'),
            'create' => Pages\CreateAvailableSlot::route('/create'),
            'edit' => Pages\EditAvailableSlot::route('/{record}/edit'),
        ];
    }
}
