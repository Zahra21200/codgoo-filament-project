<?php

namespace App\Filament\Resources\ScreenReviewResource\Pages;

use App\Filament\Resources\ScreenReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScreenReview extends EditRecord
{
    protected static string $resource = ScreenReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
