<?php

namespace App\Filament\Resources\AvailableSlotResource\Pages;

use App\Filament\Resources\AvailableSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvailableSlots extends ListRecords
{
    protected static string $resource = AvailableSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
