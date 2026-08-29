<?php

namespace App\Filament\SystemAdmin\Resources\SystemFeedbacks\Schemas;

use App\Enums\FeedbackStatus;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class SystemFeedbackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('معالجة البلاغ')->schema([
                Select::make('status')
                    ->label('الحالة')
                    ->options(
                        collect(FeedbackStatus::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                            ->toArray()
                    )
                    ->required(),
                Textarea::make('admin_notes')
                    ->label('ملاحظات المسؤول')
                    ->rows(4)
                    ->placeholder('أدخل ردك أو ملاحظاتك هنا...'),
            ]),
        ]);
    }
}
