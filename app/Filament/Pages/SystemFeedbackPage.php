<?php

namespace App\Filament\Pages;

use App\Models\SystemFeedback;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use BackedEnum;

class SystemFeedbackPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $slug = 'feedback';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $title = 'اقتراحات أو تبليغ عن أخطاء';

    protected static ?string $navigationLabel = 'اقتراحات أو تبليغ عن أخطاء';

    protected string $view = 'filament.pages.system-feedback-page';

    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function isThrottled(): bool
    {
        return SystemFeedback::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHour())
            ->exists();
    }

    public function getThrottleTimeLeft(): string
    {
        $lastFeedback = SystemFeedback::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHour())
            ->latest()
            ->first();

        if (!$lastFeedback) {
            return '';
        }

        $minutes = (int) ceil(now()->diffInSeconds($lastFeedback->created_at->addHour()) / 60);
        return "{$minutes} دقيقة";
    }

    public function form(Schema $schema): Schema
    {
        $isThrottled = $this->isThrottled();

        return $schema
            ->schema([
                Section::make('إرسال اقتراح أو الإبلاغ عن مشكلة / خطأ')
                    ->description('يسعدنا تلقي اقتراحاتكم لتطوير النظام أو بلاغاتكم عن أي أخطاء تواجهونها.')
                    ->schema([
                        Select::make('type')
                            ->label('نوع الرسالة')
                            ->options([
                                'suggestion' => 'اقتراح جديد',
                                'bug' => 'تبليغ عن خطأ / مشكلة',
                            ])
                            ->required()
                            ->native(false)
                            ->disabled($isThrottled),
                        TextInput::make('title')
                            ->label('العنوان')
                            ->placeholder('أدخل عنواناً للموضوع')
                            ->required()
                            ->maxLength(50)
                            ->disabled($isThrottled),
                        Textarea::make('content')
                            ->label('التفاصيل')
                            ->placeholder('يرجى كتابة التفاصيل هنا...')
                            ->rows(5)
                            ->maxLength(500)
                            ->required()
                            ->disabled($isThrottled),
                        FileUpload::make('image_path')
                            ->label('صورة توضيحية (اختياري)')
                            ->image()
                            ->maxSize(3072)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->directory('system-feedbacks')
                            ->visibility('public')
                            ->disabled($isThrottled),
                    ])
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        if ($this->isThrottled()) {
            Notification::make()
                ->title('لقد قمت بإرسال بلاغ/اقتراح مؤخراً')
                ->body('يرجى الانتظار قبل إرسال بلاغ آخر.')
                ->danger()
                ->send();
            return;
        }

        $state = $this->form->getState();

        SystemFeedback::create([
            'user_id' => Auth::id(),
            'team_id' => Filament::getTenant()?->id,
            'type' => $state['type'],
            'title' => $state['title'],
            'content' => $state['content'],
            'image_path' => $state['image_path'] ?? null,
        ]);

        $this->form->fill();

        Notification::make()
            ->title('تم إرسال اقتراحك أو بلاغك بنجاح!')
            ->success()
            ->send();
    }
}
