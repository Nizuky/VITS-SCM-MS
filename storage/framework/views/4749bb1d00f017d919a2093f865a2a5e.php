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

            /* Brand primary button (shared across auth pages) */
            .scms-primary-btn { 
                background-color: #6D28D9 !important; 
                color: #ffffff !important; 
                border-color: transparent !important; 
                padding: 0.625rem 1.25rem;
                font-size: 1rem;
                border-radius: 0.5rem;
                transition: all 0.2s;
            }
            
            .scms-primary-btn:hover { 
                background-color: #5B21B6 !important; 
                color: #ffffff !important; 
            }
            
            .scms-primary-btn:focus { 
                outline: none !important; 
                box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.35) !important; 
            }
            
            .scms-primary-btn:active { 
                background-color: #4C1D95 !important; 
            }
            
            /* Input field styling for auth pages */
            input[type="email"],
            input[type="password"],
            input[type="text"],
            textarea,
            select {
                background-color: rgba(255, 255, 255, 0.1) !important;
                border: 2px solid rgba(255, 255, 255, 0.3) !important;
                color: #ffffff !important;
                border-radius: 0.5rem !important;
                padding: 0.625rem 0.875rem !important;
                width: 100% !important;
                transition: all 0.2s ease !important;
                pointer-events: auto !important;
                user-select: text !important;
                -webkit-user-select: text !important;
                cursor: text !important;
                position: relative !important;
                z-index: 10 !important;
            }
            
            input[type="email"]:focus,
            input[type="password"]:focus,
            input[type="text"]:focus,
            textarea:focus,
            select:focus {
                outline: none !important;
                border-color: rgba(109, 40, 217, 0.8) !important;
                box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.2) !important;
                background-color: rgba(255, 255, 255, 0.15) !important;
            }
            
            input[type="email"]::placeholder,
            input[type="password"]::placeholder,
            input[type="text"]::placeholder,
            textarea::placeholder {
                color: rgba(255, 255, 255, 0.5) !important;
            }
            
            /* Readonly input styling */
            input[readonly] {
                background-color: rgba(255, 255, 255, 0.05) !important;
                cursor: not-allowed !important;
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
</html><?php /**PATH C:\Users\janar\Herd\scms\resources\views/components/layouts/auth/simple.blade.php ENDPATH**/ ?>