<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title>VITS Social Contract Monitoring and Management System</title>
        <?php
                $iconCandidates = [
                    'vits_white.png',
                    'storage/vits_whites.png',
                    'vits_whites.png',
                    'vitswhite.png',
                    'vitslogo.png',
                    'storage/vits_white.png',
                    'storage/vits_header.png',
                ];
                $iconUrl = null;
                $iconMTime = null;
                foreach ($iconCandidates as $relPath) {
                    try {
                        $full = public_path($relPath);
                        if (file_exists($full)) { $iconUrl = asset($relPath); try { $iconMTime = @filemtime($full) ?: null; } catch (Throwable $e) {} break; }
                    } catch (Throwable $e) {}
                }
                if (!$iconUrl) { $iconUrl = asset('vits_white.png'); }
                if ($iconUrl && $iconMTime) { $iconUrl .= '?v=' . $iconMTime; }
            ?>
        <link rel="icon" href="<?php echo e($iconUrl); ?>" sizes="any">
        <link rel="icon" href="<?php echo e($iconUrl); ?>" type="image/png">
        <link rel="shortcut icon" href="<?php echo e($iconUrl); ?>" type="image/png">
        <link rel="apple-touch-icon" href="<?php echo e($iconUrl); ?>">
        
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            /* ===========================================
               WELCOME PAGE - COMPREHENSIVE MOBILE CSS
               PURE CSS LAYOUT - NO JS DEPENDENCY
               =========================================== */
            
            /* Mobile-responsive animations */
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Smooth transitions for all interactive elements */
            button, a {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Prevent horizontal scroll globally */
            html, body {
                overflow-x: hidden !important;
                max-width: 100vw !important;
            }
            
            /* ===========================================
               WELCOME PAGE BODY
               =========================================== */
            body.welcome-page {
                min-height: 100vh !important;
                min-height: 100dvh !important;
                /* Use vits_bg.png as background instead of gradient */
                background-image: url('<?php echo e(asset('storage/vits_bg.png')); ?>') !important;
                background-repeat: no-repeat !important;
                background-position: center center !important;
                background-size: cover !important;
                background-attachment: fixed !important;
                background-color: #667eea !important; /* Fallback color */
                font-family: system-ui, -apple-system, sans-serif !important;
                margin: 0 !important;
                padding: 0 !important;
                box-sizing: border-box !important;
                width: 100% !important;
                overflow-x: hidden !important;
            }
            
            /* Mobile: scroll instead of fixed for better performance */
            @media (max-width: 640px) {
                body.welcome-page {
                    background-attachment: scroll !important;
                }
            }
            
            /* Welcome content wrapper - FIXED POSITION for viewport centering */
            .welcome-content-wrapper {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: calc(100% - 24px) !important;
                max-width: 480px !important;
                padding-top: calc(var(--header-height, 115px) / 2) !important;
                box-sizing: border-box !important;
                z-index: 10 !important;
            }
            
            /* ===========================================
               MOBILE: Small devices (640px and down)
               OPTIMIZED FOR COMPACTNESS
               =========================================== */
            @media (max-width: 640px) {
                .welcome-content-wrapper {
                    position: fixed !important;
                    top: 50% !important;
                    left: 50% !important;
                    transform: translate(-50%, -50%) !important;
                    width: calc(100% - 16px) !important;
                    max-width: 100% !important;
                    padding-top: 35px !important; /* Reduced for header */
                    max-height: calc(100vh - 70px) !important;
                    max-height: calc(100dvh - 70px) !important;
                    overflow-y: auto !important;
                }
                
                /* Main card - COMPACT mobile styling */
                .main-card {
                    padding: 16px 14px !important;
                    border-radius: 16px !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0 auto !important;
                    box-sizing: border-box !important;
                }
                
                .main-card h2 {
                    font-size: 20px !important;
                    margin-bottom: 4px !important;
                }
                
                .main-card h4 {
                    font-size: 11px !important;
                    margin-bottom: 10px !important;
                }
                
                .main-card p {
                    font-size: 11px !important;
                    line-height: 1.5 !important;
                    text-align: left !important;
                    margin-bottom: 10px !important;
                }
                
                /* Notice box - COMPACT */
                .notice-box {
                    padding: 10px 12px !important;
                    font-size: 10px !important;
                    border-radius: 8px !important;
                }
                
                .notice-box span:first-child {
                    font-size: 14px !important;
                }
                
                .notice-box div > span:first-child {
                    font-size: 10px !important;
                    margin-bottom: 2px !important;
                }
                
                /* Toggle container - COMPACT horizontal layout */
                .toggle-container {
                    flex-direction: row !important;
                    flex-wrap: wrap !important;
                    gap: 8px !important;
                    padding: 10px 12px !important;
                    border-radius: 12px !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                    margin-top: 12px !important;
                }
                
                .toggle-container > span {
                    text-align: center !important;
                    padding-left: 0 !important;
                    font-size: 10px !important;
                    white-space: normal !important;
                    width: 100% !important;
                }
                
                /* Toggle buttons - COMPACT */
                .toggle-buttons {
                    width: 100% !important;
                    justify-content: center !important;
                }
                
                .toggle-buttons button {
                    padding: 8px 20px !important;
                    font-size: 12px !important;
                    flex: 1;
                }
                
                /* Followup sections - COMPACT */
                .followup-section {
                    padding: 8px 12px !important;
                    width: 100% !important;
                    box-sizing: border-box !important;
                    margin-top: 8px !important;
                }
                
                .followup-section p {
                    font-size: 11px !important;
                    margin-bottom: 8px !important;
                }
                
                /* Action buttons - COMPACT horizontal layout */
                .btn-group {
                    flex-direction: row !important;
                    width: 100% !important;
                    gap: 8px !important;
                }
                
                .btn-group a,
                .btn-group button {
                    flex: 1 !important;
                    width: auto !important;
                    min-width: 0 !important;
                }
                
                .action-btn {
                    padding: 10px 12px !important;
                    font-size: 12px !important;
                    min-width: auto !important;
                    text-align: center;
                }
                
                /* Followups container - COMPACT */
                #followups {
                    width: 100% !important;
                    max-width: 100% !important;
                    padding: 0 !important;
                    margin-top: 8px !important;
                }
                
                /* Spacer reduction */
                .h-6 {
                    height: 8px !important;
                }
            }
                    padding: 12px 20px !important;
                    font-size: 13px !important;
                    min-width: 120px;
                    text-align: center;
                }
                
                /* Button group - full width stack on mobile */
                .btn-group {
                    flex-direction: column !important;
                    width: 100% !important;
                    gap: 10px !important;
                }
                
                .btn-group a,
                .btn-group button {
                    width: 100% !important;
                }
                
                /* Followups container */
                #followups {
                    width: 100% !important;
                    max-width: 100% !important;
                    padding: 0 !important;
                }
                
                /* Header */
                header.w-full {
                    max-width: 100% !important;
                    margin-bottom: 1rem !important;
                }
            }
            
            /* ===========================================
               EXTRA SMALL: Tiny phones (380px and down)
               =========================================== */
            @media (max-width: 380px) {
                .welcome-content-wrapper {
                    padding-top: 30px !important;
                }
                
                .main-card {
                    padding: 12px 10px !important;
                    border-radius: 12px !important;
                }
                
                .main-card h2 {
                    font-size: 18px !important;
                }
                
                .main-card h4 {
                    font-size: 10px !important;
                    margin-bottom: 8px !important;
                }
                
                .main-card p {
                    font-size: 10px !important;
                    line-height: 1.4 !important;
                }
                
                .notice-box {
                    padding: 8px 10px !important;
                    font-size: 9px !important;
                }
                
                .toggle-container {
                    padding: 8px 10px !important;
                    border-radius: 10px !important;
                    margin-top: 8px !important;
                }
                
                .toggle-container > span {
                    font-size: 9px !important;
                }
                
                .toggle-buttons button {
                    padding: 6px 14px !important;
                    font-size: 11px !important;
                }
                
                .action-btn {
                    padding: 8px 10px !important;
                    font-size: 11px !important;
                }
            }
            
            /* ===========================================
               LANDSCAPE MODE (short viewport)
               =========================================== */
            @media (max-height: 500px) and (orientation: landscape) {
                body.welcome-page {
                    padding-top: calc(var(--header-height-mobile, 80px) + 8px) !important;
                    justify-content: flex-start !important;
                }
                
                .main-card {
                    padding: 16px !important;
                }
                
                .main-card h2 {
                    font-size: 18px !important;
                    margin-bottom: 4px !important;
                }
                
                .main-card h4 {
                    font-size: 11px !important;
                    margin-bottom: 12px !important;
                }
                
                .main-card p {
                    font-size: 11px !important;
                    margin-bottom: 12px !important;
                }
                
                .notice-box {
                    padding: 10px 12px !important;
                }
            }
            
            /* ===========================================
               SAFE AREA INSETS (notch/home indicator)
               Use @verbatim to prevent Blade from parsing @supports
               =========================================== */
            @supports(padding: env(safe-area-inset-bottom)) {
                body.welcome-page {
                    padding-bottom: calc(30px + env(safe-area-inset-bottom)) !important;
                    padding-left: calc(12px + env(safe-area-inset-left)) !important;
                    padding-right: calc(12px + env(safe-area-inset-right)) !important;
                }
            }
            
            /* ===========================================
               TABLET: Medium devices (641px to 1024px)
               =========================================== */
            @media (min-width: 641px) and (max-width: 1024px) {
                body.welcome-page {
                    padding: 24px !important;
                    padding-top: calc(var(--header-height, 115px) + 24px) !important;
                }
                
                .main-card,
                .toggle-container {
                    max-width: 500px !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }
            }
        </style>
    </head>
    <body class="welcome-page">
        <?php echo $__env->make('partials.vits_branding', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <header class="w-full max-w-full sm:max-w-lg lg:max-w-4xl text-sm mb-4 sm:mb-6 not-has-[nav]:hidden mx-auto">
        </header>
        
        <!-- Main centered container - Fixed position for viewport centering -->
        <div class="welcome-content-wrapper" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: calc(100% - 24px); max-width: 480px; padding-top: 40px; box-sizing: border-box; z-index: 10;">
            <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
                <div class="main-card" style="background: rgba(255, 255, 255, 0.95); border-radius: 20px; padding: 24px 28px; width: 100%; max-width: 480px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1) inset; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); box-sizing: border-box;">
  
                <h2 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 24px; margin: 0 0 6px 0; font-weight: 800; letter-spacing: -0.02em;">Welcome</h2>
                <h4 style="color: #6D28D9; font-size: 13px; margin: 0 0 14px 0; font-weight: 600; opacity: 0.9;">
                    Social Contract Report Submission and Monitoring System
                </h4>

                <p style="font-size: 12px; line-height: 1.6; color: #4a5568; text-align: justify; margin-bottom: 14px;">
                    This is a secure digital platform designed specifically for the IT students of Pamantasan ng Lungsod ng Valenzuela (PLV). 
                    Through this system, students can log in to safely access their accumulated hours, duty records, and compliance status without 
                    the need for paper forms. Supervisors and administrators are responsible for directly recording attendance, duty details, 
                    and rendered hours, ensuring accuracy, efficiency, and transparency in monitoring student requirements.
                </p>

                <div class="notice-box" style="font-size: 12px; color: #744210; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b; padding: 10px 12px; border-radius: 8px; box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <span style="font-size: 18px; flex-shrink: 0;">⚠️</span>
                        <div>
                            <span style="font-weight: 700; color: #92400e; display: block; margin-bottom: 4px;">Important Notice:</span>
                            This system is exclusively for <strong style="color: #6D28D9;">PLV IT students</strong>. Unauthorized access, misuse, or falsification of records 
                            is strictly prohibited and may lead to disciplinary or legal action.
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
                <div class="h-6"></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Pill toggle role selector (below the main card) -->
            <div class="w-full" style="margin-top: 20px; max-width: 480px; box-sizing: border-box;">
                <!-- Modern toggle with glass morphism --> 
                <div class="toggle-container" style="background: rgba(255, 255, 255, 0.95); border-radius: 60px; padding: 10px 16px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 0 0 1px rgba(255, 255, 255, 0.1) inset; gap: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3);">
                    <span style="color: #4a5568; font-size: 11px; font-weight: 600; flex: 1; min-width: 0; padding-left: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">Are you a PLV Information Technology Student?</span>
                    <div id="toggle" class="toggle-buttons" style="display: flex; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50px; padding: 4px; align-items: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);">
                        <button id="yesBtn" type="button" style="background: rgba(255, 255, 255, 0.3); border: none; color: white; border-radius: 50px; padding: 8px 24px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); white-space: nowrap; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">Yes</button>
                        <button id="noBtn" type="button" style="background: transparent; border: none; color: rgba(255, 255, 255, 0.8); border-radius: 50px; padding: 8px 24px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); white-space: nowrap;">No</button>
                    </div>
                </div>

                <div id="followups" style="margin-top: 14px; display: none; flex-direction: column; align-items: center; justify-content: flex-start; text-align: center; width: 100%; max-width: 400px; margin-left: auto; margin-right: auto;">
                        <!-- Student Exists Section -->
                        <div id="student-exists" class="followup-section" 
                          style="display: none; background: transparent; border-radius: 16px; padding: 16px 20px; width: 100%; margin-top: 16px;">
                            <p style="margin-bottom: 12px; font-weight: 600; font-size: 13px; color: rgba(255, 255, 255, 0.95); text-align: center;">Do you have an existing account?</p>
                            <div class="btn-group" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                                <a href="#" onclick="event.preventDefault(); clearSessionAndLogin();" class="action-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); cursor: pointer;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)';">
                                    Yes — Login
                                </a>
                                <a href="#" onclick="event.preventDefault(); clearSessionAndRegister();" class="action-btn" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4); cursor: pointer;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(245, 87, 108, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(245, 87, 108, 0.4)';">
                                    No — Sign up
                                </a>
                            </div>
                        </div>
                        <!-- Non-Student Select Section -->
                        <div id="nonstudent-select" class="followup-section" 
                          style="display: none; background: transparent; border-radius: 16px; padding: 16px 20px; width: 100%; margin-top: 16px;">
                            <p style="margin-bottom: 12px; font-weight: 600; font-size: 13px; color: rgba(255, 255, 255, 0.95); text-align: center;">Select your role</p>
                            <div class="btn-group" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                                <button type="button" onclick="clearSessionAndGoToAdmin()" class="action-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(102, 126, 234, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)';">
                                    Admin
                                </button>
                                <button type="button" onclick="clearSessionAndGoToSuperAdmin()" class="action-btn" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 12px rgba(250, 112, 154, 0.4);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(250, 112, 154, 0.5)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(250, 112, 154, 0.4)';">
                                    Super Admin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div><!-- Close inner flex container -->
        </div><!-- Close welcome-content-wrapper -->
    </body>
    <script>
      // Simple toggle script (adapted from user-provided example)
        (function(){
        const yesBtn = document.getElementById('yesBtn');
        const noBtn = document.getElementById('noBtn');
        const studentExists = document.getElementById('student-exists');
        const nonstudentSelect = document.getElementById('nonstudent-select');
        const followups = document.getElementById('followups');

        function resetButtons(){
          if (yesBtn) {
            yesBtn.style.background = 'transparent';
            yesBtn.style.color = 'rgba(255, 255, 255, 0.8)';
            yesBtn.style.boxShadow = 'none';
          }
          if (noBtn) {
            noBtn.style.background = 'transparent';
            noBtn.style.color = 'rgba(255, 255, 255, 0.8)';
            noBtn.style.boxShadow = 'none';
          }
          // hide followups container
          if (followups) followups.style.display = 'none';
          if (studentExists) studentExists.style.display = 'none';
          if (nonstudentSelect) nonstudentSelect.style.display = 'none';
        }

        function showStudent(){
          resetButtons();
          if (yesBtn) {
            yesBtn.style.background = 'rgba(255, 255, 255, 0.3)';
            yesBtn.style.color = 'white';
            yesBtn.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
          }
          if (followups) followups.style.display = 'flex';
          if (studentExists) studentExists.style.display = 'block';
          if (nonstudentSelect) nonstudentSelect.style.display = 'none';
        }

        function showNonStudent(){
          resetButtons();
          if (noBtn) {
            noBtn.style.background = 'rgba(255, 255, 255, 0.3)';
            noBtn.style.color = 'white';
            noBtn.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
          }
          if (followups) followups.style.display = 'flex';
          if (nonstudentSelect) nonstudentSelect.style.display = 'block';
          if (studentExists) studentExists.style.display = 'none';
        }

        if (yesBtn) yesBtn.addEventListener('click', function(e){ e.preventDefault(); showStudent(); });
        if (noBtn) noBtn.addEventListener('click', function(e){ e.preventDefault(); showNonStudent(); });

        // init: ensure followups and sections hidden
        if (followups) followups.style.display = 'none';
        if (studentExists) studentExists.style.display = 'none';
        if (nonstudentSelect) nonstudentSelect.style.display = 'none';
      })();

      // Function to clear any existing session and go to login
      function clearSessionAndLogin() {
        try {
          // Clear localStorage except theme preferences
          var adminTheme = localStorage.getItem('scms_admin_theme');
          var superadminTheme = localStorage.getItem('scms_superadmin_theme');
          var studentTheme = localStorage.getItem('scms_student_theme');
          localStorage.clear();
          if (adminTheme) localStorage.setItem('scms_admin_theme', adminTheme);
          if (superadminTheme) localStorage.setItem('scms_superadmin_theme', superadminTheme);
          if (studentTheme) localStorage.setItem('scms_student_theme', studentTheme);
          // Clear sessionStorage
          sessionStorage.clear();
        } catch(e) { 
          console.log('Error clearing storage:', e); 
        }
        
        // Redirect to login page
        window.location.href = '<?php echo e(route('login')); ?>';
      }

      // Function to clear any existing session and go to register
      function clearSessionAndRegister() {
        try {
          // Clear localStorage except theme preferences
          var adminTheme = localStorage.getItem('scms_admin_theme');
          var superadminTheme = localStorage.getItem('scms_superadmin_theme');
          var studentTheme = localStorage.getItem('scms_student_theme');
          localStorage.clear();
          if (adminTheme) localStorage.setItem('scms_admin_theme', adminTheme);
          if (superadminTheme) localStorage.setItem('scms_superadmin_theme', superadminTheme);
          if (studentTheme) localStorage.setItem('scms_student_theme', studentTheme);
          // Clear sessionStorage
          sessionStorage.clear();
        } catch(e) { 
          console.log('Error clearing storage:', e); 
        }
        
        // Redirect to register page
        window.location.href = '<?php echo e(route('register')); ?>';
      }

      // Function to clear session and redirect to Admin login
      function clearSessionAndGoToAdmin() {
        try {
          // Send logout request via beacon (non-blocking)
          var logoutUrl = '<?php echo e(route('admin.logout')); ?>';
          var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
          var formData = new FormData();
          formData.append('_token', csrfToken);
          
          if (navigator.sendBeacon) {
            navigator.sendBeacon(logoutUrl, formData);
          }
          
          // Clear all storage except theme preferences
          var adminTheme = localStorage.getItem('scms_admin_theme');
          var superadminTheme = localStorage.getItem('scms_superadmin_theme');
          var studentTheme = localStorage.getItem('scms_student_theme');
          localStorage.clear();
          if (adminTheme) localStorage.setItem('scms_admin_theme', adminTheme);
          if (superadminTheme) localStorage.setItem('scms_superadmin_theme', superadminTheme);
          if (studentTheme) localStorage.setItem('scms_student_theme', studentTheme);
          sessionStorage.clear();
        } catch(e) { 
          console.log('Error clearing storage:', e); 
        }
        
        // Redirect to Admin login immediately
        window.location.href = '<?php echo e(route('admin.login')); ?>';
      }

      // Function to clear session and redirect to Super Admin login
      function clearSessionAndGoToSuperAdmin() {
        try {
          // Send logout request via beacon (non-blocking)
          var logoutUrl = '<?php echo e(route('superadmin.logout')); ?>';
          var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
          var formData = new FormData();
          formData.append('_token', csrfToken);
          
          if (navigator.sendBeacon) {
            navigator.sendBeacon(logoutUrl, formData);
          }
          
          // Clear all storage except theme preferences
          var adminTheme = localStorage.getItem('scms_admin_theme');
          var superadminTheme = localStorage.getItem('scms_superadmin_theme');
          var studentTheme = localStorage.getItem('scms_student_theme');
          localStorage.clear();
          if (adminTheme) localStorage.setItem('scms_admin_theme', adminTheme);
          if (superadminTheme) localStorage.setItem('scms_superadmin_theme', superadminTheme);
          if (studentTheme) localStorage.setItem('scms_student_theme', studentTheme);
          sessionStorage.clear();
        } catch(e) { 
          console.log('Error clearing storage:', e); 
        }
        
        // Redirect to super admin login immediately
        window.location.href = '<?php echo e(route('superadmin.login')); ?>';
      }

            // Header show/hide on scroll: hide when scrolling down, show when scrolling up
            (function(){
                function setupHeaderScroll() {
                    const header = document.getElementById('site-header');
                    if (!header) return;
                    
                    let lastScroll = window.pageYOffset || document.documentElement.scrollTop;
                    let ticking = false;
                    const threshold = 22; // larger px of movement before toggling to avoid jitter
                    let lastToggle = 0;
                    const minToggleInterval = 120; // ms between visibility toggles

                    function onScroll(){
                        const current = window.pageYOffset || document.documentElement.scrollTop;
                        const diff = current - lastScroll;
                        if (Math.abs(diff) < threshold) return; // ignore tiny moves

                        const now = Date.now();
                        if (now - lastToggle < minToggleInterval) {
                            // skip toggles that happen too quickly
                            lastScroll = current;
                            ticking = false;
                            return;
                        }

                        if (diff > 0) {
                            // scrolling down
                            header.classList.add('header-hidden');
                            header.style.opacity = '0';
                        } else {
                            // scrolling up
                            header.classList.remove('header-hidden');
                            header.style.opacity = '0.99';
                        }

                        lastToggle = now;
                        lastScroll = current <= 0 ? 0 : current; // for Mobile
                        ticking = false;
                    }

                    window.addEventListener('scroll', function(){
                        if (!ticking) {
                            window.requestAnimationFrame(onScroll);
                            ticking = true;
                        }
                    }, { passive: true });
                }

                // Initialize when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', setupHeaderScroll, { once: true });
                } else {
                    setupHeaderScroll();
                }
            })();
    </script>
    <style>
      /* Ensure the footer is visible above the fixed welcome card */
      .scms-footer {
        position: fixed;
        bottom: 12px;
        left: 0;
        width: 100%;
        z-index: 60; /* above welcome-content-wrapper z-index:10 */
        pointer-events: auto;
        text-align: center;
        background: transparent;
        -webkit-backdrop-filter: none;
      }
      @media (max-width: 640px) {
        .scms-footer { bottom: 8px; font-size: 0.85em; }
      }
    </style>
    <?php echo $__env->make('partials.footer_partial', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </body>
  </html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\welcome.blade.php ENDPATH**/ ?>