<?php

namespace App\Filament\SystemAdmin\Resources\Users\Schemas;

use App\Enums\UserType;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('الاسم')
                        ->required(),
                    TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->required(),
                    Select::make('role')
                        ->label('النوع')
                        ->options(UserType::arabicOptions())
                        ->required(),
                ]),
            ]),
        ]);
    }
}
