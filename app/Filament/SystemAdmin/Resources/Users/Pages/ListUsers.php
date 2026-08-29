<?php

namespace App\Filament\SystemAdmin\Resources\Users\Pages;

use App\Filament\SystemAdmin\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة مستخدم'),
        ];
    }
}
