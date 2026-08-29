<?php

namespace App\Enums;

enum FeedbackStatus: string
{
    case PENDING   = 'pending';
    case REVIEWED  = 'reviewed';
    case RESOLVED  = 'resolved';

    public function label(): string
    {
        return match ($this) {
            self::PENDING  => 'قيد الانتظار',
            self::REVIEWED => 'تمت المراجعة',
            self::RESOLVED => 'تم الحل',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING  => 'warning',
            self::REVIEWED => 'info',
            self::RESOLVED => 'success',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING  => 'heroicon-o-clock',
            self::REVIEWED => 'heroicon-o-eye',
            self::RESOLVED => 'heroicon-o-check-circle',
        };
    }
}
