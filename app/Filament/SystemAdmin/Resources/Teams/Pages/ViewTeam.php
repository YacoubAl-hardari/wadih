<?php

namespace App\Filament\SystemAdmin\Resources\Teams\Pages;

use App\Filament\SystemAdmin\Resources\Teams\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
        ];
    }
}
