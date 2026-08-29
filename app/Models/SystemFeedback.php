<?php

namespace App\Models;

use App\Enums\FeedbackStatus;
use App\Enums\UserType;
use App\Models\User;
use Filament\Notifications\Notification;
use App\Filament\SystemAdmin\Resources\SystemFeedbacks\SystemFeedbackResource;
use App\Filament\Pages\SystemFeedbackPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemFeedback extends Model
{
    protected $table = 'system_feedbacks';

    protected $fillable = [
        'user_id',
        'team_id',
        'type',
        'title',
        'content',
        'image_path',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'status' => FeedbackStatus::class,
    ];

    protected static function booted()
    {
        static::created(function (SystemFeedback $feedback) {
            $admins = User::where('role', UserType::ADMIN->value)->get();
            
            if ($admins->isNotEmpty()) {
                $url = null;
                try {
                    $url = SystemFeedbackResource::getUrl('view', ['record' => $feedback]);
                } catch (\Throwable $e) {
                    $url = url('/system-admin-monitoring/system-feedbacks/' . $feedback->id);
                }

                Notification::make()
                    ->title('بلاغ جديد في النظام')
                    ->body('قام ' . ($feedback->user?->name ?? 'مستخدم') . ' بإرسال بلاغ جديد: ' . $feedback->title)
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->actions([
                        \Filament\Actions\Action::make('view')
                            ->label('عرض التفاصيل')
                            ->url($url),
                    ])
                    ->sendToDatabase($admins);
            }
        });

        static::updated(function (SystemFeedback $feedback) {
            if ($feedback->wasChanged(['status', 'admin_notes'])) {
                $user = $feedback->user;
                if ($user) {
                    $url = null;
                    try {
                        $url = SystemFeedbackPage::getUrl(['tenant' => $feedback->team?->slug], panel: 'admin');
                    } catch (\Throwable $e) {
                        $url = $feedback->team?->slug ? url('/admin/' . $feedback->team->slug . '/feedback') : url('/admin/feedback');
                    }

                    $body = 'تم تحديث حالة بلاغك إلى (' . $feedback->status->label() . ').';
                    if ($feedback->wasChanged('admin_notes') && !empty($feedback->admin_notes)) {
                        $body .= ' ملاحظة المسؤول: ' . str($feedback->admin_notes)->limit(50);
                    }

                    Notification::make()
                        ->title('تحديث بشأن بلاغك: ' . $feedback->title)
                        ->body($body)
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->actions([
                            \Filament\Actions\Action::make('view')
                                ->label('الانتقال للبلاغات')
                                ->url($url),
                        ])
                        ->sendToDatabase($user);
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
