<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Student Contract Management System</title>
        <script>
            // Apply saved theme ASAP to reduce flash
            (function(){
                try {
                    var saved = localStorage.getItem('scms_theme');
                    if (saved === 'dark' || saved === 'light') {
                        document.documentElement.setAttribute('data-theme', saved);
                    }
                } catch(_){ }
            })();
        </script>
        <!-- Configure Tailwind BEFORE loading the CDN to avoid incorrect initial render -->
        <script>
            tailwind = typeof tailwind === 'object' ? tailwind : {};
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'background-light': '#EDF1FA',
                            'primary-purple': '#6D28D9',
                            'primary-purple-hover': '#5B21B6',
                            'text-header': '#2B3674',
                            'text-muted': '#707EAE',
                            'badge-pending-text': '#E29C44',
                            'badge-pending-bg': '#FAEAD0',
                            'badge-verified-text': '#399552',
                            'badge-verified-bg': '#CCEED6',
                            'badge-rejected-text': '#CC525D',
                            'badge-rejected-bg': '#FFD7DB',
                            'success-green': '#4CAF50',
                            'success-green-hover': '#45a049',
                            'danger-red': '#CC525D',
                            'danger-red-hover': '#b33e46',
                        },
                        fontFamily: { sans: ['Inter', 'sans-serif'] }
                    }
                }
            };
        </script>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css" />
        <!-- Load DaisyUI CSS AFTER Tailwind to preserve component styles -->
        <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css" />
        <?php
            $iconCandidates = [
                 'vits_white.png',
                 'storage/vits_whites.png',
                 'vits_whites.png',
                 'vitswhite.png',
                 'vitslogo.png',
                'public/storage/vits_white.png',
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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Ensure active nav exactly matches primary purple */
        .active-nav { background-color: #6D28D9; color: #ffffff; border-radius: 0.5rem; }
        .flex-1-dynamic { flex: 1 1 auto; min-height: 0; }
        .content-area-auto { height: auto; max-height: 100%; }
        /* Strong button color override to avoid DaisyUI theme side-effects */
        .btn-primary-purple { background-color: #6D28D9 !important; color: #ffffff !important; border-color: transparent !important; }
        .btn-primary-purple:hover { background-color: #5B21B6 !important; color: #ffffff !important; }
        .btn-primary-purple:focus { outline: none !important; box-shadow: 0 0 0 2px rgba(109,40,217,0.35) !important; }
        .btn-primary-purple:active { background-color: #4C1D95 !important; color: #ffffff !important; }
        .btn-primary-purple svg { stroke: #ffffff !important; }
    /* Success button overrid   e: keep consistent across themes and over DaisyUI */
        .btn.bg-success-green,
        .btn-success-green { background-color: #4CAF50 !important; color: #ffffff !important; border-color: transparent !important; }
        .btn.bg-success-green:hover,
        .btn-success-green:hover { background-color: #45a049 !important; color: #ffffff !important; }
        .btn.bg-success-green:active,
        .btn-success-green:active { background-color: #3d9341 !important; color: #ffffff !important; }
        .btn.bg-success-green:focus,
        .btn-success-green:focus { outline: none !important; box-shadow: 0 0 0 2px rgba(34,197,94,0.35) !important; }
        /* Consistent status badges (independent from DaisyUI badge theme) */
        .scms-badge { display: inline-flex; align-items: center; justify-content: center; font-weight: 600; border-radius: 9999px; padding: 0.25rem 0.5rem; font-size: 0.75rem; line-height: 1; border: 0 !important; }
        .scms-badge--pending { background-color: #FAEAD0 !important; color: #E29C44 !important; }
        .scms-badge--verified { background-color: #B2F5EA !important; color: #0D9488 !important; }
        .scms-badge--approved { background-color: #C8E6C9 !important; color: #2E7D32 !important; }
        .scms-badge--rejected { background-color: #FFD7DB !important; color: #CC525D !important; }
        /* Optional utility for static-looking inputs */
        .static-input { border: none !important; box-shadow: none !important; padding-left: 0 !important; background-color: transparent !important; cursor: default !important; }
        /* Page background image */
        .bg-custom {
            background-color: #EDF1FA; /* fallback */
            background-image: url('<?php echo e(asset('vits_bg_white.png')); ?>');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .bg-gradient-primary-purple { background-image: linear-gradient(to bottom, #bbacffff, #6D28D9); }
        /* Gradients for summary cards */
        .bg-gradient-approved { background-image: linear-gradient(to bottom, #DCFCE7, #81FFAC); }
        .bg-gradient-verified { background-image: linear-gradient(to bottom, #D1FAE5, #5EEAD4); }
        .bg-gradient-pending { background-image: linear-gradient(to bottom, #FFF4DE, #FFE0A2); }
        .bg-gradient-rejected { background-image: linear-gradient(to bottom, #FFE2E5, #FFB7BE); }
        /* Notification bell dot: force consistent color and visibility */
        .scms-notif-dot { width: 0.5rem; height: 0.5rem; background-color: #6D28D9 !important; border: 2px solid #ffffff !important; border-radius: 9999px; box-sizing: content-box; }
    </style>
    <style>
        /* Toast root tweaks: allow individual toasts to receive pointer events */
        #toast-root { position: fixed; right: 1rem; bottom: 1rem; z-index: 2000; display: flex; flex-direction: column; gap: .75rem; pointer-events: none; }
        #toast-root .alert { pointer-events: auto; }
        /* Refined toast look */
        .scms-toast { position: relative; display: inline-flex; align-items: center; gap: .5rem; padding: .625rem .875rem; border-radius: 9999px; color: #fff; box-shadow: 0 10px 24px rgba(0,0,0,.18), 0 2px 6px rgba(0,0,0,.08); border: 1px solid rgba(255,255,255,0.08); max-width: 520px; }
        .scms-toast--success { background: linear-gradient(90deg, #16A34A, #22C55E); }
        .scms-toast--error { background: linear-gradient(90deg, #EF4444, #DC2626); }
        .scms-toast--info { background: linear-gradient(90deg, #6D28D9, #7C3AED); }
        .scms-toast__msg { font-weight: 600; font-size: .925rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .scms-toast__close { margin-left: .25rem; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 9999px; background: rgba(255,255,255,0.12); color: #fff; border: 0; cursor: pointer; transition: background .18s ease, transform .12s ease; }
        .scms-toast__close:hover { background: rgba(255,255,255,0.22); transform: translateY(-1px); }
        .scms-toast__progress { position: absolute; left: 6px; right: 6px; bottom: 4px; height: 3px; border-radius: 9999px; background: rgba(255,255,255,0.55); transform-origin: left center; }
        @keyframes scms-toast-progress { from { transform: scaleX(1); } to { transform: scaleX(0); } }
        [data-theme="dark"] .scms-toast { border-color: rgba(255,255,255,0.14); box-shadow: 0 10px 24px rgba(0,0,0,.35), 0 2px 6px rgba(0,0,0,.2); }
    </style>
    <style>
        /* Dark theme: turn typical dark text utilities to white for readability */
        [data-theme="dark"] body { color: #ffffff; }
        [data-theme="dark"] .text-black,
        [data-theme="dark"] .text-gray-900,
        [data-theme="dark"] .text-gray-800,
        [data-theme="dark"] .text-gray-700,
        [data-theme="dark"] .text-gray-600,
        [data-theme="dark"] .text-gray-500,
        [data-theme="dark"] .text-text-header,
        [data-theme="dark"] .text-text-muted,
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] h5,
        [data-theme="dark"] h6,
        [data-theme="dark"] p,
        [data-theme="dark"] span,
        [data-theme="dark"] label,
        [data-theme="dark"] td,
        [data-theme="dark"] th,
        [data-theme="dark"] a { color: #ffffff !important; }
        /* Preserve status colors in dark mode (do not force white for these) */
        [data-theme="dark"] .scms-badge--pending { background-color: #ff9d26ff !important; color: #ffffffff !important; }
        [data-theme="dark"] .scms-badge--verified { background-color: #14B8A6 !important; color: #ffffffff!important; }
        [data-theme="dark"] .scms-badge--approved { background-color: #4CAF50 !important; color: #ffffffff!important; }
        [data-theme="dark"] .scms-badge--rejected { background-color: #b8000fff !important; color: #ffffffff!important; }
        /* Summary labels and inline status text */
        [data-theme="dark"] .text-yellow-800 { color: #ffcd91ff !important; }
        [data-theme="dark"] .text-green-800 { color: #a3ffbcff !important; }
        [data-theme="dark"] .text-red-800 { color: #ffc8c8ff !important; }
        [data-theme="dark"] .text-badge-verified-text { color: #a3ffbcff !important; }
        [data-theme="dark"] .text-badge-rejected-text { color: #ffc8c8ff !important; }
        [data-theme="dark"] .text-badge-pending-text { color: #ffcd91ff !important; }
        /* Preserve success-green button styling in dark mode */
        [data-theme="dark"] .btn.bg-success-green,
        [data-theme="dark"] .btn-success-green { background-color: #4CAF50 !important; color: #ffffff !important; border-color: transparent !important; }
        [data-theme="dark"] .btn.bg-success-green:hover,
        [data-theme="dark"] .btn-success-green:hover { background-color: #45a049 !important; color: #ffffff !important; }
        [data-theme="dark"] .btn.bg-success-green:active,
        [data-theme="dark"] .btn-success-green:active { background-color: #3d9341 !important; color: #ffffff !important; }
        [data-theme="dark"] .btn.bg-success-green:focus,
        [data-theme="dark"] .btn-success-green:focus { outline: none !important; box-shadow: 0 0 0 2px rgba(34,197,94,0.45) !important; }
    </style>
    <style>
        /* Custom Progress Stepper Styles - Horizontal inline */
        .record-details-row {
            background-color: #f9fafb;
            border-top: 2px solid #e5e7eb;
        }
        [data-theme="dark"] .record-details-row {
            background-color: #1f2937;
            border-top: 2px solid #374151;
        }
        
        .steps-horizontal {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            padding: 2rem;
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
            max-width: 200px;
        }
        
        .step-circle {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.25rem;
            background-color: #e5e7eb;
            color: #6b7280;
            z-index: 2;
            position: relative;
        }
        
        .step-circle.active {
            background-color: #3B82F6;
            color: white;
        }
        
        .step-circle.completed {
            background-color: #4CAF50;
            color: white;
        }
        
        .step-circle.rejected {
            background-color: #EF4444;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            z-index: 10;
        }
        
        .step-circle.rejected:hover {
            background-color: #DC2626;
            transform: scale(1.1);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        
        .step-circle.rejected:active {
            transform: scale(1.05);
        }
        
        .step-circle.pending {
            background-color: #F59E0B;
            color: white;
        }
        
        .step-label {
            margin-top: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-align: center;
        }
        
        .step-sublabel {
            font-size: 0.75rem;
            color: #6b7280;
            text-align: center;
            margin-top: 0.25rem;
        }
        
        .step-connector {
            position: absolute;
            top: 1.5rem;
            left: 50%;
            right: -50%;
            height: 3px;
            background-color: #e5e7eb;
            z-index: 1;
        }
        
        .step-item:last-child .step-connector {
            display: none;
        }
        
        .step-connector.active {
            background-color: #3B82F6;
        }
        
        .step-connector.completed {
            background-color: #4CAF50;
        }
        
        [data-theme="dark"] .step-circle {
            background-color: #374151;
            color: #9ca3af;
        }
        /* Force variant states to keep bright colors even in dark theme */
        [data-theme="dark"] .step-circle.active {
            background-color: #3B82F6 !important; /* blue */
            color: #ffffff !important;
        }
        [data-theme="dark"] .step-circle.completed {
            background-color: #4CAF50 !important; /* green */
            color: #ffffff !important;
        }
        [data-theme="dark"] .step-circle.rejected {
            background-color: #EF4444 !important; /* red */
            color: #ffffff !important;
            box-shadow: none !important;
        }
        [data-theme="dark"] .step-circle.pending {
            background-color: #F59E0B !important; /* yellow/orange */
            color: #ffffff !important;
        }
        
        [data-theme="dark"] .step-sublabel {
            color: #9ca3af;
        }
        
        [data-theme="dark"] .step-connector {
            background-color: #374151;
        }
        /* Force connector colors to match light theme variants in dark mode */
        [data-theme="dark"] .step-connector.active {
            background-color: #3B82F6 !important; /* blue */
        }
        [data-theme="dark"] .step-connector.completed {
            background-color: #4CAF50 !important; /* green */
        }
        
        /* Status Modal Animations */
        #status_records_modal[open] {
            animation: modalFadeIn 0.3s ease-out;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Enhanced table styling for modal */
        #status_records_modal .table tbody tr:hover {
            background-color: rgba(109, 40, 217, 0.05);
        }
        
        [data-theme="dark"] #status_records_modal .table tbody tr:hover {
            background-color: rgba(109, 40, 217, 0.15);
        }
    </style>
    <style>
                /* Dark theme: card/background overrides */
        [data-theme="dark"] .bg-custom {
            background-color: #0b0f19; /* deeper fallback */
            background-image: url('<?php echo e(asset('storage/vits_bg_black.png')); ?>');
        }
         /* Tables in dark mode */
        [data-theme="dark"] .table thead,
        [data-theme="dark"] .table thead tr,
        [data-theme="dark"] .table thead th { background-color: #374151 !important; }
        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td { border-color: #374151 !important; }
        [data-theme="dark"] .bg-white { background-color: #1f2937 !important; } /* gray-800 */
        [data-theme="dark"] .bg-gray-100 { background-color: #374151 !important; } /* gray-700 */
        [data-theme="dark"] .border-gray-200 { border-color: #374151 !important; } /* gray-700 */
        [data-theme="dark"] .bg-base-100 { background-color: #111827 !important; } /* gray-900 for DaisyUI surfaces */
        /* Replace pastel gradients with solid dark gray in dark mode */
        [data-theme="dark"] .bg-gradient-approved { background-image: linear-gradient(to top, #6D28D9, #81FFAC); }
        [data-theme="dark"] .bg-gradient-verified { background-image: linear-gradient(to top, #6D28D9, #5EEAD4); }
        [data-theme="dark"] .bg-gradient-pending { background-image: linear-gradient(to top, #6D28D9, #FFE0A2); }
        [data-theme="dark"] .bg-gradient-rejected { background-image: linear-gradient(to top, #6D28D9, #FFB7BE); }
        /* FAQ active/open state: force primary purple background and white text for the title */
        /* Make active/open FAQ whole item purple with white text and subtle shadow */
        .collapse:focus-within,
        .collapse.collapse-open,
        .collapse.open,
        .collapse[aria-expanded="true"] {
            background-color: #5B21B6 !important; /* slightly darker primary */
            border-color: rgba(91,33,182,0.25) !important;
            box-shadow: 0 6px 18px rgba(91,33,182,0.12) !important;
        }
        /* Make the expanded content readable on the purple background */
        .collapse:focus-within .collapse-content,
        .collapse.collapse-open .collapse-content,
        .collapse.open .collapse-content,
        .collapse[aria-expanded="true"] .collapse-content {
            color: #ffffff !important;
        }
        .collapse:focus-within .collapse-content p,
        .collapse.collapse-open .collapse-content p,
        .collapse.open .collapse-content p,
        .collapse[aria-expanded="true"] .collapse-content p {
            color: rgba(255,255,255,0.92) !important;
        }
        .collapse:focus-within .collapse-content a,
        .collapse.collapse-open .collapse-content a,
        .collapse.open .collapse-content a,
        .collapse[aria-expanded="true"] .collapse-content a {
            color: #ffffff !important;
            text-decoration: underline !important;
        }
        .collapse:focus-within > .collapse-title,
        .collapse.collapse-open > .collapse-title,
        .collapse.open > .collapse-title,
        .collapse[aria-expanded="true"] > .collapse-title {
            background-color: transparent !important;
            color: #ffffff !important;
        }
        /* Also ensure the arrow/chevron is white when active */
        .collapse:focus-within > .collapse-title svg,
        .collapse.collapse-open > .collapse-title svg,
        .collapse.open > .collapse-title svg,
        .collapse[aria-expanded="true"] > .collapse-title svg {
            color: #ffffff !important;
            stroke: #ffffff !important;
        }
        /* Checkboxes: purple accent in light; custom dark styling */
        .record-checkbox { accent-color: #6D28D9 !important; }
        /* Ensure light theme is purple even if accent-color unsupported */
        :root:not([data-theme="dark"]) .record-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border: 2px solid #9ca3af; /* gray-400 */
            background-color: #ffffff;
            border-radius: 0.25rem;
            display: inline-grid; place-content: center;
            transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        :root:not([data-theme="dark"]) .record-checkbox:focus { outline: none; box-shadow: 0 0 0 2px rgba(109,40,217,0.35); }
        :root:not([data-theme="dark"]) .record-checkbox:checked { background-color: #6D28D9; border-color: #6D28D9; }
        :root:not([data-theme="dark"]) .record-checkbox:checked::after {
            content: "";
            width: 0.25rem; height: 0.5rem;
            border: solid #ffffff; border-width: 0 2px 2px 0; transform: rotate(45deg);
        }
        [data-theme="dark"] .record-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border: 2px solid #374151; /* gray-700 */
            background-color: #111827;  /* gray-900 */
            border-radius: 0.25rem;
            display: inline-grid; place-content: center;
            transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        [data-theme="dark"] .record-checkbox:focus { outline: none; box-shadow: 0 0 0 2px rgba(109,40,217,0.45); }
        [data-theme="dark"] .record-checkbox:checked { background-color: #6D28D9; border-color: #6D28D9; }
        [data-theme="dark"] .record-checkbox:checked::after {
            content: "";
            width: 0.25rem; height: 0.5rem;
            border: solid #ffffff; border-width: 0 2px 2px 0; transform: rotate(45deg);
        }
        [data-theme="dark"] .record-checkbox:disabled { opacity: .4; cursor: not-allowed; }
        /* Status filter dropdown styles */
        #status-filter-dropdown .btn{color:#707EAE;min-height:auto;height:auto;padding:0.25rem 0.5rem}
        #status-filter-dropdown .btn:hover{color:#6D28D9;background-color:rgba(109,40,217,0.1)}
        #status-filter-dropdown svg{fill:#707EAE}
        #status-filter-dropdown .btn:hover svg{fill:#6D28D9}
        #status-filter-dropdown{position:relative!important}
        #status-filter-dropdown .dropdown-content{position:fixed!important;box-shadow:0 10px 25px rgba(0,0,0,0.15)!important;z-index:9999!important}
        .dropdown-content li a{font-size:0.875rem;padding:0.5rem 1rem}
        .dropdown-content li a:hover{background-color:#6D28D9;color:#fff}
        [data-theme="dark"] .dropdown-content{background-color:#1f2937!important;border:1px solid #374151}
        [data-theme="dark"] .dropdown-content li a:hover{background-color:#6D28D9}
        [data-theme="dark"] #status-filter-dropdown .btn{color:#fff}
        [data-theme="dark"] #status-filter-dropdown .btn:hover{color:#6D28D9;background-color:rgba(109,40,217,0.2)}
        [data-theme="dark"] #status-filter-dropdown svg{fill:#fff}
        [data-theme="dark"] #status-filter-dropdown .btn:hover svg{fill:#6D28D9}
        thead{overflow:visible!important}
        table{overflow:visible!important}
        #record-status-page .overflow-x-auto{overflow:visible!important}
    </style>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="min-h-screen bg-custom">
    <?php echo $__env->make('partials.auto_logout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php
        // Combined: compute base path and user initials for header/avatar
        $BASE_PATH = rtrim(parse_url(url('/'), PHP_URL_PATH) ?? '', '/');

        $fullName = trim(auth()->user()->name ?? '');
        $nameWords = $fullName !== '' ? preg_split('/\s+/', $fullName) : [];
        $initials = '';
        if (!empty($nameWords)) {
            $initials = mb_strtoupper(mb_substr($nameWords[0], 0, 1));
            if (isset($nameWords[1]) && mb_strlen($nameWords[1]) > 0) {
                $initials .= mb_strtoupper(mb_substr($nameWords[1], 0, 1));
            }
        }
    ?>
    <div class="flex p-4 gap-4 min-h-screen"> 
        <aside class="flex flex-col w-64 bg-white rounded-2xl p-4 shadow-sm sticky top-4 self-start h-[calc(100vh-2rem)] overflow-hidden">
            <div class="flex flex-col items-center text-center p-4 border-b border-gray-200">
                <div class="avatar placeholder mb-3">
                    <div class="w-24 h-24 rounded-full ring ring-[#6D28D9] ring-offset-2 ring-offset-base-100 bg-[#6D28D9] text-white flex items-center justify-center select-none" title="<?php echo e(auth()->user()->name); ?>" aria-label="<?php echo e(auth()->user()->name); ?>">
                        <span class="text-3xl font-bold leading-none"><?php echo e($initials); ?></span>
                    </div>
                </div>
                <h2 class="font-bold text-lg"><?php echo e(auth()->user()->name); ?></h2>
                <p class="text-sm text-gray-500">Student Number: <?php echo e(auth()->user()->student_id ?? '—'); ?></p>
            </div>

            <ul class="menu p-0 my-4 flex-grow">
                <li>
                    <a class="py-3" id="nav-dashboard" onclick="showPage('dashboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="py-3" id="nav-record-status" onclick="showPage('record-status')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Record Status
                    </a>
                </li>
            </ul>

            <ul class="menu p-0">
                <li>
                    <a class="py-3 pl-2" id="nav-faqs" onclick="showPage('faqs')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        FAQs
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2 pr-0 w-full text-left flex items-center gap-2 min-h-0" id="nav-profile" onclick="showPage('profile')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Profile
                    </a>
                </li>
                <li>
                    <form id="logout-form-visible" action="<?php echo e(route('logout')); ?>" method="POST" class="m-0 p-0 pl-2 pr-0" novalidate>
                        <?php echo csrf_field(); ?>
                        <button id="logout-button-visible" type="button" class="py-3 px-0 w-full text-left flex items-center gap-2 min-h-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Log Out
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <main class="flex-1 flex flex-col gap-6" id="page-container">
            <div class="flex justify-between items-center p-4">
                <div id="main-greeting" class="text-white"> 
                    <h4 class="text-4xl font-bold text-primary-purple">Student Dashboard</h4>
                </div>
                
                <div class="dropdown dropdown-end" id="notification-dropdown-container">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle" aria-label="Notifications" title="Notifications">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span id="notif-dot" class="indicator-item scms-notif-dot hidden" aria-hidden="true"></span>
                        </div>
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[1000] menu p-0 shadow bg-base-100 rounded-box w-80 mt-4 overflow-hidden max-h-[500px] overflow-y-auto">
                        
                        <li class="p-4 font-bold text-gray-700 dark:text-black border-b sticky top-0 bg-base-100 z-10">Notifications</li>

                        <div id="notifications-list">
                            <!-- Notifications will be loaded here dynamically -->
                            <li>
                                <div class="flex items-center justify-center p-8">
                                    <span class="loading loading-spinner loading-md text-primary-purple"></span>
                                </div>
                            </li>
                        </div>
                        
                        <li class="border-t border-gray-100 sticky bottom-0 bg-base-100">
                            <a class="text-center text-sm py-2 hover:bg-gray-100 dark:hover:bg-gray-700" id="see-all-notifications">See All Notifications</a>
                        </li>
                    </ul>
                </div>
            </div>
           
            <!-- Dashboard overview page with summary cards and charts -->
            <div id="dashboard-page" class="page-content hidden flex-col flex-1-dynamic">

                <!-- Personalized greeting and summary cards -->
                <!-- Outer wrapper (for background and overlay effect) -->
                <div class="relative rounded-2xl bg-transparent p-2 mb-10 h-[250px]">
                    <!-- Inner gray container -->
                    <div class="absolute inset-x-0 bottom-0 rounded-2xl bg-transparent text-white mb-2 p-2 h-[250px] flex justify-center items-end overflow-hidden z-10">
                        sd
                        <!-- Image overlay (on top of everything) -->
                        <div class="absolute right-150 bottom-0 z-20">
                            <img src="<?php echo e(asset('storage/images/PLVgirl.png')); ?>" class="w-[270px] h-auto object-contain drop-shadow-lg" />
                        </div>

                        <!-- Main card container (centered & behind) -->
                        <div 
                            id="personalized-greeting" 
                            class="absolute bottom-0 bg-gradient-primary-purple flex items-center rounded-2xl h-[190px] w-[90%] max-w-[800px] shadow-lg z-0 mx-auto left-0 right-0"
                        >
                            <!-- Purple curved accent -->
                           <div class="absolute top-0 left-0 w-[120px] h-[120px] bg-gradient-to-r from-primary-purple to-transparent rounded-br-full opacity-70"></div>

                            <!-- Left text content -->
                            <div class="relative z-10 ml-2 pl-10">
                                <h2 class="text-3xl font-semibold text-white0">
                                    Good Day, 
                                    <span class="text-white font-bold">
                                        <?php echo e(Str::of(auth()->user()->name)->explode(' ')->first()); ?>

                                    </span>
                                </h2>
                                <br>
                                <p class="text-white text-base mt-1">
                                    Here you can manage your social <br>
                                    contract and track your progress.
                                </p>
                                <p class="text-white font-bold text-base mt-1">
                                    We make it easier for you ka-VITS!
                                </p>
                            </div>
                            <!-- Pending hours donut -->
                            <div class="flex flex-col items-center ml-auto">
                                <h2 class="text-xl font-bold text-white mb-4">Pending Hours</h2>
                                <div class="relative w-40 h-40">
                                    <canvas id="pendingHoursChart"></canvas>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="text-3xl font-bold text-white" id="pending-hours-label">0%</span>
                                        <p class="text-sm text-white" id="pending-amount">0h of 160h</p>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute top-0 right-0 w-[300px] h-full pointer-events-none"></div>
                            <!-- Optional space for balance -->
                            <div class="w-[120px]"></div>
                        </div>
                    </div>
                </div>



                <div class="bg-white rounded-2xl p-4 shadow-sm mb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-text-header mb-1">Social Contract Summary</h2>
                            <p class="text-sm text-text-muted">Contract Status Overview (Approved, Verified, Pending, Rejected)</p>
                        </div>
                        <button onclick="loadRecords();" class="btn btn-ghost btn-sm gap-2" title="Refresh dashboard stats">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span class="hidden md:inline">Refresh</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Approved Records -->
                        <div class="bg-gradient-approved p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Approved')">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="approved-count">8</span> Records</h3>
                                <p class="text-green-800 font-semibold">Approved</p>
                                <p class="text-xs text-text-muted mt-1" id="summary-last-updated-row">Last update: <span id="summary-last-updated">oct 18, 2025</span></p>
                            </div>
                        </div>
                        <!-- Verified Records -->
                        <div class="bg-gradient-verified p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Verified')">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="verified-count">0</span> Records</h3>
                                <p class="text-teal-800 font-semibold">Verified</p>
                                <p class="text-xs text-text-muted mt-1" id="summary-last-updated-verified-row">Last update: <span id="summary-last-updated-verified"></span></p>
                            </div>
                        </div>
                        <!-- Pending Records -->
                        <div class="bg-gradient-pending p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Pending')">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="pending-count">8</span> Records</h3>
                                <p class="text-yellow-800 font-semibold">Pending</p>
                                <p class="text-xs text-text-muted mt-1" id="summary-last-updated-2-row">Last update: <span id="summary-last-updated-2">oct 18, 2025</span></p>
                            </div>
                        </div>
                        <!-- Rejected Records -->
                        <div class="bg-gradient-rejected p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Rejected')">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="rejected-count">0</span> Records</h3>
                                <p class="text-red-800 font-semibold">Rejected</p>
                                <p class="text-xs text-text-muted mt-1" id="summary-last-updated-3-row">Last update: <span id="summary-last-updated-3">oct 18, 2025</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 flex-1-dynamic">
                    <div class="lg:col-span-3 bg-white rounded-2xl p-4 shadow-sm flex flex-col">
                        <h2 class="text-xl font-bold text-text-header mb-4">Yearly Approved Records</h2>
                        <div class="relative flex-1 w-full">
                            <canvas id="yearlyRecordsChart"></canvas>
                        </div>
                    </div>
                    <div class="lg:col-span-2 bg-white rounded-2xl p-4 shadow-sm flex flex-col items-center justify-center gap-6">
                        <!-- Approved / Completion donut -->
                        <div class="flex flex-col items-center">
                            <h2 class="text-xl font-bold text-text-header mb-4">Approved Hours Completion</h2>
                            <div class="relative w-40 h-40">
                                <canvas id="hoursCompletionChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-3xl font-bold text-[#6D28D9]" id="hours-completion-label">0%</span>
                                    <p class="text-sm text-text-muted" id="hours-amount">0h of 160h</p>
                                </div>
                            </div>
                        </div>

                        <div class="divider my-0"></div>

        
                    </div>
                </div>
            </div>

            <div id="record-status-page" class="page-content flex flex-col flex-1-dynamic">
                <div class="flex justify-between items-center px-4 mb-6">
                    <button class="btn btn-primary-purple rounded-lg border-0" onclick="document.getElementById('add_record_modal').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Record
                    </button>
                    <div class="flex items-center gap-4">
                        <label class="input input-bordered flex items-center gap-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                            <input id="record-search" type="text" class="grow" placeholder="Search by event, venue, or organization" />
                        </label>
                        
                        <!-- Refresh Button -->
                        <button id="refresh-records-btn" onclick="refreshRecords()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh records">
                            <svg id="refresh-records-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span class="hidden md:inline">Refresh</span>
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm content-area-auto">
                    <div class="overflow-x-auto"> 
                        <table class="table table-fixed w-full">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="w-10 text-center">
                                        <button id="delete-selected" class="btn btn-ghost btn-xs" title="Delete selected (Pending only)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </th>
                                    <th class="text-center w-1/6">
                                        <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Date">
                                            Date
                                            <span id="date-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center w-2/6">Event Name</th>
                                    <th class="text-center w-1/6">Venue</th>
                                    <th class="text-center w-1/6">Organization</th>
                                    <th class="text-center w-1/6">
                                        <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Hours Rendered">
                                            Hours Rendered
                                            <span id="hours-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center w-1/6">
                                        <div class="flex items-center justify-center gap-1">
                                            <span>Status</span>
                                            <div class="dropdown dropdown-end" id="status-filter-dropdown">
                                                <div tabindex="0" role="button" class="btn btn-ghost btn-xs m-1" title="Filter by status">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1.5A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                                                    </svg>
                                                </div>
                                                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-32">
                                                    <li><a onclick="filterTableByStatus('All', event)">All</a></li>
                                                    <li><a onclick="filterTableByStatus('Pending', event)">Pending</a></li>
                                                    <li><a onclick="filterTableByStatus('Verified', event)">Verified</a></li>
                                                    <li><a onclick="filterTableByStatus('Approved', event)">Approved</a></li>
                                                    <li><a onclick="filterTableByStatus('Rejected', event)">Rejected</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="record-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div> 

            <div id="profile-page" class="page-content hidden">
                <div class="flex items-center justify-between p-4">
                    <h4 class="text-4xl font-bold text-primary-purple">Profile Information</h4>
                    <label class="label cursor-pointer flex items-center gap-3">
                        <span id="theme-label" class="text-sm text-gray-600">Light theme</span>
                        <input id="theme-toggle" type="checkbox" class="toggle toggle-primary" />
                    </label>
                </div>
                
                <div class="flex-1 bg-white rounded-2xl p-6 shadow-sm flex flex-col gap-6">

                    <div id="profile-view" class="space-y-6">
                        <div class="grid grid-cols-2 gap-x-12 gap-y-6">
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Full Name</p>
                                <p class="font-semibold text-lg text-text-header"><?php echo e(auth()->user()->name); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Student Number</p>
                                <p class="font-semibold text-lg text-text-header"><?php echo e(auth()->user()->student_id ?? '—'); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Email Address</p>
                                <p class="font-semibold text-lg text-text-header"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Account Type</p>
                                <p class="font-semibold text-lg text-text-header">Student</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-500 text-sm mb-1">Password</p>
                                <p class="font-semibold text-lg text-text-header">••••••••••</p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button class="btn bg-primary-purple hover:bg-primary-purple-hover text-white rounded-lg" onclick="showEditMode('info')">
                                Edit Profile
                            </button>
                        </div>
                    </div>

                    <div id="profile-edit" class="space-y-6 hidden">
                        <form id="profile-info-form" class="grid grid-cols-2 gap-x-12 gap-y-4">
                            <label class="form-control w-full">
                                <div class="label"><span class="label-text font-semibold">Full Name</span></div>
                                <input id="edit-full-name" type="text" value="<?php echo e(auth()->user()->name); ?>" class="input input-bordered w-full rounded-lg" required />
                                <div class="label"><span class="label-text-alt text-gray-500">Surname, First Name Middle Initial</span></div>
                            </label>
                            <label class="form-control w-full">
                                <div class="label"><span class="label-text">Student Number</span></div>
                                <input type="text" value="<?php echo e(auth()->user()->student_id ?? ''); ?>" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label"><span class="label-text">Email Address</span></div>
                                <input type="email" value="<?php echo e(auth()->user()->email); ?>" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label"><span class="label-text">Account Type</span></div>
                                <input type="text" value="Student" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                            </label>

                            <div class="col-span-2 space-y-4 pt-4" id="password-view-section">
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Password</span></div>
                                    <input type="password" value="••••••••••" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                                </label>
                                <button type="button" class="btn btn-link px-0 text-sm text-primary-purple hover:text-primary-purple-hover" onclick="togglePasswordForm('show')">
                                    Reset Password?
                                </button>
                            </div>

                            <div class="col-span-2 space-y-4 pt-4 hidden" id="password-edit-fields">
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Current Password</span></div>
                                    <label class="input input-bordered flex items-center gap-2 rounded-lg">
                                        <input id="current-password" type="password" placeholder="••••••••••" class="grow" required/>
                                        <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('current-password', this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                                        </button>
                                    </label>
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">New Password</span></div>
                                    <label class="input input-bordered flex items-center gap-2 rounded-lg">
                                        <input id="new-password" type="password" placeholder="••••••••••" class="grow" required/>
                                        <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('new-password', this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                                        </button>
                                    </label>
                                </label>
                                <label class="form-control w-full">
                                    <div class="label"><span class="label-text">Confirm Password</span></div>
                                    <label class="input input-bordered flex items-center gap-2 rounded-lg">
                                        <input id="confirm-password" type="password" placeholder="••••••••••" class="grow" required/>
                                        <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('confirm-password', this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                                        </button>
                                    </label>
                                </label>
                            </div>
                            
                            <div class="col-span-2 pt-6 flex justify-end">
                                <button type="button" id="profile-save-btn" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>

            <!-- FAQs Page -->
            <div id="faqs-page" class="page-content hidden flex flex-col">
                <div class="p-4">
                    <h4 class="text-4xl font-bold text-primary-purple">Frequently Asked Questions</h4>
                </div>
                <div class="flex-1 bg-white rounded-2xl p-6 shadow-sm overflow-y-auto space-y-4">
                    <!-- FAQ 1 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            1. What is the Social Contract Management System?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">The Social Contract Management System is a digital platform that helps manage and monitor students' social service hours required by the Pamantasan ng Lungsod ng Valenzuela. It allows students, staff, and advisers to record, verify, and approve social contract activities more efficiently.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 2 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            2. Why was the system developed?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">It was developed to replace the manual, paper-based process of recording social contract hours. The old process often led to misplaced files, inaccurate records, and difficulty in tracking student progress.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 3 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            3. Who can access the system?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">The system can be accessed by students, department staff, advisers, and the head office reviewer. Each has different access levels and responsibilities.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 4 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            4. How does the approval process work?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">The process starts when a student submits their accomplished form. The Department Staff or Adviser first checks and verifies the details. Once verified, the Chairperson gives the final approval or rejection. Approved records are then officially counted toward the student's required hours.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 5 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            5. How do I create an account?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">Click "Sign Up," fill in your student details, and verify your PLV email to activate your account.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 6 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            6. Can I edit my form after submission?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">No. Once submitted, you can't edit it. Wait for feedback from your adviser if revisions are needed. Only pending contracts can be deleted.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 7 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            7. What happens after I submit my form?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">Your form will first be reviewed by your Department Staff or Adviser, then forwarded to the Head Office Reviewer for final approval.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 8 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            8. How long does the review process take?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">It may take a few working days, depending on the availability of your adviser and the head office reviewer.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 9 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            9. How will I know if my form is approved or rejected?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">You'll receive a status update and notification on your dashboard once your form is verified or approved.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 10 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            10. What should I do if my form is rejected?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">Check the reason for rejection on your dashboard, correct the issue, and resubmit your form.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 11 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            11. Can I submit multiple forms?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">No. Only one valid Social Contract Form is allowed per academic year.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ 12 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            12. Do I need to print my Social Contract Form?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted">Yes. Once your form is approved by the head office reviewer, export and print it at the end of every academic year, then submit it to the Registrar's Office as proof of your completed service hours.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <dialog id="add_record_modal" class="modal">
        <div class="modal-box p-6 max-w-lg rounded-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>

            <h3 class="font-bold text-xl mb-6 text-center text-text-header">Create a record</h3>
            
            <form id="add-record-form" class="space-y-4">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Event name</span>
                    </div>
                    <input type="text" id="event-name" placeholder="Enter event name here" class="input input-bordered w-full rounded-lg" required />
                </label>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Venue</span>
                    </div>
                    <input type="text" id="venue" placeholder="Enter venue here" class="input input-bordered w-full rounded-lg" required />
                </label>
                
                        <div class="flex gap-4">
                    <label class="form-control flex-1">
                        <div class="label">
                            <span class="label-text font-semibold">Date</span>
                        </div>
                        <label class="input input-bordered flex items-center gap-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                            </svg>
                            <input type="text" id="date" onfocus="(this.type='date')" onblur="(this.type='text')" class="grow" placeholder="Choose Date" required/>
                        </label>
                    </label>

                            <label class="form-control w-40">
                        <div class="label">
                            <span class="label-text font-semibold">Hours Rendered</span>
                        </div>
                                <input id="hours-rendered" type="number" min="0" step="1" value="0" class="input input-bordered w-full text-center" />
                    </label>
                </div>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Name of Organizing Committee</span>
                    </div>
                    <input type="text" id="organization" placeholder="Enter Supervisor name here" class="input input-bordered w-full rounded-lg" required />
                </label>

                <div class="mt-8 pt-4 flex justify-center">
                    <button type="button" id="submit-record-button" class="btn bg-primary-purple hover:bg-primary-purple-hover text-white rounded-lg">
                        Submit
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="confirmation_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Are you sure?</h3>
            <p class="py-4">Once you submit, you can no longer edit this record.</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-submit-btn" class="btn btn-primary bg-primary-purple hover:bg-primary-purple-hover text-white">Yes, submit</button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Status Records Modal -->
    <dialog id="status_records_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <div class="flex items-center gap-3 mb-6">
                <div id="status-modal-icon" class="bg-white p-3 rounded-full">
                    <!-- Icon will be injected here -->
                </div>
                <div>
                    <h3 class="font-bold text-2xl text-text-header" id="status-modal-title">Records</h3>
                    <p class="text-sm text-text-muted" id="status-modal-subtitle">Showing all records with this status</p>
                </div>
            </div>
            
            <div class="divider my-4"></div>
            
            <!-- Records Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="text-center">Date</th>
                            <th class="text-center">Event Name</th>
                            <th class="text-center">Venue</th>
                            <th class="text-center">Organization</th>
                            <th class="text-center">Hours</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody id="status-modal-table-body">
                        <!-- Records will be injected here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Empty State -->
            <div id="status-modal-empty" class="hidden text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-500 text-lg font-semibold">No records found</p>
                <p class="text-gray-400 text-sm mt-2">There are no records with this status yet.</p>
            </div>
            
            <!-- Summary Footer -->
            <div class="bg-base-200 rounded-lg p-4 mt-6 flex justify-between items-center">
                <div>
                    <p class="text-sm text-text-muted">Total Records</p>
                    <p class="text-xl font-bold text-text-header" id="status-modal-total">0</p>
                </div>
                <div>
                    <p class="text-sm text-text-muted">Total Hours</p>
                    <p class="text-xl font-bold text-text-header" id="status-modal-hours">0 hours</p>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Rejection Reason Modal -->
    <dialog id="rejection_reason_modal" class="modal">
        <div class="modal-box max-w-md">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-gradient-rejected p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-text-header">Rejection Reason</h3>
                    <p class="text-sm text-text-muted">Why this submission was rejected</p>
                </div>
            </div>
            
            <div class="divider my-4"></div>
            
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <p class="text-sm text-gray-900 dark:text-black whitespace-pre-wrap" id="rejection-reason-text">
                    <!-- Rejection reason will be injected here -->
                </p>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-text-muted">Please review and correct the issues mentioned above before resubmitting.</p>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- All Notifications Modal -->
    <dialog id="all_notifications_modal" class="modal">
        <div class="modal-box max-w-2xl max-h-[80vh]">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="bg-primary-purple p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl text-text-header">All Notifications</h3>
                        <p class="text-sm text-text-muted">Your recent activity updates</p>
                    </div>
                </div>
                <button id="mark-all-read-btn" class="btn btn-sm btn-ghost text-primary-purple hover:bg-primary-purple/10">
                    Mark all as read
                </button>
            </div>
            
            <div class="divider my-4"></div>
            
            <div id="all-notifications-list" class="space-y-2 overflow-y-auto max-h-[500px]">
                <!-- All notifications will be loaded here -->
                <div class="flex items-center justify-center py-8">
                    <span class="loading loading-spinner loading-md text-primary-purple"></span>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>


    <!-- DaisyUI toast root (bottom-right) -->
    <div id="toast-root" class="toast toast-bottom toast-end fixed bottom-4 right-4 z-[5000] space-y-2"></div>

    <script>
    // --- Toast helper using DaisyUI ---
    function showToast(message, type = 'info') {
        try {
            const root = document.getElementById('toast-root');
            if (!root) return alert(message);
            const alertDiv = document.createElement('div');
            const typeClass = {
                info: 'alert-info',
                success: 'alert-success',
                warning: 'alert-warning',
                error: 'alert-error'
            }[type] || 'alert-info';
            alertDiv.className = `alert ${typeClass} shadow-md transition transform duration-150 ease-out opacity-0 scale-95`;
            alertDiv.innerHTML = `<span class="max-w-[22rem]">${message.replace(/</g, '&lt;')}</span>`;
            // Close on click
            alertDiv.addEventListener('click', () => alertDiv.remove());
            root.appendChild(alertDiv);
            // Animate in
            requestAnimationFrame(() => {
                alertDiv.classList.remove('opacity-0', 'scale-95');
                alertDiv.classList.add('opacity-100', 'scale-100');
            });
            // Auto dismiss
            setTimeout(() => {
                try {
                    alertDiv.classList.remove('opacity-100', 'scale-100');
                    alertDiv.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => alertDiv.remove(), 160);
                } catch (_) { alertDiv.remove(); }
            }, 4500);
        } catch (_) { try { alert(message); } catch {} }
    }
    // --- Page Navigation Functions ---
        function showPage(pageId) {
            document.querySelectorAll('aside a').forEach(a => {
                a.classList.remove('bg-primary-purple', 'active-nav', 'rounded-lg');
            });
            document.querySelectorAll('.page-content').forEach(p => { p.classList.add('hidden'); });
            const greetingElement = document.getElementById('main-greeting');
            const notificationContainer = document.getElementById('notification-dropdown-container');
            if (pageId === 'profile' || pageId === 'faqs') { greetingElement.classList.add('hidden'); notificationContainer.classList.add('hidden'); }
            else { greetingElement.classList.remove('hidden'); notificationContainer.classList.remove('hidden'); }
            const newPage = document.getElementById(pageId + '-page'); if (newPage) newPage.classList.remove('hidden');
            const navLink = document.getElementById('nav-' + pageId); if (navLink) navLink.classList.add('bg-primary-purple', 'active-nav', 'rounded-lg');
            
            // Save current page to localStorage for student
            try {
                localStorage.setItem('scms_student_current_page', pageId);
            } catch(_) {}
            
            if (pageId === 'profile') { showViewMode(); }
            if (pageId === 'dashboard' && typeof renderCharts === 'function') { renderCharts(); }
        }
        function showEditMode(mode) {
            document.getElementById('profile-view').classList.add('hidden');
            document.getElementById('profile-edit').classList.add('hidden');
            if (mode === 'info') {
                document.getElementById('profile-edit').classList.remove('hidden');
                document.getElementById('password-view-section').classList.remove('hidden');
                document.getElementById('password-edit-fields').classList.add('hidden');
            }
        }
        function showViewMode() {
            document.getElementById('profile-edit').classList.add('hidden');
            document.getElementById('profile-view').classList.remove('hidden');
        }
        function togglePasswordForm(mode) {
            const viewSection = document.getElementById('password-view-section');
            const editFields = document.getElementById('password-edit-fields');
            if (mode === 'show') { viewSection.classList.add('hidden'); editFields.classList.remove('hidden'); }
            else { viewSection.classList.remove('hidden'); editFields.classList.add('hidden'); }
        }
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
        // Charts state
    let yearlyChart = null;
    let hoursChart = null;
    let pendingChart = null;

        function renderCharts() {
            try {
                const yearlyCanvas = document.getElementById('yearlyRecordsChart');
                const hoursCanvas = document.getElementById('hoursCompletionChart');
                const pendingCanvas = document.getElementById('pendingHoursChart');
                if (!yearlyCanvas || !hoursCanvas) return;
                const isDark = (document.documentElement.getAttribute('data-theme') === 'dark');
                const axisTextColor = isDark ? '#FFFFFF' : '#2B3674';
                const gridColor = isDark ? 'rgba(255,255,255,0.12)' : 'rgba(0,0,0,0.06)';
                const borderColor = isDark ? 'rgba(255,255,255,0.18)' : 'rgba(0,0,0,0.12)';

                const yearlyCtx = yearlyCanvas.getContext('2d');
                if (yearlyChart) { yearlyChart.destroy(); }
                yearlyChart = new Chart(yearlyCtx, {
                    type: 'bar',
                    data: {
                        labels: (window.__scms_yearLabels && window.__scms_yearLabels.length)
                            ? window.__scms_yearLabels
                            : ['2022', '2023', '2024', '2025', '2026', '2027'],
                        datasets: [{
                            label: 'Approved Records',
                            data: (window.__scms_yearlyApprovedData && window.__scms_yearlyApprovedData.length)
                                ? window.__scms_yearlyApprovedData
                                : [25, 18, 32, 22, 28, 35],
                            backgroundColor: '#6D28D9',
                            borderRadius: 8,
                            barThickness: 20,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false, labels: { color: axisTextColor } },
                            tooltip: {
                                enabled: true,
                                backgroundColor: isDark ? '#111827' : '#ffffff',
                                titleColor: isDark ? '#ffffff' : '#111827',
                                bodyColor: isDark ? '#ffffff' : '#111827',
                                borderColor: isDark ? borderColor : '#e5e7eb',
                                borderWidth: 1
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: axisTextColor },
                                grid: { color: gridColor, borderColor }
                            },
                            x: {
                                ticks: { color: axisTextColor },
                                grid: { color: gridColor, borderColor }
                            }
                        }
                    }
                });

                const hoursCtx = hoursCanvas.getContext('2d');
                if (hoursChart) { hoursChart.destroy(); }
                hoursChart = new Chart(hoursCtx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: (typeof window.__scms_hoursPercent === 'number')
                                ? [window.__scms_hoursPercent, Math.max(0, 100 - window.__scms_hoursPercent)]
                                : [0, 100],
                            backgroundColor: ['#6D28D9', '#E9D5FF'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '80%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false }
                        }
                    }
                });

                if (pendingCanvas) {
                    const pendingCtx = pendingCanvas.getContext('2d');
                    if (pendingChart) { pendingChart.destroy(); }
                    pendingChart = new Chart(pendingCtx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: (typeof window.__scms_pendingPercent === 'number')
                                    ? [window.__scms_pendingPercent, Math.max(0, 100 - window.__scms_pendingPercent)]
                                    : [0, 100],
                                backgroundColor: ['#6D28D9', '#E9D5FF'],
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            cutout: '80%',
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: false }
                            }
                        }
                    });
                }
            } catch (_) {}
        }

        // --- Table & Modal Logic ---
        function initDashboard() {
            // idempotent init: avoid double initialization if this script runs twice
            if (window.__scms_dashboard_inited) return;
            window.__scms_dashboard_inited = true;

            const BASE_PATH = <?php echo json_encode($BASE_PATH, 15, 512) ?>;
            function getCookie(name) {
                const value = `; ${document.cookie}`;
                const parts = value.split(`; ${name}=`);
                if (parts.length === 2) return parts.pop().split(';').shift();
                return null;
            }
            async function ensureCsrfCookie() {
                if (getCookie('XSRF-TOKEN')) return;
                try {
                    await fetch(`${BASE_PATH}/api/csrf-cookie`, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'Cache-Control': 'no-cache' }
                    });
                } catch (_) {}
            }
            const addRecordForm = document.getElementById('add-record-form');
            const submitRecordButton = document.getElementById('submit-record-button');
            const confirmationModal = document.getElementById('confirmation_modal');
            const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
            const addRecordModal = document.getElementById('add_record_modal');
            const tableBody = document.getElementById('record-table-body');
            const hoursInput = document.getElementById('hours-rendered');
            const searchInput = document.getElementById('record-search');
            const profileSaveBtn = document.getElementById('profile-save-btn');
            // Show Dashboard as the initial page
            showPage('dashboard');
            // Load existing records for the current student's latest social contract (single-account mode)
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let allRecords = [];
            // Date sort: 'desc' by default for latest first
            let dateSortDirection = 'desc';
            const dateSortToggle = document.getElementById('date-sort-toggle');
            const dateSortIndicator = document.getElementById('date-sort-indicator');
            // Hours sort: 'desc' by default for highest hours first
            let hoursSortDirection = 'desc';
            let currentSortBy = 'date'; // 'date' or 'hours'
            const hoursSortToggle = document.getElementById('hours-sort-toggle');
            const hoursSortIndicator = document.getElementById('hours-sort-indicator');
            // Normalize API date to YYYY-MM-DD and format to DD-MM-YYYY without timezone shifts
            function normalizeDateString(dateVal) {
                if (!dateVal) return '';
                let s = String(dateVal);
                // handle ISO datetimes like 2025-10-04T00:00:00.000000Z
                if (s.includes('T')) s = s.substring(0, 10);
                // Expect s as YYYY-MM-DD now
                const parts = s.split('-');
                if (parts.length === 3) {
                    const [y, m, d] = parts;
                    return `${d.padStart(2,'0')}-${m.padStart(2,'0')}-${y}`;
                }
                // Fallback to locale formatting
                try { return new Date(dateVal).toLocaleDateString('en-GB').replace(/\//g, '-'); } catch { return s; }
            }
            var lastRecordsData = null; // Store last data to detect changes
            var isLoadingRecords = false; // Prevent concurrent requests
            
            function loadRecords(showLoading = true) {
                // Prevent concurrent requests
                if (isLoadingRecords) {
                    console.log('Already loading records, skipping...');
                    return;
                }
                
                // Only clear table on initial load
                if (showLoading && !lastRecordsData) {
                    tableBody.innerHTML = '';
                }
                
                isLoadingRecords = true;
                
                // Add timestamp to URL to prevent caching
                var timestamp = new Date().getTime();
                
                fetch(`${BASE_PATH}/api/social-contract/records?_=${timestamp}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    },
                    credentials: 'same-origin'
                })
                    .then(async (r) => {
                        const ct = r.headers.get('content-type') || '';
                        if (!r.ok) throw r;
                                if (!ct.includes('application/json')) {
                                    // likely got redirected to login or an HTML error; log details and go to login
                                    console.warn('loadRecords: non-JSON response', { status: r.status, url: r.url, contentType: ct });
                                    try { window.location.replace(`${BASE_PATH}/login`); } catch(_) { window.location.href = `${BASE_PATH}/login`; }
                                    return Promise.reject(new Error('Non-JSON response'));
                                }
                        return r.json();
                    })
                    .then(({ records }) => {
                        isLoadingRecords = false;
                        // Always update when we get successful response
                        lastRecordsData = records;
                        allRecords = records;
                        renderTable();
                        updateDashboardFromRecords(allRecords);
                    })
                    .catch((err) => { 
                        isLoadingRecords = false;
                        console.error('Failed to load records', err);
                        
                        // Only show error if we have no cached data AND it's the first load
                        if (!lastRecordsData && showLoading) {
                            showToast('Failed to load records. Please refresh the page.', 'error');
                        }
                        // If we have cached data, silently keep using it - no toast notification
                        // The data will automatically update on next successful refresh
                    });
            }
            
            // Refresh records with visual feedback
            function refreshRecords() {
                var refreshBtn = document.getElementById('refresh-records-btn');
                var refreshIcon = document.getElementById('refresh-records-icon');
                
                if (refreshBtn && refreshIcon) {
                    refreshBtn.disabled = true;
                    refreshIcon.style.animation = 'spin 1s linear infinite';
                    
                    // Add CSS animation if not exists
                    if (!document.getElementById('refresh-spin-style')) {
                        var style = document.createElement('style');
                        style.id = 'refresh-spin-style';
                        style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
                        document.head.appendChild(style);
                    }
                }
                
                // Call loadRecords with loading indicator
                loadRecords(true);
                
                // Re-enable button after 2 seconds
                setTimeout(function() {
                    if (refreshBtn && refreshIcon) {
                        refreshBtn.disabled = false;
                        refreshIcon.style.animation = '';
                    }
                }, 2000);
            }
            
            // Make functions globally accessible for onclick handlers
            window.loadRecords = loadRecords;
            window.refreshRecords = refreshRecords;
            
            let currentExpandedRecordId = null;
            
            // Toggle record details inline with horizontal stepper
            function toggleRecordDetails(record, rowElement) {
                const recordId = record.id;
                
                // Debug: log rejection reason if status is rejected
                if (record.status === 'Rejected') {
                    console.log('Rejected record:', record);
                    console.log('Rejection reason:', record.rejection_reason);
                }
                
                // If clicking the same row, collapse it
                if (currentExpandedRecordId === recordId) {
                    const existingDetailsRow = document.querySelector(`tr[data-details-for="${recordId}"]`);
                    if (existingDetailsRow) {
                        existingDetailsRow.remove();
                    }
                    currentExpandedRecordId = null;
                    return;
                }
                
                // Remove any existing details row
                const existingDetailsRow = document.querySelector('tr.record-details-row');
                if (existingDetailsRow) {
                    existingDetailsRow.remove();
                }
                
                // Create new details row
                const detailsRow = document.createElement('tr');
                detailsRow.className = 'record-details-row';
                detailsRow.dataset.detailsFor = recordId;
                
                // Determine step states based on status
                let step1Class = 'completed';
                let step1Icon = '1';
                let step2Class = '';
                let step2Icon = '2';
                let step2Label = 'Pending';
                let step3Class = '';
                let step3Icon = '3';
                let step3Label = 'Pending';
                let connector1Class = 'completed';
                let connector2Class = '';
                
                if (record.status === 'Pending') {
                    step2Class = 'pending';
                    step2Label = 'Awaiting verification';
                } else if (record.status === 'Verified') {
                    step2Class = 'completed';
                    step2Icon = '2';
                    step2Label = 'Verified';
                    connector1Class = 'completed';
                    connector2Class = 'active';
                    step3Class = 'active';
                    step3Label = 'Awaiting approval';
                } else if (record.status === 'Approved') {
                    step2Class = 'completed';
                    step2Icon = '2';
                    step2Label = 'Verified';
                    step3Class = 'completed';
                    step3Icon = '3';
                    step3Label = 'Approved';
                    connector1Class = 'completed';
                    connector2Class = 'completed';
                } else if (record.status === 'Rejected') {
                    // Determine if rejected at step 2 or 3
                    // For now, assume rejection at step 2
                    step2Class = 'rejected';
                    step2Icon = '✕';
                    step2Label = 'Rejected';
                    connector1Class = 'completed';
                }
                
                detailsRow.innerHTML = `
                    <td colspan="7">
                        <div class="steps-horizontal">
                            <div class="step-item">
                                <div class="step-circle ${step1Class}">${step1Icon}</div>
                                <div class="step-connector ${connector1Class}"></div>
                                <div class="step-label">Submitted</div>
                                <div class="step-sublabel">${normalizeDateString(record.date)}</div>
                            </div>
                            <div class="step-item">
                                <div class="step-circle ${step2Class}" 
                                     ${record.status === 'Rejected' && record.rejection_reason ? 'data-rejection-reason="' + (record.rejection_reason || '').replace(/"/g, '&quot;') + '" style="cursor: pointer;"' : ''} 
                                     title="${record.status === 'Rejected' ? 'Click to view rejection reason' : ''}">${step2Icon}</div>
                                <div class="step-connector ${connector2Class}"></div>
                                <div class="step-label">Admin Review</div>
                                <div class="step-sublabel">${step2Label}</div>
                            </div>
                            <div class="step-item">
                                <div class="step-circle ${step3Class}">${step3Icon}</div>
                                <div class="step-label">Super Admin Review</div>
                                <div class="step-sublabel">${step3Label}</div>
                            </div>
                        </div>
                    </td>
                `;
                
                // Insert after the clicked row
                rowElement.insertAdjacentElement('afterend', detailsRow);
                currentExpandedRecordId = recordId;
                
                // Add click event listener for rejected status
                if (record.status === 'Rejected') {
                    console.log('Setting up click listener for rejected record');
                    console.log('Has rejection reason:', !!record.rejection_reason);
                    
                    const rejectedCircle = detailsRow.querySelector('.step-circle.rejected');
                    console.log('Found rejected circle:', !!rejectedCircle);
                    
                    if (rejectedCircle) {
                        rejectedCircle.style.cursor = 'pointer';
                        rejectedCircle.addEventListener('click', function(e) {
                            console.log('Rejected circle clicked!');
                            e.preventDefault();
                            e.stopPropagation();
                            const reason = record.rejection_reason || 'No specific reason provided.';
                            console.log('Calling showRejectionReason with:', reason);
                            showRejectionReason(reason);
                        });
                        console.log('Click listener attached successfully');
                    }
                }
            }
            
            // Show status modal with filtered records
            function showStatusModal(status) {
                const modal = document.getElementById('status_records_modal');
                const modalTitle = document.getElementById('status-modal-title');
                const modalSubtitle = document.getElementById('status-modal-subtitle');
                const modalIcon = document.getElementById('status-modal-icon');
                const tableBody = document.getElementById('status-modal-table-body');
                const emptyState = document.getElementById('status-modal-empty');
                const totalCount = document.getElementById('status-modal-total');
                const totalHours = document.getElementById('status-modal-hours');
                
                // Filter records by status
                const filteredRecords = allRecords.filter(r => r.status === status);
                
                // Update modal header based on status
                let iconSvg = '';
                let bgColorClass = '';
                
                if (status === 'Approved') {
                    iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
                    bgColorClass = 'bg-gradient-approved';
                    modalTitle.textContent = `${filteredRecords.length} Approved Records`;
                } else if (status === 'Verified') {
                    iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
                    bgColorClass = 'bg-gradient-verified';
                    modalTitle.textContent = `${filteredRecords.length} Verified Records`;
                } else if (status === 'Pending') {
                    iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    bgColorClass = 'bg-gradient-pending';
                    modalTitle.textContent = `${filteredRecords.length} Pending Records`;
                } else if (status === 'Rejected') {
                    iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
                    bgColorClass = 'bg-gradient-rejected';
                    modalTitle.textContent = `${filteredRecords.length} Rejected Records`;
                }
                
                modalIcon.innerHTML = iconSvg;
                modalIcon.className = `${bgColorClass} p-3 rounded-full flex items-center justify-center`;
                modalSubtitle.textContent = `All ${status.toLowerCase()} social contract records`;
                
                // Clear table body
                tableBody.innerHTML = '';
                
                // Calculate total hours
                const hours = filteredRecords.reduce((sum, r) => sum + (parseInt(r.hours_rendered) || 0), 0);
                
                if (filteredRecords.length === 0) {
                    // Show empty state
                    emptyState.classList.remove('hidden');
                    tableBody.closest('.overflow-x-auto').classList.add('hidden');
                } else {
                    // Hide empty state and show table
                    emptyState.classList.add('hidden');
                    tableBody.closest('.overflow-x-auto').classList.remove('hidden');
                    
                    // Populate table
                    filteredRecords.forEach(rec => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="text-center">${normalizeDateString(rec.date)}</td>
                            <td class="text-center">${rec.event_name || '-'}</td>
                            <td class="text-center">${rec.venue || '-'}</td>
                            <td class="text-center">${rec.organization || '-'}</td>
                            <td class="text-center">${rec.hours_rendered} hours</td>
                            <td class="text-center">${renderStatusBadge(rec.status)}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                }
                
                // Update summary
                totalCount.textContent = filteredRecords.length;
                totalHours.textContent = `${hours} hours`;
                
                // Show modal
                modal.showModal();
            }
            
            // Make showStatusModal globally accessible
            window.showStatusModal = showStatusModal;
            
            // Function to show rejection reason modal
            function showRejectionReason(reason) {
                console.log('showRejectionReason called with:', reason);
                const modal = document.getElementById('rejection_reason_modal');
                const reasonText = document.getElementById('rejection-reason-text');
                
                if (!modal) {
                    console.error('rejection_reason_modal not found');
                    return;
                }
                
                if (!reasonText) {
                    console.error('rejection-reason-text element not found');
                    return;
                }
                
                if (reason && reason.trim()) {
                    reasonText.textContent = reason;
                } else {
                    reasonText.textContent = 'No specific reason provided.';
                }
                
                console.log('Opening modal...');
                modal.showModal();
            }
            
            // Make it globally accessible
            window.showRejectionReason = showRejectionReason;
            
            // ========== NOTIFICATION SYSTEM ==========
            
            // Load recent notifications (max 3 for dropdown)
            async function loadRecentNotifications() {
                try {
                    const response = await fetch('/api/notifications/recent', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (!response.ok) throw new Error('Failed to fetch notifications');
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        updateNotificationDropdown(data.notifications);
                        updateNotificationBadge(data.unread_count);
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    document.getElementById('notifications-list').innerHTML = `
                        <li><div class="p-4 text-center text-sm text-gray-500">Failed to load notifications</div></li>
                    `;
                }
            }
            
            // Update notification dropdown with recent notifications
            function updateNotificationDropdown(notifications) {
                const list = document.getElementById('notifications-list');
                
                if (!notifications || notifications.length === 0) {
                    list.innerHTML = `
                        <li><div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">No notifications yet</div></li>
                    `;
                    return;
                }
                
                list.innerHTML = notifications.map(notif => createNotificationHTML(notif, false)).join('');
                
                // Add click handlers for rejection reasons
                notifications.forEach(notif => {
                    if (notif.type === 'rejected' && notif.rejection_reason) {
                        const notifEl = document.getElementById(`notif-${notif.id}`);
                        if (notifEl) {
                            const reasonBtn = notifEl.querySelector('.view-reason-btn');
                            if (reasonBtn) {
                                reasonBtn.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    showRejectionReason(notif.rejection_reason);
                                });
                            }
                        }
                    }
                });
            }
            
            // Update notification badge (red dot)
            function updateNotificationBadge(unreadCount) {
                const badge = document.getElementById('notif-dot');
                if (badge) {
                    if (unreadCount > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }
            
            // Create notification HTML
            function createNotificationHTML(notif, showDeleteBtn = true) {
                let icon, iconColor, statusText, statusColor, statusHex;
                
                switch(notif.type) {
                    case 'verified':
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
                        iconColor = 'text-blue-500';
                        statusText = 'Verified';
                        statusColor = 'text-blue-600 dark:text-blue-600';
                        statusHex = '#4a80f4ff';
                        break;
                    case 'approved':
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
                        iconColor = 'text-green-500';
                        statusText = 'Approved';
                        statusColor = 'text-green-600 dark:text-green-600';
                        statusHex = '#29bb5eff';
                        break;
                    case 'rejected':
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />';
                        iconColor = 'text-red-500';
                        statusText = 'Rejected';
                        statusColor = 'text-red-600 dark:text-red-600';
                        statusHex = '#f85050ff';
                        break;
                    default:
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                        iconColor = 'text-gray-500';
                        statusText = 'Updated';
                        statusColor = 'text-gray-700 dark:text-gray-700';
                        statusHex = '#374151';
                }
                
                const isUnread = !notif.is_read;
                const bgClass = isUnread ? 'bg-purple-50 dark:bg-purple-900/10' : '';
                
                // Event details
                let eventDetails = '';
                    if (notif.event_name || notif.venue) {
                    eventDetails = `
                        <p class="text-xs text-gray-700 dark:text-gray mt-1">
                            ${notif.event_name ? `<span class="font-medium">${notif.event_name}</span>` : ''}
                            ${notif.event_name && notif.venue ? ' • ' : ''}
                            ${notif.venue ? notif.venue : ''}
                        </p>
                    `;
                }
                
                let reasonSection = '';
                let expandedReasonSection = '';
                if (notif.type === 'rejected' && notif.rejection_reason) {
                    if (showDeleteBtn) {
                        // For "All Notifications" modal - inline expandable reason
                        reasonSection = `
                            <button class="view-reason-btn text-xs text-primary-purple hover:underline mt-1 flex items-center gap-1" data-notif-id="${notif.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 chevron-icon transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                View reason
                            </button>
                            <div class="reason-content hidden mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <p class="text-xs font-semibold text-red-700 dark:text-red mb-1">Rejection Reason:</p>
                                <p class="text-sm text-gray-900 dark:text-black whitespace-pre-wrap">${notif.rejection_reason}</p>
                            </div>
                        `;
                    } else {
                        // For dropdown - opens modal
                        reasonSection = `
                            <button class="view-reason-btn text-xs text-primary-purple hover:underline mt-1 flex items-center gap-1" data-notif-id="${notif.id}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View reason
                            </button>
                        `;
                    }
                }
                
                const deleteBtn = showDeleteBtn ? `
                    <button class="delete-notif-btn btn btn-ghost btn-xs btn-circle absolute top-2 right-2" data-notif-id="${notif.id}" title="Delete notification">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                ` : '';
                
                return `
                    <li id="notif-${notif.id}" class="relative ${bgClass}">
                        <div class="flex items-start p-3 w-full border-b border-gray-100 dark:border-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ${iconColor} mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                ${icon}
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900 dark:text-black">
                                    Your submission has been <span class="${statusColor} font-bold" ${statusHex ? `style="color: ${statusHex} !important"` : ''}>${statusText}</span>
                                </p>
                                ${eventDetails}
                                <p class="text-xs text-gray dark:text-gray mt-1">${notif.created_at}</p>
                                ${reasonSection}
                            </div>
                            ${deleteBtn}
                        </div>
                    </li>
                `;
            }
            
            // Load all notifications for modal
            async function loadAllNotifications() {
                try {
                    const response = await fetch('/api/notifications/all', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (!response.ok) throw new Error('Failed to fetch all notifications');
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        updateAllNotificationsModal(data.notifications);
                    }
                } catch (error) {
                    console.error('Error loading all notifications:', error);
                    document.getElementById('all-notifications-list').innerHTML = `
                        <div class="p-4 text-center text-sm text-gray-500">Failed to load notifications</div>
                    `;
                }
            }
            
            // Update all notifications modal
            function updateAllNotificationsModal(notifications) {
                const list = document.getElementById('all-notifications-list');
                
                if (!notifications || notifications.length === 0) {
                    list.innerHTML = `
                        <div class="p-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p>No notifications yet</p>
                        </div>
                    `;
                    return;
                }
                
                list.innerHTML = notifications.map(notif => createNotificationHTML(notif, true)).join('');
                
                // Add click handlers for rejection reasons in "All Notifications" modal
                notifications.forEach(notif => {
                    if (notif.type === 'rejected' && notif.rejection_reason) {
                        // scope search to modal list to avoid ID collisions with dropdown
                        const notifEl = list.querySelector(`#notif-${notif.id}`);
                        if (notifEl) {
                            const reasonBtn = notifEl.querySelector('.view-reason-btn');
                            const reasonContent = notifEl.querySelector('.reason-content');
                            const chevronIcon = notifEl.querySelector('.chevron-icon');
                            
                            if (reasonBtn && reasonContent) {
                                console.debug('Attaching view-reason handler for notif (modal)', notif.id);
                                reasonBtn.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    try {
                                        // Toggle the reason content
                                        const isHidden = reasonContent.classList.contains('hidden');
                                        if (isHidden) {
                                            reasonContent.classList.remove('hidden');
                                            if (chevronIcon) chevronIcon.style.transform = 'rotate(180deg)';
                                            const firstSvg = reasonBtn.querySelector('svg');
                                            // Rebuild button content safely
                                            reasonBtn.innerHTML = '';
                                            if (firstSvg) reasonBtn.appendChild(firstSvg.cloneNode(true));
                                            reasonBtn.appendChild(document.createTextNode(' Hide reason'));
                                        } else {
                                            reasonContent.classList.add('hidden');
                                            if (chevronIcon) chevronIcon.style.transform = 'rotate(0deg)';
                                            const firstSvg = reasonBtn.querySelector('svg');
                                            reasonBtn.innerHTML = '';
                                            if (firstSvg) reasonBtn.appendChild(firstSvg.cloneNode(true));
                                            reasonBtn.appendChild(document.createTextNode(' View reason'));
                                        }
                                        console.debug('Toggled reason visibility for notif (modal)', notif.id, !isHidden);
                                    } catch (err) {
                                        console.error('Error toggling reason for notif (modal)', notif.id, err);
                                    }
                                });
                            }
                        }
                    }
                });
                
                // Add delete handlers scoped to modal
                list.querySelectorAll('.delete-notif-btn').forEach(btn => {
                    btn.addEventListener('click', async (e) => {
                        e.stopPropagation();
                        const notifId = btn.getAttribute('data-notif-id');
                        await deleteNotification(notifId);
                    });
                });
            }
            
            // Delete notification
            async function deleteNotification(notifId) {
                try {
                    const response = await fetch(`/api/notifications/${notifId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (!response.ok) throw new Error('Failed to delete notification');
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Remove from UI
                        const notifEl = document.getElementById(`notif-${notifId}`);
                        if (notifEl) {
                            notifEl.style.opacity = '0';
                            notifEl.style.transform = 'translateX(100%)';
                            setTimeout(() => notifEl.remove(), 300);
                        }
                        
                        // Reload notifications
                        setTimeout(() => {
                            loadRecentNotifications();
                            loadAllNotifications();
                        }, 300);
                        
                        showToast('Notification deleted', 'success');
                    }
                } catch (error) {
                    console.error('Error deleting notification:', error);
                    showToast('Failed to delete notification', 'error');
                }
            }
            
            // Mark all notifications as read
            async function markAllAsRead() {
                try {
                    const response = await fetch('/api/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    if (!response.ok) throw new Error('Failed to mark all as read');
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        loadRecentNotifications();
                        loadAllNotifications();
                        showToast('All notifications marked as read', 'success');
                    }
                } catch (error) {
                    console.error('Error marking all as read:', error);
                    showToast('Failed to mark notifications as read', 'error');
                }
            }
            
            // Event listeners for notifications
            document.getElementById('see-all-notifications')?.addEventListener('click', () => {
                loadAllNotifications();
                document.getElementById('all_notifications_modal').showModal();
            });
            
            document.getElementById('mark-all-read-btn')?.addEventListener('click', () => {
                markAllAsRead();
            });
            
            // Load notifications on page load
            loadRecentNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(loadRecentNotifications, 30000);
            
            // ========== END NOTIFICATION SYSTEM ==========
            
            function renderTable() {
                tableBody.innerHTML = '';
                const query = (searchInput.value || '').toLowerCase().trim();
                // filter
                let filtered = allRecords.filter(r => {
                    if (!query) return true;
                    return (
                        (r.event_name || '').toLowerCase().includes(query) ||
                        (r.venue || '').toLowerCase().includes(query) ||
                        (r.organization || '').toLowerCase().includes(query)
                    );
                });
                // sort by current sort column
                filtered.sort((a, b) => {
                    if (currentSortBy === 'hours') {
                        const ha = parseInt(a.hours_rendered) || 0;
                        const hb = parseInt(b.hours_rendered) || 0;
                        return hoursSortDirection === 'asc' ? ha - hb : hb - ha;
                    } else {
                        // sort by date
                        const da = new Date(a.date);
                        const db = new Date(b.date);
                        if (isNaN(da) && isNaN(db)) return 0;
                        if (isNaN(da)) return dateSortDirection === 'asc' ? -1 : 1;
                        if (isNaN(db)) return dateSortDirection === 'asc' ? 1 : -1;
                        return dateSortDirection === 'asc' ? da - db : db - da;
                    }
                });
                filtered.forEach(rec => {
                    const formattedDate = normalizeDateString(rec.date);
                    const row = document.createElement('tr');
                    row.dataset.recordId = rec.id;
                    row.dataset.status = rec.status;
                    row.style.cursor = 'pointer';
                    row.onclick = function(e) { 
                        // Don't toggle if clicking on checkbox
                        if (e.target.classList.contains('record-checkbox')) return;
                        toggleRecordDetails(rec, row); 
                    };
                    // Compute deletion countdown HTML if available
                    const deletionCountdownHtml = (() => {
                        try {
                            const rejectedAtRaw = rec.rejected_at || rec.rejectedAt || rec.updated_at || rec.updatedAt || null;
                            if (!rejectedAtRaw) return '';
                            const rej = new Date(String(rejectedAtRaw));
                            if (isNaN(rej)) return '';
                            const deleteAt = new Date(rej.getTime() + 7 * 24 * 60 * 60 * 1000);
                            const now = new Date();
                            const diff = deleteAt - now;
                            if (diff <= 0) return '<div class="text-xs text-red-500">Deleting soon</div>'; // fallback
                            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            return `<div class="text-xs text-gray-400 mt-1">Time left before deletion: ${days} day${days!==1?'s':''} ${hours} hour${hours!==1?'s':''}</div>`;
                        } catch (e) { return ''; }
                    })();

                    // Add action date if available
                    let statusHtml = renderStatusBadge(rec.status);
                    if (rec.action_date) {
                        statusHtml += `<div class="text-xs text-gray-500 mt-1">${rec.action_date}</div>`;
                    }
                    
                    row.innerHTML = `
                        <td class="text-center"><input type="checkbox" class="record-checkbox" ${rec.status !== 'Pending' ? 'disabled' : ''}></td>
                        <td class="text-center">${formattedDate}</td>
                        <td class="text-center">${rec.event_name}</td>
                        <td class="text-center">${rec.venue}</td>
                        <td class="text-center">${rec.organization}</td>
                        <td class="text-center">${rec.hours_rendered} hours</td>
                        <td class="text-center">${statusHtml} ${rec.status === 'Rejected' ? deletionCountdownHtml : ''}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
            searchInput.addEventListener('input', renderTable);
            // Date sort toggle
            dateSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'date';
                dateSortDirection = dateSortDirection === 'asc' ? 'desc' : 'asc';
                dateSortIndicator.textContent = dateSortDirection === 'asc' ? '▲' : '▼';
                hoursSortIndicator.textContent = '▼'; // Reset hours indicator
                renderTable();
            });
            // Hours sort toggle
            hoursSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'hours';
                hoursSortDirection = hoursSortDirection === 'asc' ? 'desc' : 'asc';
                hoursSortIndicator.textContent = hoursSortDirection === 'asc' ? '▲' : '▼';
                dateSortIndicator.textContent = '▼'; // Reset date indicator
                renderTable();
            });
            // Initial load: ensure CSRF cookie exists for consistent behavior
            ensureCsrfCookie().finally(() => {
                loadRecords();
            });
            submitRecordButton.addEventListener('click', () => {
                if (addRecordForm.checkValidity()) { confirmationModal.showModal(); }
                else { addRecordForm.reportValidity(); }
            });
            confirmSubmitBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const payload = {
                    event_name: document.getElementById('event-name').value,
                    venue: document.getElementById('venue').value,
                    date: document.getElementById('date').value,
                    hours_rendered: parseInt(document.getElementById('hours-rendered').value || '0', 10),
                    organization: document.getElementById('organization').value,
                };
                fetch(`${BASE_PATH}/api/social-contract/records`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf,
                        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN') || '',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                })
                .then(async (r) => {
                        const ct = r.headers.get('content-type') || '';
                        if (!r.ok) {
                            let err;
                            try { err = await r.json(); } catch { err = { message: 'Request failed' }; }
                            return Promise.reject({ status: r.status, err });
                        }
                        if (!ct.includes('application/json')) {
                                // helpful debug info when server returned HTML (often a login redirect)
                                console.warn('submit-record: non-JSON response', { status: r.status, url: r.url, contentType: ct });
                                try { window.location.replace(`${BASE_PATH}/login`); } catch(_) { window.location.href = `${BASE_PATH}/login`; }
                                return Promise.reject(new Error('Non-JSON response'));
                            }
                    return r.json();
                })
                .then((rec) => {
                    allRecords.unshift(rec);
                    renderTable();
                    updateDashboardFromRecords(allRecords);
                    addRecordForm.reset();
                    hoursInput.value = '0';
                    confirmationModal.close();
                    addRecordModal.close();
                })
                .catch((err) => {
                    console.error('Failed to save record', err);
                    if (err && err.status === 422 && err.err && err.err.errors) {
                        const messages = Object.values(err.err.errors).flat().join('\n');
                        showToast('Validation error: ' + messages, 'error');
                    } else if (err && err.status === 401) {
                        showToast('Session expired. Please log in again.', 'error');
                        setTimeout(() => {
                            window.location.href = `${BASE_PATH}/login`;
                        }, 2000);
                    } else if (err && err.status === 419) {
                        // CSRF token mismatch
                        showToast('Session expired. Please refresh and try again.', 'error');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else if (err && err.err && err.err.message) {
                        showToast('Error: ' + err.err.message, 'error');
                    } else {
                        showToast('Failed to save record. Please try again.', 'error');
                    }
                    confirmationModal.close();
                });
            });
            // Send reset link to PLV email for profile change confirmation
            if (profileSaveBtn) {
                profileSaveBtn.addEventListener('click', async () => {
                    try {
                        // Check if user wants to update name
                        const newName = (document.getElementById('edit-full-name')?.value || '').trim();
                        const originalName = '<?php echo e(auth()->user()->name); ?>';
                        const nameChanged = newName && newName !== originalName;
                        
                        // Check if user wants to change password
                        const wantPasswordEdit = !document.getElementById('password-edit-fields').classList.contains('hidden');
                        
                        // If neither name nor password changed, show warning
                        if (!nameChanged && !wantPasswordEdit) {
                            showToast('No changes detected. Please modify your name or click "Reset Password?" to change password.', 'warning');
                            return;
                        }
                        
                        // Validate name if changed
                        if (nameChanged) {
                            if (newName.length < 3) {
                                showToast('Name must be at least 3 characters long.', 'error');
                                return;
                            }
                        }
                        
                        // Validate password fields if password edit is active
                        let currentPwd = '';
                        let newPwd = '';
                        let confirmPwd = '';
                        
                        if (wantPasswordEdit) {
                            currentPwd = (document.getElementById('current-password')?.value || '').trim();
                            newPwd = (document.getElementById('new-password')?.value || '').trim();
                            confirmPwd = (document.getElementById('confirm-password')?.value || '').trim();
                            
                            if (!currentPwd || !newPwd || !confirmPwd) {
                                showToast('Please fill in all password fields.', 'warning');
                                return;
                            }
                            if (newPwd !== confirmPwd) {
                                showToast('New password and confirm password do not match.', 'error');
                                return;
                            }
                            if (newPwd.length < 8) {
                                showToast('Password must be at least 8 characters.', 'error');
                                return;
                            }
                        }
                        
                        // Prepare request body
                        const requestBody = {};
                        if (nameChanged) {
                            requestBody.name = newName;
                        }
                        if (wantPasswordEdit) {
                            requestBody.current_password = currentPwd;
                            requestBody.password = newPwd;
                            requestBody.password_confirmation = confirmPwd;
                        }

                        await ensureCsrfCookie();
                        const res = await fetch(`${BASE_PATH}/api/profile/update`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrf,
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(requestBody)
                        });
                        const ct = res.headers.get('content-type') || '';
                        const data = ct.includes('application/json') ? await res.json().catch(() => ({})) : {};
                        if (!res.ok) {
                            const msg = (data && data.message) ? data.message : 'Failed to update profile.';
                            showToast(msg, 'error');
                            return;
                        }
                        
                        let successMessage = 'Profile updated successfully!';
                        if (nameChanged && wantPasswordEdit) {
                            successMessage = 'Name and password updated successfully!';
                        } else if (nameChanged) {
                            successMessage = 'Name updated successfully!';
                        } else if (wantPasswordEdit) {
                            successMessage = 'Password updated successfully!';
                        }
                        
                        showToast(successMessage, 'success');
                        
                        // Update the UI with new name if changed
                        if (nameChanged && data.name) {
                            document.querySelectorAll('[data-user-name]').forEach(el => {
                                el.textContent = data.name;
                            });
                            // Update profile view
                            const profileViewName = document.querySelector('#profile-view .font-semibold');
                            if (profileViewName) {
                                profileViewName.textContent = data.name;
                            }
                            // Update sidebar name
                            const sidebarName = document.querySelector('.text-xl.font-bold.text-white');
                            if (sidebarName) {
                                sidebarName.textContent = data.name;
                            }
                        }
                        
                        // Back to view mode
                        togglePasswordForm('hide');
                        showViewMode();
                        
                        // Refresh page to show updated data everywhere
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } catch (e) {
                        console.error('Profile update error:', e);
                        showToast('Failed to update profile.', 'error');
                    }
                });
            }
            document.getElementById('delete-selected').addEventListener('click', () => {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Get all checked rows
                const checkedRows = Array.from(document.querySelectorAll('#record-table-body tr'))
                    .filter(tr => tr.querySelector('.record-checkbox')?.checked);
                
                // Filter to only pending records
                const pendingIds = checkedRows
                    .filter(tr => tr.dataset.status === 'Pending')
                    .map(tr => parseInt(tr.dataset.recordId, 10))
                    .filter(Boolean);
                
                // Check if there are non-pending records selected
                const nonPendingSelected = checkedRows.length > pendingIds.length;
                
                if (!checkedRows.length) {
                    showToast('Please select records to delete', 'warning');
                    return;
                }
                
                if (nonPendingSelected) {
                    showToast('Only pending records can be deleted. Non-pending records have been excluded.', 'warning');
                }
                
                if (!pendingIds.length) {
                    showToast('No pending records selected. Only pending records can be deleted.', 'error');
                    return;
                }
                
                // Confirm deletion
                if (!confirm(`Are you sure you want to delete ${pendingIds.length} pending record(s)?`)) {
                    return;
                }
                
                Promise.all(pendingIds.map(id => fetch(`${BASE_PATH}/api/social-contract/records/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN') || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                }).then(async (r) => {
                    const ct = r.headers.get('content-type') || '';
                    if (!r.ok) throw r;
                    if (!ct.includes('application/json')) {
                        try { window.location.replace(`${BASE_PATH}/login`); } catch(_) { window.location.href = `${BASE_PATH}/login`; }
                        throw new Error('Non-JSON response');
                    }
                    return r.json();
                })))
                .then(() => {
                    allRecords = allRecords.filter(r => !pendingIds.includes(r.id));
                    renderTable();
                    updateDashboardFromRecords(allRecords);
                    showToast(`Successfully deleted ${pendingIds.length} record(s)`, 'success');
                })
                .catch((err) => { 
                    console.error('Failed to delete selected records', err);
                    
                    // Check for specific error types
                    if (err && err.status === 401) {
                        showToast('Session expired. Please log in again.', 'error');
                        setTimeout(() => {
                            window.location.href = `${BASE_PATH}/login`;
                        }, 2000);
                    } else if (err && err.status === 419) {
                        showToast('Session expired. Please refresh and try again.', 'error');
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showToast('Failed to delete records. Please try again.', 'error');
                    }
                });
            });
        }
        // Floating portal for the status filter menu (avoid overflow clipping)
        function setupStatusFilterPortal() {
            try {
                const dd = document.getElementById('status-filter-dropdown');
                if (!dd) return;
                const toggle = dd.querySelector('[role="button"]');
                const menu = dd.querySelector('ul');
                if (!toggle || !menu) return;

                function closePortal(){
                    const portal = document.getElementById('status-filter-portal');
                    if (!portal) return;
                    const ul = portal.querySelector('ul');
                    if (ul) dd.appendChild(ul);
                    portal.remove();
                    document.removeEventListener('click', onDocClick, true);
                    window.removeEventListener('keydown', onEsc);
                    window.removeEventListener('scroll', onScroll, { passive: true });
                    window.removeEventListener('resize', onScroll);
                }
                function onDocClick(e){
                    const portal = document.getElementById('status-filter-portal');
                    if (!portal) return;
                    if (!portal.contains(e.target) && e.target !== toggle) closePortal();
                }
                function onEsc(e){ if (e.key === 'Escape') closePortal(); }
                function onScroll(){ closePortal(); }

                toggle.addEventListener('click', function(ev){
                    ev.preventDefault(); ev.stopPropagation();
                    const portalExisting = document.getElementById('status-filter-portal');
                    if (portalExisting) { closePortal(); return; }
                    const rect = toggle.getBoundingClientRect();
                    const portal = document.createElement('div');
                    portal.id = 'status-filter-portal';
                    portal.className = 'fixed z-[3000]';
                    document.body.appendChild(portal);
                    portal.appendChild(menu);
                    // Prepare for measurement
                    const menuWidth = Math.max(menu.offsetWidth || 128, 128);
                    menu.style.minWidth = menuWidth + 'px';
                    menu.style.visibility = 'hidden';
                    portal.style.left = '-9999px';
                    portal.style.top = '0px';
                    // Measure to decide placement
                    const mRect = menu.getBoundingClientRect();
                    const mH = mRect.height || 160;
                    const spaceBelow = window.innerHeight - rect.bottom;
                    const openAbove = spaceBelow < mH + 12; // flip if not enough space
                    const left = Math.min(Math.max(8, rect.right - menuWidth), window.innerWidth - menuWidth - 8);
                    const offset = 10; // space for caret
                    const top = openAbove ? Math.max(8, rect.top - offset - mH) : (rect.bottom + offset);
                    portal.style.left = left + 'px';
                    portal.style.top = top + 'px';
                    // Caret
                    const caret = document.createElement('div');
                    caret.className = 'absolute w-3 h-3 bg-base-100 rotate-45 shadow';
                    caret.style.right = '12px';
                    if (openAbove) { caret.style.top = (mH + 2) + 'px'; } else { caret.style.top = '-6px'; }
                    portal.appendChild(caret);
                    // Animate menu in
                    menu.classList.add('transition', 'ease-out', 'duration-150', 'transform', 'opacity-0', 'scale-95');
                    menu.style.visibility = 'visible';
                    requestAnimationFrame(() => {
                        menu.classList.remove('opacity-0', 'scale-95');
                        menu.classList.add('opacity-100', 'scale-100');
                    });
                    document.addEventListener('click', onDocClick, true);
                    window.addEventListener('keydown', onEsc);
                    window.addEventListener('scroll', onScroll, { passive: true });
                    window.addEventListener('resize', onScroll);
                });
            } catch (_) {}
        }

        function initThemeToggle(){
            try {
                const toggle = document.getElementById('theme-toggle');
                const label = document.getElementById('theme-label');
                const applyTheme = (mode) => {
                    document.documentElement.setAttribute('data-theme', mode);
                    try { localStorage.setItem('scms_theme', mode); } catch(_) {}
                    if (label) label.textContent = (mode === 'dark') ? 'Dark theme' : 'Light theme';
                    if (toggle) toggle.checked = (mode === 'dark');
                    // Re-render charts to pick up new colors
                    try { if (typeof renderCharts === 'function') renderCharts(); } catch(_) {}
                };
                let saved = 'light';
                try { saved = (localStorage.getItem('scms_theme') === 'dark') ? 'dark' : 'light'; } catch(_) {}
                applyTheme(saved);
                if (toggle) {
                    toggle.addEventListener('change', () => {
                        applyTheme(toggle.checked ? 'dark' : 'light');
                    });
                }
            } catch(_) {}
        }

        function boot(){ 
            // Restore saved page for student, default to dashboard
            var savedPage = 'dashboard';
            try {
                savedPage = localStorage.getItem('scms_student_current_page') || 'dashboard';
            } catch(_) {}
            
            initDashboard(); 
            setupStatusFilterPortal(); 
            initThemeToggle();
            
            // Fix dropdown positioning for status filter
            var dropdownBtn = document.querySelector('#status-filter-dropdown [role="button"]');
            if (dropdownBtn) {
                dropdownBtn.addEventListener('click', function() {
                    setTimeout(function() {
                        var dropdown = document.querySelector('#status-filter-dropdown .dropdown-content');
                        if (dropdown) {
                            var btnRect = dropdownBtn.getBoundingClientRect();
                            dropdown.style.position = 'fixed';
                            dropdown.style.left = (btnRect.left - 100) + 'px';
                            dropdown.style.top = (btnRect.bottom + 5) + 'px';
                        }
                    }, 10);
                });
            }
            
            // Show the saved page
            if (savedPage !== 'dashboard') {
                showPage(savedPage);
            }
            
            // Auto-refresh removed - use manual refresh buttons instead
        }
        if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', boot, { once: true }); }
        else { boot(); }
        
        // Lightweight toast API
        function ensureToastRoot(){
            let root = document.getElementById('toast-root');
            if (!root) {
                root = document.createElement('div');
                root.id = 'toast-root';
                document.body.appendChild(root);
            }
            return root;
        }
        function showToast(message, { type = 'success', timeout = 3500 } = {}){
            const root = ensureToastRoot();
            const el = document.createElement('div');
            el.className = `scms-toast scms-toast--${type}`;
            el.setAttribute('role', 'status');
            el.setAttribute('aria-live', 'polite');
            el.style.pointerEvents = 'auto';
            el.innerHTML = `
                <span class="scms-toast__msg"></span>
                <button class="scms-toast__close" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
                <div class="scms-toast__progress"></div>
            `;
            el.querySelector('.scms-toast__msg').textContent = String(message ?? '');
            const closeBtn = el.querySelector('.scms-toast__close');
            const progress = el.querySelector('.scms-toast__progress');
            if (progress && timeout > 0) {
                progress.style.animation = `scms-toast-progress ${timeout}ms linear forwards`;
            }
            let removeTimer = null;
            const remove = () => {
                try { clearTimeout(removeTimer); } catch(_) {}
                el.style.opacity = '0'; el.style.transform = 'translateY(6px)';
                setTimeout(() => el.remove(), 180);
            };
            closeBtn.addEventListener('click', remove, { passive: true });
            root.appendChild(el);
            // animate in
            el.style.opacity = '0'; el.style.transform = 'translateY(6px)';
            requestAnimationFrame(() => { el.style.transition = 'opacity .18s ease, transform .18s ease'; el.style.opacity = '1'; el.style.transform = 'translateY(0)'; });
            if (timeout > 0) removeTimer = setTimeout(remove, timeout);
            return { close: remove, el };
        }
        // Attach logout handler after DOM is ready to avoid inline script rendering issues
        function attachLogoutHandler() {
            try {
                const form = document.getElementById('logout-form-visible');
                const btn = document.getElementById('logout-button-visible');
                if (!form || !btn) return;
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';

                // remove any previous handler to avoid duplicates
                btn.replaceWith(btn.cloneNode(true));
                const freshBtn = document.getElementById('logout-button-visible');
                if (!freshBtn) return;

                freshBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    try {
                        // Broadcast logout to other tabs/windows
                        try { localStorage.setItem('scms_force_logout', String(Date.now())); } catch(_) {}
                        fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({'_token': csrf}).toString(),
                            keepalive: true
                        }).finally(() => {
                            try { window.location.replace(<?php echo json_encode(url('/'), 15, 512) ?>); } catch(_) { window.location.href = '/'; }
                        });
                    } catch (err) {
                        try { window.location.replace(<?php echo json_encode(url('/'), 15, 512) ?>); } catch(_) { window.location.href = '/'; }
                    }
                }, { passive: true });
            } catch (_) {}
        }
        // execute attachLogoutHandler once after DOM ready
        try { attachLogoutHandler(); } catch (_) {}
        function renderStatusBadge(status) {
            if (status === 'Approved') {
                return '<span class="scms-badge scms-badge--approved">Approved</span>';
            }
            if (status === 'Verified') {
                return '<span class="scms-badge scms-badge--verified">Verified</span>';
            }
            if (status === 'Rejected') {
                return '<span class="scms-badge scms-badge--rejected">Rejected</span>';
            }
            return '<span class="scms-badge scms-badge--pending">Pending</span>';
        }
        function filterTableByStatus(status, event) {
            const tableBody = document.getElementById('record-table-body');
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach(row => {
                const rowStatus = row.dataset.status || '';
                row.style.display = (status === 'All' || rowStatus === status) ? '' : 'none';
            });
            // Close the floating menu if open
            try {
                const portal = document.getElementById('status-filter-portal');
                if (portal) {
                    const ul = portal.querySelector('ul');
                    const dd = document.getElementById('status-filter-dropdown');
                    if (ul && dd) dd.appendChild(ul);
                    portal.remove();
                }
            } catch (_) {}
        }

        // --- Dashboard metrics from records ---
        function updateDashboardFromRecords(records) {
            try {
                const elApproved = document.getElementById('approved-count');
                const elVerified = document.getElementById('verified-count');
                const elPending = document.getElementById('pending-count');
                const elRejected = document.getElementById('rejected-count');
                const elHoursLabel = document.getElementById('hours-completion-label');
                const elHoursAmount = document.getElementById('hours-amount');
                const elPendingLabel = document.getElementById('pending-hours-label');
                const elPendingAmount = document.getElementById('pending-amount');

                if (!Array.isArray(records)) records = [];

                const counts = { Approved: 0, Verified: 0, Pending: 0, Rejected: 0 };
                let totalHours = 0; // all hours, informational only
                let approvedHours = 0; // Approved only (final approval)
                let verifiedHours = 0; // Verified only (admin verified, awaiting super admin approval)
                let pendingHours = 0;  // Pending only
                // Track last update per status
                let lastApproved = null;
                let lastVerified = null;
                let lastPending = null;
                let lastRejected = null;
                const yearMap = new Map(); // year -> approved count

                for (const r of records) {
                    const status = String(r.status || '').trim();
                    // prefer explicit timestamps when available; fallback to record date
                    const createdAt = safeDate(r.created_at || r.createdAt || r.date);
                    const updatedAt = safeDate(r.updated_at || r.updatedAt || r.date);
                    if (status === 'Approved') {
                        counts.Approved++;
                        // aggregate yearly approved
                        const y = safeYear(r.date);
                        if (y !== null) {
                            yearMap.set(y, (yearMap.get(y) || 0) + 1);
                        }
                        const basis = updatedAt || createdAt;
                        if (basis && (!lastApproved || basis > lastApproved)) lastApproved = basis;
                    } else if (status === 'Verified') {
                        counts.Verified++;
                        const basis = updatedAt || createdAt;
                        if (basis && (!lastVerified || basis > lastVerified)) lastVerified = basis;
                    } else if (status === 'Pending') {
                        counts.Pending++;
                        if (createdAt && (!lastPending || createdAt > lastPending)) lastPending = createdAt;
                    } else if (status === 'Rejected') {
                        counts.Rejected++;
                        const basis = updatedAt || createdAt;
                        if (basis && (!lastRejected || basis > lastRejected)) lastRejected = basis;
                    }
                    // hours
                    const h = Number(r.hours_rendered || 0);
                    if (!Number.isNaN(h)) {
                        totalHours += h;
                        if (status === 'Approved') approvedHours += h;
                        else if (status === 'Verified') verifiedHours += h;
                        else if (status === 'Pending') pendingHours += h;
                    }
                }

                // Update cards
                if (elApproved) elApproved.textContent = String(counts.Approved);
                if (elVerified) elVerified.textContent = String(counts.Verified);
                if (elPending) elPending.textContent = String(counts.Pending);
                if (elRejected) elRejected.textContent = String(counts.Rejected);
                // Per-status last update text & row visibility
                const fmt = (d) => d ? d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).toLowerCase() : '';
                const approvedText = fmt(lastApproved);
                const verifiedText = fmt(lastVerified);
                const pendingText = fmt(lastPending);
                const rejectedText = fmt(lastRejected);
                setTextIf('#summary-last-updated', approvedText);
                setTextIf('#summary-last-updated-verified', verifiedText);
                setTextIf('#summary-last-updated-2', pendingText);
                setTextIf('#summary-last-updated-3', rejectedText);
                // Leave labels visible; if no data, the value spans are empty (blank)

                // Hours: explicit 160-hour target for approved (completion - only Approved, not Verified)
                const targetHours = 160;
                const combinedPendingHours = pendingHours + verifiedHours; // Pending + Verified hours
                const approvedPct = Math.max(0, Math.min(100, Math.round(((approvedHours || 0) / targetHours) * 100)));
                const pendingPct = Math.max(0, Math.min(100, Math.round(((combinedPendingHours || 0) / targetHours) * 100)));
                // Update labels
                if (elHoursLabel) elHoursLabel.textContent = approvedPct + '%';
                if (elHoursAmount) elHoursAmount.textContent = `${approvedHours || 0}h of ${targetHours}h`;
                if (elPendingLabel) elPendingLabel.textContent = pendingPct + '%';
                if (elPendingAmount) elPendingAmount.textContent = `${combinedPendingHours || 0}h of ${targetHours}h`;
                // Values for charts
                window.__scms_hoursPercent = approvedPct;
                window.__scms_pendingPercent = pendingPct;

                // Build yearly chart data from yearMap (sorted by year)
                const sortedYears = Array.from(yearMap.keys()).sort((a,b) => a - b);
                const yearLabels = sortedYears.length ? sortedYears.map(String) : ['2022','2023','2024','2025'];
                const approvedData = sortedYears.length ? sortedYears.map(y => yearMap.get(y) || 0) : [0,0,0,0];
                window.__scms_yearLabels = yearLabels;
                window.__scms_yearlyApprovedData = approvedData;

                // Re-render charts if dashboard visible or already initialized
                if (typeof renderCharts === 'function') {
                    renderCharts();
                }
            } catch (e) { console.warn('updateDashboardFromRecords failed', e); }
        }

        function setTextIf(selector, text) {
            try {
                const el = document.querySelector(selector);
                if (el) el.textContent = text;
            } catch (_) {}
        }

        function safeDate(val) {
            try {
                if (!val) return null;
                const s = String(val);
                const iso = s.includes('T') ? s : s;
                const d = new Date(iso);
                return isNaN(d) ? null : d;
            } catch (_) { return null; }
        }

        function safeYear(val) {
            const d = safeDate(val);
            return d ? d.getFullYear() : null;
        }
    </script>
</body>
</html><?php /**PATH C:\Users\janar\Herd\scms\resources\views/dashboards/student.blade.php ENDPATH**/ ?>