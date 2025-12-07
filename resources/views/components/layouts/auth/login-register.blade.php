<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
        <style>
            /* ===========================================
               AUTH LAYOUT - PURE CSS, NO JS DEPENDENCY
               Content will be centered and visible immediately
               =========================================== */
            :root { 
                --header-desktop-h: 115px; 
                --header-mobile-h: 80px;
                --header-height-actual: 115px;
            }
            
            /* Prevent horizontal scroll globally */
            html {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            #site-header { 
                position: fixed; 
                top: 0; 
                left: 0; 
                width: 100%; 
                height: var(--header-desktop-h); 
                z-index: 1000; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                box-shadow: 0 2px 12px rgba(0,0,0,0.12); 
                background: rgba(255,255,255,0.9); 
                backdrop-filter: blur(6px); 
                -webkit-backdrop-filter: blur(6px); 
                transition: transform .32s cubic-bezier(.22,.9,.32,1), background .28s ease, opacity .28s ease; 
                opacity: 0.98; 
                -webkit-backface-visibility: hidden; 
                backface-visibility: hidden; 
            }
            
            /* hidden state slides header up */
            #site-header.header-hidden { 
                transform: translateY(-120%); 
            }

            /* ===========================================
               BODY: Minimal styling
               =========================================== */
            body { 
                background-image: url('{{ asset('storage/vitsbg.png') }}'); 
                background-repeat: no-repeat; 
                background-position: center top; 
                background-size: cover; 
                background-attachment: fixed;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                overflow-x: hidden !important;
                min-height: 100vh !important;
                min-height: 100dvh !important;
            }
            
            /* Auth content wrapper - FIXED POSITION for viewport centering */
            .auth-content-wrapper {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: calc(100% - 24px) !important;
                max-width: 28rem !important;
                padding-top: calc(var(--header-desktop-h, 115px) / 2) !important;
                box-sizing: border-box !important;
                z-index: 10 !important;
            }

            /* ===========================================
               MOBILE: 640px and down
               =========================================== */
            @media (max-width: 640px) { 
                :root {
                    --header-height-actual: var(--header-mobile-h);
                }
                
                #site-header { 
                    height: var(--header-mobile-h); 
                } 
                
                body { 
                    background-attachment: scroll;
                }
                
                .auth-content-wrapper {
                    position: fixed !important;
                    top: 50% !important;
                    left: 50% !important;
                    transform: translate(-50%, -50%) !important;
                    width: calc(100% - 16px) !important;
                    max-width: 100% !important;
                    padding-top: calc(var(--header-mobile-h, 80px) / 2) !important;
                }
                
                #site-header img {
                    object-fit: contain !important;
                    padding: 0.5rem;
                }
            }

            /* Enhanced Color Hierarchy for Login/Register Pages */
            
            /* Headings - Clean white with subtle shadow */
            h1, h2, h3 {
                color: #ffffff !important;
                font-weight: 700 !important;
                text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                letter-spacing: -0.02em;
            }
            
            /* ===========================================
               AUTH PAGE SPECIFIC STYLES
               Pure CSS centering - no JS dependency
               =========================================== */
            body.auth-page {
                /* Override any conflicting styles */
                margin: 0 !important;
                padding: 0 !important;
                padding-top: var(--header-desktop-h) !important;
            }
            
            @media (max-width: 640px) {
                body.auth-page {
                    padding-top: var(--header-mobile-h) !important;
                }
            }
            
            /* Auth content wrapper - CRITICAL for centering */
            .auth-content-wrapper {
                width: 100% !important;
                max-width: 100vw !important;
                margin: 0 auto !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                box-sizing: border-box !important;
                min-height: calc(100vh - var(--header-desktop-h)) !important;
                min-height: calc(100dvh - var(--header-desktop-h)) !important;
            }
            
            @media (max-width: 640px) {
                .auth-content-wrapper {
                    min-height: calc(100vh - var(--header-mobile-h)) !important;
                    min-height: calc(100dvh - var(--header-mobile-h)) !important;
                    padding: 1rem !important;
                }
            }
            
            /* Primary text - Crisp white */
            p, label, span {
                color: rgba(255, 255, 255, 0.95) !important;
            }
            
            /* Labels - Enhanced readability */
            label {
                color: rgba(255, 255, 255, 0.95) !important;
                font-weight: 500 !important;
                font-size: 0.9rem !important;
                letter-spacing: 0.01em;
            }
            
            /* Secondary text - Softer white */
            .text-secondary,
            .text-sm:not(.font-semibold),
            .text-xs {
                color: rgba(255, 255, 255, 0.8) !important;
            }
            
            /* Muted text - Subtle but readable */
            .text-muted,
            small,
            .text-white\/80 {
                color: rgba(255, 255, 255, 0.7) !important;
            }
            
            /* Links - Subtle gray to vibrant purple */
            a,
            a.text-sm,
            a span {
                color: rgba(156, 163, 175, 0.9) !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                text-decoration: none;
                position: relative;
            }
            
            a:hover,
            a.text-sm:hover,
            a:hover span {
                color: #a78bfa !important;
                text-decoration: none;
            }
            
            a:active {
                color: #8c4cf2 !important;
            }

            /* Brand primary button - Modern gradient style */
            .scms-primary-btn { 
                background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%) !important;
                color: #ffffff !important; 
                border: none !important; 
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                border-radius: 0.625rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                font-weight: 600 !important;
                box-shadow: 0 4px 12px rgba(109, 40, 217, 0.3),
                           0 2px 4px rgba(0, 0, 0, 0.1);
                letter-spacing: 0.01em;
            }
            
            .scms-primary-btn:hover { 
                background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important;
                color: #ffffff !important; 
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(109, 40, 217, 0.4),
                           0 4px 8px rgba(0, 0, 0, 0.15);
            }
            
            .scms-primary-btn:focus { 
                outline: none !important; 
                box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.3),
                           0 4px 12px rgba(109, 40, 217, 0.3) !important; 
            }
            
            .scms-primary-btn:active { 
                background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%) !important;
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(109, 40, 217, 0.3);
            }
            
            .scms-primary-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
                transform: none !important;
            }
            
            /* Input field styling - Clean modern design */
            input[type="email"],
            input[type="password"],
            input[type="text"],
            textarea,
            select {
                background: rgba(255, 255, 255, 0.08) !important;
                background-color: rgba(255, 255, 255, 0.08) !important;
                border: 1.5px solid rgba(255, 255, 255, 0.15) !important;
                color: #ffffff !important;
                border-radius: 0.625rem !important;
                padding: 0.75rem 1rem !important;
                width: 100% !important;
                font-size: 0.95rem !important;
                line-height: 1.5 !important;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                pointer-events: auto !important;
                user-select: text !important;
                -webkit-user-select: text !important;
                cursor: text !important;
                position: relative !important;
                z-index: 10 !important;
                -webkit-text-fill-color: #ffffff !important;
            }
            
            /* Placeholder styling - Subtle gray */
            input[type="email"]::placeholder,
            input[type="password"]::placeholder,
            input[type="text"]::placeholder,
            textarea::placeholder {
                color: rgba(156, 163, 175, 0.7) !important;
                opacity: 1 !important;
                -webkit-text-fill-color: rgba(156, 163, 175, 0.7) !important;
            }
            
            /* Hover state for inputs */
            input[type="email"]:hover:not(:focus),
            input[type="password"]:hover:not(:focus),
            input[type="text"]:hover:not(:focus),
            textarea:hover:not(:focus) {
                border-color: rgba(255, 255, 255, 0.25) !important;
                background: rgba(255, 255, 255, 0.12) !important;
            }
            
            /* Focus state - Purple accent */
            input[type="email"]:focus,
            input[type="password"]:focus,
            input[type="text"]:focus,
            textarea:focus,
            select:focus {
                outline: none !important;
                border-color: #8c4cf2 !important;
                background: rgba(140, 76, 242, 0.08) !important;
                box-shadow: 0 0 0 4px rgba(140, 76, 242, 0.15), 0 1px 2px rgba(0, 0, 0, 0.1) !important;
                color: #ffffff !important;
            }
            
            /* WebKit autofill styling */
            input[type="email"]:-webkit-autofill,
            input[type="password"]:-webkit-autofill,
            input[type="text"]:-webkit-autofill {
                -webkit-text-fill-color: #ffffff !important;
                -webkit-box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.08) inset !important;
                box-shadow: 0 0 0 1000px rgba(255, 255, 255, 0.08) inset !important;
                background-color: rgba(255, 255, 255, 0.08) !important;
                border: 1.5px solid rgba(255, 255, 255, 0.15) !important;
                caret-color: #ffffff !important;
                transition: background-color 5000s ease-in-out 0s !important;
            }
            
            /* Autofill focus state */
            input[type="email"]:-webkit-autofill:focus,
            input[type="password"]:-webkit-autofill:focus,
            input[type="text"]:-webkit-autofill:focus {
                -webkit-text-fill-color: #ffffff !important;
                -webkit-box-shadow: 0 0 0 1000px rgba(140, 76, 242, 0.08) inset, 0 0 0 4px rgba(140, 76, 242, 0.15) !important;
                border-color: #8c4cf2 !important;
                caret-color: #ffffff !important;
            }
            
            /* Autofill placeholder */
            input:-webkit-autofill::placeholder,
            input:-webkit-autofill:hover::placeholder,
            input:-webkit-autofill:focus::placeholder {
                color: rgba(156, 163, 175, 0.7) !important;
                -webkit-text-fill-color: rgba(156, 163, 175, 0.7) !important;
            }
            
            /* Universal placeholder styling */
            *::placeholder {
                color: rgba(156, 163, 175, 0.7) !important;
                opacity: 1 !important;
                -webkit-text-fill-color: rgba(156, 163, 175, 0.7) !important;
            }
            
            /* Text selection styling */
            input[type="email"]::selection,
            input[type="password"]::selection,
            input[type="text"]::selection,
            textarea::selection,
            ::selection {
                background-color: rgba(140, 76, 242, 0.5) !important;
                color: #ffffff !important;
            }
            
            ::-moz-selection {
                background-color: rgba(140, 76, 242, 0.5) !important;
                color: #ffffff !important;
            }
            
            /* Error messages - Modern alert style */
            .error-message,
            [style*="background:rgba(239, 68, 68"] {
                background: rgba(239, 68, 68, 0.15) !important;
                border-left: 3px solid #f87171 !important;
                border-radius: 0.5rem !important;
                color: #fef2f2 !important;
                padding: 0.875rem 1rem !important;
                backdrop-filter: blur(8px);
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
            }
            
            /* Success messages - Clean green style */
            .success-message,
            [style*="background:rgba(16, 185, 129"] {
                background: rgba(16, 185, 129, 0.15) !important;
                border-left: 3px solid #34d399 !important;
                border-radius: 0.5rem !important;
                color: #f0fdf4 !important;
                padding: 0.875rem 1rem !important;
                backdrop-filter: blur(8px);
                box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
            }
            
            /* Card container - Modern glass morphism */
            .bg-white\/10,
            .auth-card {
                background: rgba(255, 255, 255, 0.06) !important;
                backdrop-filter: blur(20px) saturate(180%) !important;
                -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
                border: 1px solid rgba(255, 255, 255, 0.1) !important;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1), 
                           0 1px 2px rgba(0, 0, 0, 0.05),
                           inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
            }
            
            /* Readonly input styling */
            input[readonly] {
                background-color: rgba(255, 255, 255, 0.05) !important;
                cursor: not-allowed !important;
                opacity: 0.6;
            }
            
            /* Ensure input wrappers don't block interaction */
            [data-flux-input],
            .flux-input,
            form > div,
            form div {
                pointer-events: auto !important;
            }
            
            /* Override any Flux overlays or blocking elements */
            [data-flux-input] > *,
            [data-flux-input]::before,
            [data-flux-input]::after {
                pointer-events: none !important;
            }
            
            [data-flux-input] input {
                pointer-events: auto !important;
            }
            
            /* ===========================================
               COMPREHENSIVE MOBILE RESPONSIVENESS
               =========================================== */
            
            /* Mobile optimizations - Small devices (phones, 640px and down) */
            @media (max-width: 640px) {
                /* Button full width on mobile with compact padding */
                .scms-primary-btn {
                    width: 100%;
                    padding: 0.625rem 0.875rem;
                    font-size: 0.9375rem;
                }
                
                /* Prevent iOS zoom on input focus - COMPACT */
                input[type="email"],
                input[type="password"],
                input[type="text"] {
                    font-size: 16px !important;
                    padding: 0.625rem 0.875rem !important;
                }
                
                /* Reduce form spacing for mobile */
                form > div,
                form .space-y-4 > div,
                form .space-y-6 > div {
                    margin-bottom: 0.75rem !important;
                }
                
                /* Compact form container */
                .auth-content-wrapper > div > div,
                .auth-card,
                .bg-white\/10 {
                    padding: 1rem 1.25rem !important;
                }
                
                /* Auth container adjustments */
                body > div.flex {
                    padding: 0.75rem !important;
                    padding-top: calc(var(--header-height-mobile, 80px) + 0.75rem) !important;
                    gap: 0.75rem !important;
                }
                
                /* Logo sizing on mobile */
                body > div.flex > a img {
                    height: 3rem !important;
                }
                
                /* Auth card - ensure full width and proper sizing */
                body > div.flex > div[class*="w-full"],
                body > div.flex > form[class*="w-full"],
                body > div.flex > div > div[class*="w-full"] {
                    width: 100% !important;
                    max-width: 100% !important;
                    margin-left: 0 !important;
                    margin-right: 0 !important;
                    padding: 1rem !important;
                    border-radius: 1rem !important;
                }
                
                /* Form fields full width */
                form input,
                form select,
                form textarea {
                    width: 100% !important;
                    max-width: 100% !important;
                }
                
                /* Form buttons */
                form button[type="submit"],
                form .scms-primary-btn {
                    width: 100% !important;
                    padding: 0.875rem 1rem !important;
                }
                
                /* Heading sizes */
                h1, .text-2xl, .text-3xl {
                    font-size: 1.5rem !important;
                    line-height: 1.25 !important;
                }
                
                h2, .text-xl {
                    font-size: 1.25rem !important;
                }
                
                /* Description text */
                p.text-sm, p.text-xs {
                    font-size: 0.8125rem !important;
                    line-height: 1.5 !important;
                }
                
                /* Link and button spacing */
                form a,
                form .text-sm a {
                    display: inline-block;
                    padding: 0.25rem 0;
                }
            }
            
            /* Extra small devices (small phones, 380px and down) */
            @media (max-width: 380px) {
                body > div.flex {
                    padding: 0.75rem !important;
                    padding-top: calc(var(--header-height-mobile, 80px) + 0.75rem) !important;
                }
                
                body > div.flex > div[class*="w-full"],
                body > div.flex > form[class*="w-full"] {
                    padding: 0.75rem !important;
                    border-radius: 0.75rem !important;
                }
                
                h1, .text-2xl, .text-3xl {
                    font-size: 1.25rem !important;
                }
                
                h2, .text-xl {
                    font-size: 1.125rem !important;
                }
                
                /* Smaller buttons on tiny screens */
                form button[type="submit"],
                form .scms-primary-btn {
                    padding: 0.75rem 0.875rem !important;
                    font-size: 0.9375rem !important;
                }
            }
            
            /* Medium devices (tablets, 641px to 1024px) */
            @media (min-width: 641px) and (max-width: 1024px) {
                body > div.flex {
                    padding: 1.5rem !important;
                    padding-top: calc(var(--header-height, 115px) + 1.5rem) !important;
                }
                
                /* Auth card with max-width on tablets */
                body > div.flex > div[class*="w-full"],
                body > div.flex > form[class*="w-full"] {
                    max-width: 28rem !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }
            }
            
            /* Ensure no horizontal scroll */
            html, body {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            /* Prevent content from overflowing viewport */
            body > div.flex {
                max-width: 100vw !important;
                box-sizing: border-box !important;
            }
            
            /* Safe area insets for modern devices (notch, home indicator) */
            /* Use @@ to escape @supports from Blade parser */
            @@supports (padding: env(safe-area-inset-bottom)) {
                body > div.flex {
                    padding-bottom: calc(1rem + env(safe-area-inset-bottom)) !important;
                }
            }
            
            /* Landscape mode on mobile */
            @media (max-height: 500px) and (orientation: landscape) {
                body > div.flex {
                    padding-top: calc(var(--header-height-mobile, 80px) + 0.5rem) !important;
                    gap: 0.5rem !important;
                }
                
                body > div.flex > a img {
                    height: 2rem !important;
                }
            }

            /* Dark mode tweak */
            @media (prefers-color-scheme: dark) { 
                #site-header { 
                    background: rgba(255, 255, 255); 
                } 
            }
        </style>
    </head>
    <body class="min-h-screen antialiased auth-page">
        @include('partials.vits_branding')
        <div class="auth-content-wrapper" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: calc(100% - 20px); max-width: 26rem; padding-top: 30px; box-sizing: border-box; z-index: 10;">
            <!-- Inner container for vertical stacking -->
            <div style="display: flex; flex-direction: column; align-items: center; width: 100%; max-width: 26rem; gap: 0.75rem;">
                <!-- Logo link to welcome page -->
                <a href="{{ route('home') }}" class="mb-1 sm:mb-3">
                    <img src="{{ asset('storage/vits_white.png') }}" alt="VITS Logo" class="h-10 sm:h-14 w-auto" />
                </a>
            
                {{ $slot }}
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
        <script>
            // On auth pages (login/register/etc), clear any pending logout flags so they don't affect login
            (function(){
                try { localStorage.removeItem('scms_force_logout_pending'); } catch (_) {}
                try { localStorage.removeItem('scms_force_logout'); } catch (_) {}
                try { document.cookie = 'scms_force_logout_pending=; Max-Age=0; path=/'; } catch (_) {}
            })();
            
            // Force enable input interaction after page load and on bfcache restoration
            (function() {
                function enableInputs() {
                    const inputs = document.querySelectorAll('input[type="email"], input[type="password"], input[type="text"]');
                    inputs.forEach(function(input) {
                        input.style.pointerEvents = 'auto';
                        input.style.userSelect = 'text';
                        input.style.webkitUserSelect = 'text';
                        input.removeAttribute('disabled');
                        input.removeAttribute('readonly');
                        
                        // Remove any overlay elements that might be blocking
                        const parent = input.parentElement;
                        if (parent) {
                            parent.style.pointerEvents = 'auto';
                            const overlays = parent.querySelectorAll('[style*="pointer-events: none"]');
                            overlays.forEach(o => o.style.pointerEvents = 'auto');
                        }
                    });
                }
                
                // Run on DOMContentLoaded
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        setTimeout(enableInputs, 100);
                    });
                } else {
                    setTimeout(enableInputs, 100);
                }
                
                // CRITICAL: Also run on pageshow for bfcache navigation (back/forward)
                window.addEventListener('pageshow', function(event) {
                    // event.persisted is true when page is restored from bfcache
                    setTimeout(enableInputs, 100);
                });
            })();
        </script>
    </body>
</html>
