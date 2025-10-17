<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <head>
        @include('partials.head')
        <style>
            :root { --header-desktop-h: 115px; --header-mobile-h: 72px; }
            #site-header { position: fixed; top: 0; left: 0; width: 100%; height: var(--header-desktop-h); z-index: 1000; display:flex; align-items:center; justify-content:center; box-shadow: 0 2px 12px rgba(0,0,0,0.12); background: rgba(255,255,255,0.9); backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px); transition: transform .32s cubic-bezier(.22,.9,.32,1), background .28s ease, opacity .28s ease; opacity: 0.98; -webkit-backface-visibility: hidden; backface-visibility: hidden; }
            /* hidden state slides header up */
            #site-header.header-hidden { transform: translateY(-120%); }

            /* mobile adjustments */
            @media (max-width: 640px) { #site-header { height: var(--header-mobile-h); } body { padding-top: var(--header-mobile-h); }
            }

            /* desktop default body padding so content isn't covered */
            body { padding-top: var(--header-desktop-h); background-image: url('{{ asset('storage/vitsbg.png') }}'); background-repeat: no-repeat; background-position: center top; background-size: cover; background-attachment: fixed; }

            /* Dark mode tweak */
            @media (prefers-color-scheme: dark) { #site-header { background: rgba(255, 255, 255); } }
        </style>
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
        @include('partials.vits_branding')
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <script>
            // Header show/hide on scroll: hide when scrolling down, show when scrolling up
            (function(){
                const header = document.getElementById('site-header');
                if (!header) return;
                let lastScroll = window.pageYOffset || document.documentElement.scrollTop;
                let ticking = false;
                const threshold = 22;
                let lastToggle = 0;
                const minToggleInterval = 120;

                function onScroll(){
                    const current = window.pageYOffset || document.documentElement.scrollTop;
                    const diff = current - lastScroll;
                    if (Math.abs(diff) < threshold) return;
                    const now = Date.now();
                    if (now - lastToggle < minToggleInterval) { lastScroll = current; ticking = false; return; }
                    if (diff > 0) { header.classList.add('header-hidden'); header.style.opacity = '0'; }
                    else { header.classList.remove('header-hidden'); header.style.opacity = '0.99'; }
                    lastToggle = now; lastScroll = current <= 0 ? 0 : current; ticking = false;
                }
                window.addEventListener('scroll', function(){ if (!ticking) { window.requestAnimationFrame(onScroll); ticking = true; } }, { passive: true });
            })();
        </script>
        @fluxScripts
    </body>
</html>
