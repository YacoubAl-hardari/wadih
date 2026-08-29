<?php

namespace App\Filament\SystemAdmin\Resources\Teams;

use App\Filament\SystemAdmin\Resources\Teams\Pages\EditTeam;
use App\Filament\SystemAdmin\Resources\Teams\Pages\ListTeams;
use App\Filament\SystemAdmin\Resources\Teams\Pages\ViewTeam;
use App\Filament\SystemAdmin\Resources\Teams\Schemas\TeamForm;
use App\Filament\SystemAdmin\Resources\Teams\Schemas\TeamInfolist;
use App\Filament\SystemAdmin\Resources\Teams\Tables\TeamsTable;
use App\Models\Team;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationLabel = 'الفرق';

    protected static ?string $modelLabel = 'فريق';

    protected static ?string $pluralModelLabel = 'الفرق';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TeamForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeamInfolist::configure($schema);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeams::route('/'),
            'view'  => ViewTeam::route('/{record}'),
            'edit'  => EditTeam::route('/{record}/edit'),
        ];
    }
}
