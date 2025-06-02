<?php

namespace App\Filament\Resources\ScreenReviewResource\Pages;

use App\Filament\Resources\ScreenReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScreenReviews extends ListRecords
{
    protected static string $resource = ScreenReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
