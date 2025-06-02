<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketReplyResource\Pages;
use App\Filament\Resources\TicketReplyResource\RelationManagers;
use App\Models\TicketReply;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Admin;
use App\Models\Ticket;

class TicketReplyResource extends Resource
{
    protected static ?string $model = TicketReply::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('ticket_id')
                ->label('Ticket')
                ->options(Ticket::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\Textarea::make('reply')
                ->required()
                ->rows(5),

            Forms\Components\Select::make('creator_type')
                ->label('Creator Type')
                ->options([
                    'App\Models\Admin' => 'Admin',
                ])
                ->required(),

            Forms\Components\Select::make('creator_id')
                ->label('Creator')
                ->options(function (callable $get) {
                    $type = $get('creator_type');
                    if ($type === 'App\Models\Admin') {
                        return Admin::all()->pluck('name', 'id')->toArray();
                    }
                    return [];
                })
                ->searchable()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),

            Tables\Columns\TextColumn::make('ticket.name')
                ->label('Ticket')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('reply')
                ->limit(50)
                ->wrap(),

            Tables\Columns\TextColumn::make('creator_type')
                ->label('Creator Type')
                ->sortable(),

            Tables\Columns\TextColumn::make('creator_id')
                ->label('Creator ID')
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
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
            'index' => Pages\ListTicketReplies::route('/'),
            'create' => Pages\CreateTicketReply::route('/create'),
            'edit' => Pages\EditTicketReply::route('/{record}/edit'),
        ];
    }
}
