<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>منصة وضوح</title>
    
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="theme-color" content="#ffffff" />
    
    @viteReactRefresh
    @vite(['resources/js/src/main.tsx'])
</head>

<body>
    <script>
        window.isLoggedIn = @json(auth()->check());
    </script>
    <div id="root"></div>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('SW registered: ', registration);
                }).catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }
    </script>
</body>

</html>
