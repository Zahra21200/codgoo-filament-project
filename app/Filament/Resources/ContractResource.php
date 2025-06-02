<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\RelationManagers;
use App\Models\Contract;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Project;
use App\Models\Admin;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('project_id')
                ->label('Project')
                ->options(Project::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\Select::make('admin_id')
                ->label('Admin')
                ->options(Admin::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\FileUpload::make('file_path')
                ->label('Contract File')
                ->directory('contracts')
                ->required(),

            Forms\Components\Select::make('status')
                ->options([
                    'not_signed' => 'Not Signed',
                    'signed' => 'Signed',
                ])
                ->default('not_signed')
                ->required(),

            Forms\Components\DateTimePicker::make('signed_at')
                ->label('Signed At')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),

            Tables\Columns\TextColumn::make('project.name')->label('Project')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('admin.name')->label('Admin')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('file_path')
                ->label('Contract File')
                ->formatStateUsing(fn ($state) => basename($state))
                ->url(fn ($record) => asset('storage/' . $record->file_path))
                ->openUrlInNewTab(),

                Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'not_signed',
                    'success' => 'signed',
                ])
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'not_signed' => 'Not Signed',
                    'signed' => 'Signed',
                    default => ucfirst($state),
                })
                ->sortable(),
            
                Tables\Columns\TextColumn::make('signed_at')
                ->dateTime()
                ->sortable(),
            
            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListContracts::route('/'),
            'create' => Pages\CreateContract::route('/create'),
            'edit' => Pages\EditContract::route('/{record}/edit'),
        ];
    }
}
