<?php

namespace App\Filament\SystemAdmin\Resources\SystemFeedbacks;

use App\Enums\FeedbackStatus;
use App\Filament\SystemAdmin\Resources\SystemFeedbacks\Pages\EditSystemFeedback;
use App\Filament\SystemAdmin\Resources\SystemFeedbacks\Pages\ListSystemFeedbacks;
use App\Filament\SystemAdmin\Resources\SystemFeedbacks\Pages\ViewSystemFeedback;
use App\Filament\SystemAdmin\Resources\SystemFeedbacks\Schemas\SystemFeedbackForm;
use App\Filament\SystemAdmin\Resources\SystemFeedbacks\Schemas\SystemFeedbackInfolist;
use App\Filament\SystemAdmin\Resources\SystemFeedbacks\Tables\SystemFeedbacksTable;
use App\Models\SystemFeedback;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SystemFeedbackResource extends Resource
{
    protected static ?string $model = SystemFeedback::class;

    protected static ?string $slug = 'system-feedbacks';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $navigationLabel = 'البلاغات والاقتراحات';

    protected static ?string $modelLabel = 'بلاغ';

    protected static ?string $pluralModelLabel = 'البلاغات والاقتراحات';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = SystemFeedback::where('status', FeedbackStatus::PENDING->value)->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return SystemFeedbackForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SystemFeedbacksTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SystemFeedbackInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSystemFeedbacks::route('/'),
            'view'  => ViewSystemFeedback::route('/{record}'),
            'edit'  => EditSystemFeedback::route('/{record}/edit'),
        ];
    }
}
