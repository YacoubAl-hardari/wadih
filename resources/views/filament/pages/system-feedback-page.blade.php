<x-filament-panels::page>
    @if ($this->isThrottled())
        <div class="p-4 mb-2 rounded-lg bg-warning-50 text-warning-700 dark:bg-warning-950/30 dark:text-warning-400 border border-warning-200 dark:border-warning-800">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium">
                    ⚠️ لقد قمت بإرسال بلاغ أو اقتراح مؤخراً. يرجى الانتظار {{ $this->getThrottleTimeLeft() }} قبل إمكانية إرسال بلاغ آخر.
                </span>
            </div>
        </div>
    @endif

    <x-filament::section>
        <form wire:submit.prevent="submit" class="space-y-4">
            {{ $this->form }}

            <div class="flex justify-end gap-3 mt-4">
                <x-filament::button type="submit" :disabled="$this->isThrottled()">
                    {{ __('إرسال البلاغ / الاقتراح') }}
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>
