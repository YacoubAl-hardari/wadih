<?php

namespace App\Filament\SystemAdmin\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('البيانات الأساسية')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('name')->label('الاسم'),
                    TextEntry::make('email')->label('البريد الإلكتروني')->copyable(),
                    TextEntry::make('role')
                        ->label('النوع')
                        ->badge()
                        ->formatStateUsing(fn ($state) => $state?->arabicLabel()),
                    TextEntry::make('phone')->label('الهاتف')->placeholder('—'),
                    TextEntry::make('created_at')->label('تاريخ التسجيل')->dateTime('Y/m/d H:i'),
                    TextEntry::make('email_verified_at')->label('التحقق من البريد')->dateTime('Y/m/d')->placeholder('غير محقق'),
                ]),
            ]),
            Section::make('بيانات الأعمال (للتجار)')->schema([
                Grid::make(2)->schema([
                    TextEntry::make('business_name')->label('اسم النشاط التجاري')->placeholder('—'),
                    TextEntry::make('business_activity')->label('نوع النشاط')->placeholder('—'),
                    TextEntry::make('business_location')->label('الموقع')->placeholder('—'),
                    TextEntry::make('tax_number')->label('الرقم الضريبي')->placeholder('—'),
                ]),
            ])->collapsed(),
            Section::make('الفرق المرتبطة')->schema([
                TextEntry::make('teams.name')
                    ->label('الفرق')
                    ->listWithLineBreaks()
                    ->placeholder('لا توجد فرق'),
            ]),
        ]);
    }
}
