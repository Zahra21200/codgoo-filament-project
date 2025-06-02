<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MilestoneResource\Pages;
use App\Filament\Resources\MilestoneResource\RelationManagers;
use App\Models\Milestone;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('project_id')
                ->label('Project')
                ->options(Project::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('label')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->maxLength(65535),

            Forms\Components\TextInput::make('cost')
                ->numeric()
                ->minValue(0)
                ->required(),

            Forms\Components\TextInput::make('period')
                ->label('Period (days)')
                ->numeric()
                ->minValue(1)
                ->required(),

            Forms\Components\DatePicker::make('start_date')
                ->required(false),

            Forms\Components\DatePicker::make('end_date')
                ->disabled(),

            Forms\Components\Select::make('status')
                ->options([
                    'not_started' => 'Not Started',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ])
                ->default('not_started')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),

            Tables\Columns\TextColumn::make('label')->searchable()->sortable(),

            Tables\Columns\TextColumn::make('project.name')->label('Project')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('cost')->money('usd', true)->sortable(),

            Tables\Columns\TextColumn::make('period')->label('Period (days)')->sortable(),

            Tables\Columns\TextColumn::make('start_date')->date()->sortable(),

            Tables\Columns\TextColumn::make('end_date')->date()->sortable(),

            Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'not_started' => 'Not Started',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'not_started',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'canceled',
                    ])
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
            'index' => Pages\ListMilestones::route('/'),
            'create' => Pages\CreateMilestone::route('/create'),
            'edit' => Pages\EditMilestone::route('/{record}/edit'),
        ];
    }
}
