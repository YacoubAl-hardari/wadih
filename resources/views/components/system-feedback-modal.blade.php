<?php

use Livewire\Component;
use App\Models\SystemFeedback;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public string $type = 'suggestion';
    public string $title = '';
    public string $content = '';

    protected array $rules = [
        'type' => 'required|in:suggestion,bug',
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ];

    public function submit()
    {
        $this->validate();

        SystemFeedback::create([
            'user_id' => Auth::id(),
            'team_id' => Filament::getTenant()?->id,
            'type' => $this->type,
            'title' => $this->title,
            'content' => $this->content,
        ]);

        $this->reset(['title', 'content', 'type']);

        $this->dispatch('close-modal', id: 'feedback-modal');

        Notification::make()
            ->title('تم إرسال اقتراحك أو بلاغك بنجاح!')
            ->success()
            ->send();
    }
};
?>

<x-filament::modal id="feedback-modal" width="lg">
    <x-slot name="heading">
        {{ __('إرسال اقتراح أو إبلاغ عن مشكلة') }}
    </x-slot>

    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">{{ __('نوع الرسالة') }}</label>
            <x-filament::input.wrapper>
                <select wire:model="type" class="fi-in-select block w-full border-none bg-transparent py-1.5 pe-8 ps-3 text-sm text-gray-950 focus:ring-0 dark:text-white dark:bg-gray-900">
                    <option value="suggestion">{{ __('اقتراح جديد') }}</option>
                    <option value="bug">{{ __('تبليغ عن خطأ / مشكلة') }}</option>
                </select>
            </x-filament::input.wrapper>
            @error('type') <span class="text-danger-600 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">{{ __('العنوان') }}</label>
            <x-filament::input.wrapper>
                <x-filament::input
                    type="text"
                    wire:model="title"
                    placeholder="أدخل عنواناً للموضوع"
                    required
                />
            </x-filament::input.wrapper>
            @error('title') <span class="text-danger-600 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">{{ __('التفاصيل') }}</label>
            <x-filament::input.wrapper>
                <textarea
                    wire:model="content"
                    placeholder="يرجى كتابة التفاصيل هنا..."
                    rows="5"
                    class="block w-full border-none bg-transparent py-1.5 px-3 text-sm text-gray-950 focus:ring-0 dark:text-white dark:bg-gray-900"
                    required
                ></textarea>
            </x-filament::input.wrapper>
            @error('content') <span class="text-danger-600 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <x-filament::button
                type="button"
                color="gray"
                x-on:click="$dispatch('close-modal', { id: 'feedback-modal' })" 
            >
                {{ __('إلغاء') }}
            </x-filament::button>

            <x-filament::button
                type="submit"
                color="primary"
            >
                {{ __('إرسال') }}
            </x-filament::button>
        </div>
    </form>
</x-filament::modal>
