<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Client;
use App\Models\Department;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Ticket Name')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('department_id')
                ->label('Department')
                ->options(Department::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\Select::make('priority')
                ->options([
                    'High' => 'High',
                    'Medium' => 'Medium',
                    'Low' => 'Low',
                ])
                ->default('Low')
                ->required(),

            Forms\Components\Textarea::make('description')
                ->required(),

            Forms\Components\Select::make('created_by')
                ->label('Client')
                ->options(Client::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\FileUpload::make('attachment')
                ->label('Attachment')
                ->nullable(),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'open' => 'Open',
                    'closed' => 'Closed',
                    'answered' => 'Answered',
                ])
                ->default('pending')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),

            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),

            Tables\Columns\TextColumn::make('department.name')->label('Department')->sortable()->searchable(),

            Tables\Columns\BadgeColumn::make('priority')
                ->colors([
                    'danger' => 'High',
                    'warning' => 'Medium',
                    'success' => 'Low',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('created_by')
                ->label('Client ID')
                ->sortable(),

                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'open' => 'info',
                    'answered' => 'warning',
                    'closed' => 'success',
                    default => 'secondary',
                })
                ->formatStateUsing(fn (string $state): string => ucfirst($state))
                ->sortable(),
            

            Tables\Columns\IconColumn::make('attachment')
                ->label('Has Attachment')
                ->boolean()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
