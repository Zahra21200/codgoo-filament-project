<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingResource\Pages;
use App\Filament\Resources\MeetingResource\RelationManagers;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Client;
use App\Models\AvailableSlot;
use App\Models\Project;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('client_id')
                ->label('Client')
                ->options(Client::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\Select::make('slot_id')
                ->label('Available Slot')
                ->options(AvailableSlot::all()->pluck('label', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('jitsi_url')
                ->label('Jitsi URL')
                ->url()
                ->required(),

            Forms\Components\TimePicker::make('start_time')
                ->label('Start Time')
                ->required(),

            Forms\Components\TimePicker::make('end_time')
                ->label('End Time')
                ->required(),

            Forms\Components\TextInput::make('meeting_name')
                ->label('Meeting Name')
                ->nullable(),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->nullable(),

            Forms\Components\Select::make('status')
                ->options([
                    'Request Sent' => 'Request Sent',
                    'Confirmed' => 'Confirmed',
                    'Completed' => 'Completed',
                    'Canceled' => 'Canceled',
                ])
                ->default('Request Sent')
                ->required(),

            Forms\Components\Select::make('project_id')
                ->label('Project')
                ->options(Project::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),

            Tables\Columns\TextColumn::make('client.name')->label('Client')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('slot.label')->label('Slot')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('jitsi_url')
                ->label('Jitsi URL')
                ->url()
                ->openUrlInNewTab(),

            Tables\Columns\TextColumn::make('start_time')->label('Start Time')->sortable(),

            Tables\Columns\TextColumn::make('end_time')->label('End Time')->sortable(),

            Tables\Columns\TextColumn::make('meeting_name')->label('Meeting Name')->sortable()->searchable(),

            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'primary' => 'Request Sent',
                    'warning' => 'Confirmed',
                    'success' => 'Completed',
                    'danger' => 'Canceled',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('project.name')->label('Project')->sortable()->searchable(),

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
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }
}
