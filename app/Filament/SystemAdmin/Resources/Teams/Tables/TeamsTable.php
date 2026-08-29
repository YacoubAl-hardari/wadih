<?php

namespace App\Filament\SystemAdmin\Resources\Teams\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class TeamsTable
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
                    ->label('اسم الفريق')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('المعرّف')
                    ->copyable()
                    ->color('gray'),
                TextColumn::make('members_count')
                    ->label('الأعضاء')
                    ->counts('members')
                    ->badge()
                    ->color('indigo'),
                TextColumn::make('merchants_count')
                    ->label('التجار')
                    ->counts('merchants')
                    ->badge()
                    ->color('amber'),
                IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y/m/d')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->trueLabel('النشطة فقط')
                    ->falseLabel('المعطلة فقط'),
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
