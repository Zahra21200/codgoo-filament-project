<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('milestone_id')
                ->label('Milestone')
                ->options(Milestone::all()->pluck('label', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\Select::make('project_id')
                ->label('Project')
                ->options(Project::all()->pluck('name', 'id')->toArray())
                ->searchable()
                ->required(),

            Forms\Components\Select::make('status')
                ->options([
                    'paid' => 'Paid',
                    'unpaid' => 'Unpaid',
                ])
                ->default('unpaid')
                ->required(),

            Forms\Components\Select::make('payment_method')
                ->options([
                    'bank_transfer' => 'Bank Transfer',
                    'online' => 'Online',
                ])
                ->nullable(),

            Forms\Components\FileUpload::make('payment_proof')
                ->label('Payment Proof')
                ->nullable(),

            Forms\Components\DatePicker::make('due_date')
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->minValue(0)
                ->required(),

            Forms\Components\TextInput::make('reference')
                ->unique(Invoice::class, 'reference', ignoreRecord: true)
                ->nullable(),

            Forms\Components\TextInput::make('order_no')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),

            Tables\Columns\TextColumn::make('milestone.label')->label('Milestone')->sortable()->searchable(),

            Tables\Columns\TextColumn::make('project.name')->label('Project')->sortable()->searchable(),

            Tables\Columns\BadgeColumn::make('status')
                ->enum([
                    'paid' => 'Paid',
                    'unpaid' => 'Unpaid',
                ])
                ->colors([
                    'success' => 'paid',
                    'danger' => 'unpaid',
                ])
                ->sortable(),

            Tables\Columns\TextColumn::make('payment_method')
                ->sortable()
                ->enum([
                    'bank_transfer' => 'Bank Transfer',
                    'online' => 'Online',
                ]),

            Tables\Columns\TextColumn::make('due_date')->date()->sortable(),

            Tables\Columns\TextColumn::make('amount')->money('usd', true)->sortable(),

            Tables\Columns\TextColumn::make('reference')->sortable(),

            Tables\Columns\TextColumn::make('order_no')->sortable(),

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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
