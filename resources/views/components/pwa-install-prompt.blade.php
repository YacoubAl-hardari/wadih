<div x-data="{
        isVisible: false,
        deferredPrompt: null,
        init() {
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                this.isVisible = true;
            });
        },
        async installApp() {
            if (!this.deferredPrompt) return;
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            this.deferredPrompt = null;
            this.isVisible = false;
        },
        dismiss() {
            this.isVisible = false;
        }
    }" x-show="isVisible" x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-8"
    class="fixed bottom-0 left-0 right-0 z-50 p-4 sm:p-6 flex justify-center pointer-events-none" style="display: none;"
    dir="rtl">
    <div class="pointer-events-auto w-full max-w-md">
        <div
            class="relative overflow-hidden rounded-2xl border border-white/20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-2xl p-5 sm:p-6 transition-all">
            <button @click="dismiss"
                class="absolute top-3 left-3 p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-slate-800"
                aria-label="إغلاق">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>

            <div class="flex items-start gap-4">
                <div
                    class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" x2="12" y1="15" y2="3" />
                    </svg>
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">
                        تثبيت التطبيق للوصول بشكل سريع
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                        احصل على تجربة أسرع وأكثر استقراراً. قم بتثبيت التطبيق الآن للوصول السريع دون الحاجة للمتصفح!
                    </p>

                    <div class="flex gap-3">
                        <button @click="installApp"
                            class="flex-1 py-2.5 px-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold text-sm shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all">
                            تثبيت الآن
                        </button>
                        <button @click="dismiss"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-600 dark:text-slate-300 font-medium text-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                            لاحقاً
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>