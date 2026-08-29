<?php

namespace App\Filament\SystemAdmin\Resources\SystemFeedbacks\Pages;

use App\Filament\SystemAdmin\Resources\SystemFeedbacks\SystemFeedbackResource;
use Filament\Resources\Pages\ListRecords;

class ListSystemFeedbacks extends ListRecords
{
    protected static string $resource = SystemFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
