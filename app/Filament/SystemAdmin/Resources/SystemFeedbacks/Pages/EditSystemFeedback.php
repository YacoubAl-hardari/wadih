<?php

namespace App\Filament\SystemAdmin\Resources\SystemFeedbacks\Pages;

use App\Filament\SystemAdmin\Resources\SystemFeedbacks\SystemFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSystemFeedback extends EditRecord
{
    protected static string $resource = SystemFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('عرض'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
