<?php

namespace App\Filament\SystemAdmin\Resources\Teams\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('اسم الفريق')
                        ->required(),
                    TextInput::make('slug')
                        ->label('المعرّف (Slug)')
                        ->required(),
                    Toggle::make('is_active')
                        ->label('نشط')
                        ->default(true),
                ]),
            ]),
        ]);
    }
}
