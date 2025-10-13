<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Student Dashboard — VITS</title>
        <link rel="icon" href="/vitswhite.png" sizes="any">
        <style>
            :root { --header-desktop-h: 115px; --header-mobile-h: 72px; }

            /* ✅ Add this line */
            body, button, input, h1, h2, h3, p, a, span {
                font-family: Arial, Helvetica, sans-serif;
            }

            #site-header {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: var(--header-desktop-h);
                z-index: 1000;
                display:flex;
                align-items:center;
                justify-content:center;
                box-shadow: 0 2px 12px rgba(0,0,0,0.12);
                background: rgba(255,255,255,0.9);
                backdrop-filter: blur(6px);
                -webkit-backdrop-filter: blur(6px);
                transition: transform .32s cubic-bezier(.22,.9,.32,1), background .28s ease, opacity .28s ease;
                opacity: 0.98;
                -webkit-backface-visibility: hidden;
                backface-visibility: hidden;
            }

            @media (max-width: 640px) {
                #site-header { height: var(--header-mobile-h); }
                body { padding-top: var(--header-mobile-h); }
            }

            body { padding-top: var(--header-desktop-h); }

            .card {
                background: rgba(255,255,255,0.94);
                border-radius: 14px;
                padding: 22px;
                box-shadow: 0 8px 30px rgba(0,0,0,0.12);
                max-width: 980px;
                width: 100%;
            }
        </style>

    </head>
    <body style="background-image: url('<?php echo e(asset('storage/vits_bg.png')); ?>'); background-repeat: no-repeat; background-position: center top; background-size: cover; background-attachment: fixed; min-height:100vh;">

        <header id="site-header">
            <img src="<?php echo e(asset('storage/vits_header.png')); ?>" alt="VITS Header" style="width:100%; height:100%; object-fit:cover; display:block;">
        </header>

        <main style="display:flex; align-items:center; justify-content:center; padding:48px 20px; min-height: calc(100vh - var(--header-desktop-h));">
            <div class="card">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
                    <div>
                        <h1 style="color:#1a00ac; font-size:22px; margin:0 0 6px 0;">Student Dashboard</h1>
                        <p style="margin:0; color:#333;">Welcome, <strong>Student</strong>. Use the links below to manage system-wide settings.</p>
                    </div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <a href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="role-btn" style="background:#1a00ac; color:#fff; padding:8px 12px; border-radius:10px; text-decoration:none;">Logout</a>
                    </div>
                </div>

                <hr style="margin:16px 0; border:none; height:1px; background: #ececec;">

                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
                    <div style="background:#fff; padding:12px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <strong>System Overview</strong>
                        <p class="text-sm" style="margin:6px 0 0 0; color:#444;">Placeholder metrics and quick stats.</p>
                    </div>
                    <div style="background:#fff; padding:12px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <strong>Manage Contracts</strong>
                        <p class="text-sm" style="margin:6px 0 0 0; color:#444;">Quick links to user & seeder management.</p>
                    </div>
                    <div style="background:#fff; padding:12px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <strong>Email Logs</strong>
                        <p class="text-sm" style="margin:6px 0 0 0; color:#444;">View recent verification/reset emails (if available).</p>
                    </div>
                </div>

                    <script>
                        // Parallax effect: slight background position shift on scroll
                        (function(){
                            const body = document.body;
                            const card = document.getElementById('superadmin-card');
                            // entrance animation
                            requestAnimationFrame(()=>{
                                if (card){ card.style.opacity = '1'; card.style.transform = 'translateY(0)'; }
                            });

                            let ticking = false;
                            window.addEventListener('scroll', function(){
                                if (!ticking){
                                    window.requestAnimationFrame(()=>{
                                        const y = window.scrollY || window.pageYOffset;
                                        // move background slower than scroll for depth
                                        body.style.backgroundPosition = `center calc(50% + ${-(y * 0.06)}px)`;
                                        ticking = false;
                                    });
                                    ticking = true;
                                }
                            }, { passive: true });
                        })();
                    </script>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;"><?php echo csrf_field(); ?></form>
            </div>
        </main>
    </body>
</html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/dashboard.blade.php ENDPATH**/ ?>