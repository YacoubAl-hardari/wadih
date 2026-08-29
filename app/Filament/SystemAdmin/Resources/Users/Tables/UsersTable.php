<?php

namespace App\Filament\SystemAdmin\Resources\Users\Tables;

use App\Enums\UserType;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->width('60px'),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('role')
                    ->label('النوع')
                    ->badge()
                    ->color(fn (UserType $state) => match ($state) {
                        UserType::ADMIN    => 'danger',
                        UserType::MERCHANT => 'warning',
                        UserType::USER     => 'info',
                    })
                    ->formatStateUsing(fn (UserType $state) => $state->arabicLabel()),
                TextColumn::make('teams_count')
                    ->label('الفرق')
                    ->counts('teams')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('merchants_count')
                    ->label('التجار')
                    ->counts('merchants')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('النوع')
                    ->options(UserType::arabicOptions()),
            ])
            ->actions([
                ViewAction::make()->label('عرض'),
                EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('حذف'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
