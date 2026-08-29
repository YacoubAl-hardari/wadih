<?php

namespace App\Filament\SystemAdmin\Resources\SystemFeedbacks\Pages;

use App\Filament\SystemAdmin\Resources\SystemFeedbacks\SystemFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSystemFeedback extends ViewRecord
{
    protected static string $resource = SystemFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('معالجة / الرد'),
        ];
    }
}
