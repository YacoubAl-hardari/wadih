<?php

namespace App\Filament\SystemAdmin\Resources\SystemFeedbacks\Tables;

use App\Enums\FeedbackStatus;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;

class SystemFeedbacksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->width('60px'),
                TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'bug'        => 'danger',
                        'suggestion' => 'info',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'bug'        => 'خطأ',
                        'suggestion' => 'اقتراح',
                        default      => 'أخرى',
                    }),
                TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable(),
                TextColumn::make('team.name')
                    ->label('الفريق')
                    ->placeholder('—'),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn (FeedbackStatus $state) => $state->color())
                    ->icon(fn (FeedbackStatus $state) => $state->icon())
                    ->formatStateUsing(fn (FeedbackStatus $state) => $state->label()),
                TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options(
                        collect(FeedbackStatus::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                            ->toArray()
                    ),
                SelectFilter::make('type')
                    ->label('النوع')
                    ->options([
                        'bug'        => 'خطأ',
                        'suggestion' => 'اقتراح',
                        'other'      => 'أخرى',
                    ]),
            ])
            ->actions([
                ViewAction::make()->label('عرض'),
                EditAction::make()->label('معالجة'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
