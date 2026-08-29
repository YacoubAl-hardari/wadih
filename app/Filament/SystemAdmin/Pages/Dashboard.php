<?php

namespace App\Filament\SystemAdmin\Pages;

use App\Enums\FeedbackStatus;
use App\Enums\UserType;
use App\Models\SystemFeedback;
use App\Models\Team;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected string $view = 'filament.system-admin.pages.dashboard';

    protected static ?string $title = 'لوحة المراقبة';

    protected static ?string $navigationLabel = 'الرئيسية';

    protected static ?int $navigationSort = -2;

    public array $stats = [];

    public function mount(): void
    {
        $this->stats = $this->getStats();
    }

    protected function getStats(): array
    {
        return [
            'total_users'          => User::where('role', UserType::USER->value)->count(),
            'total_merchants'      => User::where('role', UserType::MERCHANT->value)->count(),
            'total_admins'         => User::where('role', UserType::ADMIN->value)->count(),
            'total_teams'          => Team::count(),
            'active_teams'         => Team::where('is_active', true)->count(),
            'inactive_teams'       => Team::where('is_active', false)->count(),
            'pending_feedbacks'    => SystemFeedback::where('status', FeedbackStatus::PENDING->value)->count(),
            'total_feedbacks'      => SystemFeedback::count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
        ];
    }
}
