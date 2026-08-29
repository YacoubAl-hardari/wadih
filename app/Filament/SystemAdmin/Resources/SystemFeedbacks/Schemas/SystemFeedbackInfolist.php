<?php

namespace App\Filament\SystemAdmin\Resources\SystemFeedbacks\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class SystemFeedbackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('تفاصيل البلاغ')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('type')
                        ->label('النوع')
                        ->badge()
                        ->formatStateUsing(fn (string $state) => match ($state) {
                            'bug'        => 'خطأ',
                            'suggestion' => 'اقتراح',
                            default      => 'أخرى',
                        }),
                    TextEntry::make('status')
                        ->label('الحالة')
                        ->badge()
                        ->formatStateUsing(fn ($state) => $state?->label()),
                    TextEntry::make('created_at')
                        ->label('تاريخ الإرسال')
                        ->dateTime('Y/m/d H:i'),
                    TextEntry::make('user.name')
                        ->label('المستخدم'),
                    TextEntry::make('user.email')
                        ->label('البريد الإلكتروني')
                        ->copyable(),
                    TextEntry::make('team.name')
                        ->label('الفريق')
                        ->placeholder('—'),
                ]),
                TextEntry::make('title')
                    ->label('العنوان')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->label('المحتوى')
                    ->columnSpanFull()
                    ->prose(),
                ImageEntry::make('image_path')
                    ->label('الصورة التوضيحية')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->placeholder('لا توجد صورة توضيحية'),
            ]),
            Section::make('ملاحظات المسؤول')->schema([
                TextEntry::make('admin_notes')
                    ->label('الرد / الملاحظات')
                    ->placeholder('لم يتم الرد بعد')
                    ->columnSpanFull()
                    ->prose(),
            ]),
        ]);
    }
}
