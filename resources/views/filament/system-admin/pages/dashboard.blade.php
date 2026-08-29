<x-filament-panels::page>
    {{-- Welcome Header Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-purple-800 p-8 shadow-lg text-white mb-6">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">نظرة عامة على النظام</h1>
                <p class="mt-2 text-indigo-100 max-w-xl">مرحباً بك في لوحة المراقبة وإدارة العمليات الفنية. يمكنك متابعة المستخدمين، التجار، والفرق والرد على بلاغات النظام من هنا.</p>
            </div>
            <div class="flex items-center gap-x-3 bg-white/10 backdrop-blur-md rounded-xl px-4 py-2.5 self-start md:self-auto border border-white/10">
                <x-heroicon-s-clock class="h-5 w-5 text-indigo-200" />
                <span class="text-sm font-medium">{{ now()->translatedFormat('l، d F Y') }}</span>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 h-40 w-40 rounded-full bg-white/5 blur-2xl"></div>
        <div class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white/5 blur-2xl"></div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

        {{-- Total Users --}}
        <div class="transition duration-300 ease-in-out hover:scale-[1.02] hover:shadow-md fi-stats-overview-stat rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">المستخدمون</p>
                <div class="rounded-lg bg-indigo-50 p-2 dark:bg-indigo-950/50">
                    <x-heroicon-o-users class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-950 dark:text-white">{{ number_format($stats['total_users']) }}</p>
                <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold">حساب شخصي نشط</span>
            </div>
        </div>

        {{-- Total Merchants --}}
        <div class="transition duration-300 ease-in-out hover:scale-[1.02] hover:shadow-md fi-stats-overview-stat rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">التجار</p>
                <div class="rounded-lg bg-amber-50 p-2 dark:bg-amber-950/50">
                    <x-heroicon-o-building-storefront class="h-6 w-6 text-amber-600 dark:text-amber-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-950 dark:text-white">{{ number_format($stats['total_merchants']) }}</p>
                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold">حساب تجاري نشط</span>
            </div>
        </div>

        {{-- Total Teams --}}
        <div class="transition duration-300 ease-in-out hover:scale-[1.02] hover:shadow-md fi-stats-overview-stat rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">إجمالي الفرق</p>
                <div class="rounded-lg bg-emerald-50 p-2 dark:bg-emerald-950/50">
                    <x-heroicon-o-rectangle-group class="h-6 w-6 text-emerald-600 dark:text-emerald-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-950 dark:text-white">{{ number_format($stats['total_teams']) }}</p>
                <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">
                    {{ $stats['active_teams'] }} نشط / {{ $stats['inactive_teams'] }} معطل
                </p>
            </div>
        </div>

        {{-- Pending Feedbacks --}}
        <div class="transition duration-300 ease-in-out hover:scale-[1.02] hover:shadow-md fi-stats-overview-stat rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex flex-col justify-between {{ $stats['pending_feedbacks'] > 0 ? 'ring-2 ring-orange-500' : '' }}">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">بلاغات للرد</p>
                <div class="rounded-lg bg-orange-50 p-2 dark:bg-orange-950/50">
                    <x-heroicon-o-chat-bubble-left-ellipsis class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold {{ $stats['pending_feedbacks'] > 0 ? 'text-orange-600' : 'text-gray-950 dark:text-white' }}">
                    {{ number_format($stats['pending_feedbacks']) }}
                </p>
                <span class="text-[10px] text-gray-400 font-bold">من أصل {{ $stats['total_feedbacks'] }} بلاغ</span>
            </div>
        </div>

        {{-- New Users This Month --}}
        <div class="transition duration-300 ease-in-out hover:scale-[1.02] hover:shadow-md fi-stats-overview-stat rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">تسجيلات الشهر</p>
                <div class="rounded-lg bg-purple-50 p-2 dark:bg-purple-950/50">
                    <x-heroicon-o-user-plus class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-950 dark:text-white">{{ number_format($stats['new_users_this_month']) }}</p>
                <span class="text-[10px] text-purple-600 dark:text-purple-400 font-bold">مستخدم جديد</span>
            </div>
        </div>

        {{-- Admins --}}
        <div class="transition duration-300 ease-in-out hover:scale-[1.02] hover:shadow-md fi-stats-overview-stat rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">المشرفون</p>
                <div class="rounded-lg bg-red-50 p-2 dark:bg-red-950/50">
                    <x-heroicon-o-shield-check class="h-6 w-6 text-red-600 dark:text-red-400" />
                </div>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-extrabold text-gray-950 dark:text-white">{{ number_format($stats['total_admins']) }}</p>
                <span class="text-[10px] text-red-600 dark:text-red-400 font-bold">مشرف نظام</span>
            </div>
        </div>

    </div>

    {{-- Bottom Grid (Feedbacks + Sidebar) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- Recent Feedbacks (2 cols) --}}
        <div class="lg:col-span-2 rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-center justify-between mb-6 border-b border-gray-50 dark:border-gray-800 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-950 dark:text-white">آخر البلاغات والاقتراحات</h2>
                    <p class="text-xs text-gray-500 mt-1">أحدث البلاغات وطلبات المساعدة المرسلة من التجار والمستخدمين</p>
                </div>
                <a href="{{ App\Filament\SystemAdmin\Resources\SystemFeedbacks\SystemFeedbackResource::getUrl('index') }}" 
                   class="inline-flex items-center gap-x-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                    <span>عرض الكل</span>
                    <x-heroicon-m-arrow-left class="h-4 w-4" />
                </a>
            </div>

            @php
                $recentFeedbacks = \App\Models\SystemFeedback::with(['user', 'team'])->latest()->take(5)->get();
            @endphp
            @if ($recentFeedbacks->isEmpty())
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <x-heroicon-o-chat-bubble-left class="h-12 w-12 text-gray-300 dark:text-gray-700 mb-2" />
                    <p class="text-sm">لا توجد بلاغات أو اقتراحات حالياً.</p>
                </div>
            @else
                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($recentFeedbacks as $feedback)
                            <li class="py-5">
                                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold
                                                {{ $feedback->type === 'bug' ? 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20' : '' }}
                                                {{ $feedback->type === 'suggestion' ? 'bg-cyan-50 text-cyan-700 ring-1 ring-inset ring-cyan-600/10 dark:bg-cyan-500/10 dark:text-cyan-400 dark:ring-cyan-500/20' : '' }}
                                                {{ !in_array($feedback->type, ['bug', 'suggestion']) ? 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/10 dark:bg-gray-500/10 dark:text-gray-400 dark:ring-gray-500/20' : '' }}
                                            ">
                                                {{ $feedback->type === 'bug' ? 'خطأ' : ($feedback->type === 'suggestion' ? 'اقتراح' : 'أخرى') }}
                                            </span>
                                            <a href="{{ App\Filament\SystemAdmin\Resources\SystemFeedbacks\SystemFeedbackResource::getUrl('view', ['record' => $feedback]) }}" 
                                               class="text-sm font-bold leading-6 text-gray-950 hover:underline dark:text-white">
                                                {{ $feedback->title }}
                                            </a>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                            {{ $feedback->content }}
                                        </p>
                                        <div class="mt-2.5 flex flex-wrap items-center gap-x-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="font-bold text-gray-700 dark:text-gray-300">{{ $feedback->user?->name }}</span>
                                            <span class="text-gray-300 dark:text-gray-700">•</span>
                                            <span>{{ $feedback->team?->name ?? 'بلا فريق' }}</span>
                                            <span class="text-gray-300 dark:text-gray-700">•</span>
                                            <time datetime="{{ $feedback->created_at->toIso8601String() }}" class="font-medium">
                                                {{ $feedback->created_at->diffForHumans() }}
                                            </time>
                                        </div>
                                    </div>
                                    <div class="flex items-center sm:items-end justify-between sm:flex-col gap-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-gray-50 dark:border-gray-800">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold
                                            {{ $feedback->status->value === 'pending'  ? 'bg-orange-50 text-orange-800 dark:bg-orange-950/30 dark:text-orange-400' : '' }}
                                            {{ $feedback->status->value === 'reviewed' ? 'bg-blue-50 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'     : '' }}
                                            {{ $feedback->status->value === 'resolved' ? 'bg-green-50 text-green-800 dark:bg-green-900/30 dark:text-green-400' : '' }}
                                        ">
                                            {{ $feedback->status->label() }}
                                        </span>
                                        <a href="{{ App\Filament\SystemAdmin\Resources\SystemFeedbacks\SystemFeedbackResource::getUrl('edit', ['record' => $feedback]) }}" 
                                           class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-xs font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:hover:bg-gray-700">
                                            معالجة البلاغ
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Sidebar (Quick Actions + Info) --}}
        <div class="space-y-6">

            {{-- Quick Links --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="text-sm font-bold text-gray-950 dark:text-white mb-4">روابط سريعة</h3>
                <div class="grid grid-cols-1 gap-2.5">
                    <a href="{{ App\Filament\SystemAdmin\Resources\Users\UserResource::getUrl('create') }}" 
                       class="flex items-center justify-between rounded-lg p-3 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-100 dark:border-gray-800 transition duration-150">
                        <div class="flex items-center gap-x-2.5">
                            <x-heroicon-o-user-plus class="h-5 w-5 text-gray-400" />
                            <span class="text-gray-700 dark:text-gray-300">إضافة مستخدم جديد</span>
                        </div>
                        <x-heroicon-m-chevron-left class="h-4 w-4 text-gray-400" />
                    </a>
                    
                    <a href="{{ App\Filament\SystemAdmin\Resources\Users\UserResource::getUrl('index') }}" 
                       class="flex items-center justify-between rounded-lg p-3 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-100 dark:border-gray-800 transition duration-150">
                        <div class="flex items-center gap-x-2.5">
                            <x-heroicon-o-users class="h-5 w-5 text-gray-400" />
                            <span class="text-gray-700 dark:text-gray-300">إدارة كافة المستخدمين</span>
                        </div>
                        <x-heroicon-m-chevron-left class="h-4 w-4 text-gray-400" />
                    </a>

                    <a href="{{ App\Filament\SystemAdmin\Resources\Teams\TeamResource::getUrl('index') }}" 
                       class="flex items-center justify-between rounded-lg p-3 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-100 dark:border-gray-800 transition duration-150">
                        <div class="flex items-center gap-x-2.5">
                            <x-heroicon-o-rectangle-group class="h-5 w-5 text-gray-400" />
                            <span class="text-gray-700 dark:text-gray-300">إدارة فرق العمل</span>
                        </div>
                        <x-heroicon-m-chevron-left class="h-4 w-4 text-gray-400" />
                    </a>
                </div>
            </div>

            {{-- System Environment Info --}}
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="text-sm font-bold text-gray-950 dark:text-white mb-4">بيئة تشغيل النظام</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between border-b border-gray-50 dark:border-gray-800 pb-2">
                        <dt class="text-gray-500 dark:text-gray-400">إصدار Laravel</dt>
                        <dd class="font-bold text-gray-900 dark:text-white">13.14.0</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 dark:border-gray-800 pb-2">
                        <dt class="text-gray-500 dark:text-gray-400">إصدار PHP</dt>
                        <dd class="font-bold text-gray-900 dark:text-white">8.4.12</dd>
                    </div>
                    <div class="flex justify-between border-b border-gray-50 dark:border-gray-800 pb-2">
                        <dt class="text-gray-500 dark:text-gray-400">البيئة</dt>
                        <dd class="font-bold text-emerald-600 dark:text-emerald-400">
                            {{ config('app.env') === 'production' ? 'الإنتاجية (Production)' : 'التطوير (Local)' }}
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">قاعدة البيانات</dt>
                        <dd class="font-bold text-gray-900 dark:text-white">{{ config('database.default') }}</dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>
</x-filament-panels::page>
