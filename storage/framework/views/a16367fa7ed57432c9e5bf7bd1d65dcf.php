<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
    <head>
        <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
        <style>
            :root { 
                --header-desktop-h: 115px; 
                --header-mobile-h: 80px; 
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

            /* desktop default body padding so content isn't covered */
            body { 
                padding-top: var(--header-desktop-h); 
                background-image: url('<?php echo e(asset('storage/vitsbg.png')); ?>'); 
                background-repeat: no-repeat; 
                background-position: center top; 
                background-size: cover; 
                background-attachment: fixed; 
            }

            /* mobile adjustments */
            @media (max-width: 640px) { 
                #site-header { 
                    height: var(--header-mobile-h); 
                } 
                
                body { 
                    padding-top: var(--header-mobile-h); 
                    background-attachment: scroll;
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
            
            /* Mobile optimizations */
            @media (max-width: 640px) {
                .scms-primary-btn {
                    width: 100%;
                    padding: 0.75rem 1rem;
                    font-size: 1rem;
                }
                
                input[type="email"],
                input[type="password"],
                input[type="text"] {
                    font-size: 16px !important; /* Prevents zoom on iOS */
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
    <body class="min-h-screen antialiased" style="margin: 0; padding: 0;">
        <?php echo $__env->make('partials.vits_branding', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-4 sm:p-6 md:p-10">
            <!-- Logo link to welcome page -->
            <a href="<?php echo e(route('home')); ?>" class="mb-4">
                <img src="<?php echo e(asset('storage/vits_white.png')); ?>" alt="VITS Logo" class="h-16 w-auto" />
            </a>
            
            <?php echo e($slot); ?>

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
            
            // Force enable input interaction after page load
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
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
                }, 100);
            });
        </script>
    </body>
</html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/components/layouts/auth/login-register.blade.php ENDPATH**/ ?>