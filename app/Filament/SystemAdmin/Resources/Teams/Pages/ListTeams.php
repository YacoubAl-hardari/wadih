<?php

namespace App\Filament\SystemAdmin\Resources\Teams\Pages;

use App\Filament\SystemAdmin\Resources\Teams\TeamResource;
use Filament\Resources\Pages\ListRecords;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
