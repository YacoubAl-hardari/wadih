<?php

namespace App\Filament\SystemAdmin\Resources\Teams\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class TeamInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('بيانات الفريق')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('name')->label('الاسم'),
                    TextEntry::make('slug')->label('المعرّف')->copyable(),
                    IconEntry::make('is_active')->label('نشط')->boolean(),
                    TextEntry::make('created_at')->label('تاريخ الإنشاء')->dateTime('Y/m/d H:i'),
                    TextEntry::make('members_count')
                        ->label('عدد الأعضاء')
                        ->state(fn ($record) => $record->members()->count()),
                    TextEntry::make('merchants_count')
                        ->label('عدد التجار')
                        ->state(fn ($record) => $record->merchants()->count()),
                ]),
            ]),
            Section::make('الأعضاء')->schema([
                TextEntry::make('members.name')
                    ->label('أسماء الأعضاء')
                    ->listWithLineBreaks()
                    ->placeholder('لا يوجد أعضاء'),
            ]),
        ]);
    }
}
