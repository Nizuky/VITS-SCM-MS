<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        
        <!-- PDF Export Libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

        <!-- Toastify Library -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        @php
            $iconCandidates = ['storage/vits_white.png', 'vits_white.png', 'storage/vits_whites.png', 'vits_whites.png', 'vitswhite.png', 'vitslogo.png', 'public/storage/vits_white.png', 'storage/vits_header.png'];
            $iconUrl = null;
            $iconMTime = null;
            foreach ($iconCandidates as $relPath) {
                try {
                    $full = public_path($relPath);
                    if (file_exists($full)) {
                        $iconUrl = asset($relPath);
                        try {
                            $iconMTime = @filemtime($full) ?: null;
                        } catch (Throwable $e) {
                        }
                        break;
                    }
                } catch (Throwable $e) {
                }
            }
            if (!$iconUrl) {
                $iconUrl = asset('vits_white.png');
            }
            if ($iconUrl && $iconMTime) {
                $iconUrl .= '?v=' . $iconMTime;
            }
        @endphp
        <link rel="icon" href="{{ $iconUrl }}" sizes="any">
        <link rel="icon" href="{{ $iconUrl }}" type="image/png">
        <link rel="shortcut icon" href="{{ $iconUrl }}" type="image/png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Ensure active nav exactly matches primary purple */
        .active-nav { background-color: #6D28D9; color: #ffffff; border-radius: 0.5rem; }
        .flex-1-dynamic { flex: 1 1 auto; min-height: 0; }
        .page-content:not(.hidden) { display: flex !important; }
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
            background-image: url('{{ asset('vits_bg_white.png') }}');
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
            justify-content: space-between;
            align-items: flex-start;
            gap: 0;
            padding: 3rem 2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }
        
        .step-circle {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
            background-color: #e5e7eb;
            color: #6b7280;
            z-index: 2;
            position: relative;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        
        .step-circle.active {
            background-color: #3B82F6;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .step-circle.completed {
            background-color: #4CAF50;
            color: white;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }
        
        .step-circle.rejected {
            background-color: #EF4444;
            color: white;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        
        .step-circle.rejected:hover {
            background-color: #DC2626;
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }
        
        .step-circle.rejected:active {
            transform: scale(1.05);
        }
        
        .step-circle.pending {
            background-color: #F59E0B;
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        
        .step-label {
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
            color: #1f2937;
            margin-bottom: 0.375rem;
            line-height: 1.2;
        }
        
        .step-sublabel {
            font-size: 0.8rem;
            color: #6b7280;
            text-align: center;
            line-height: 1.3;
        }
        
        .step-connector {
            position: absolute;
            top: 1.75rem;
            left: calc(50% + 1.75rem);
            right: calc(-50% + 1.75rem);
            height: 4px;
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
        
        /* Dark theme styles */
        [data-theme="dark"] .step-circle {
            background-color: #374151;
            color: #9ca3af;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        [data-theme="dark"] .step-label {
            color: #e5e7eb;
        }
        
        [data-theme="dark"] .step-sublabel {
            color: #9ca3af;
        }
        
        [data-theme="dark"] .step-connector {
            background-color: #4b5563;
        }
        
        /* Force variant states to keep bright colors even in dark theme */
        [data-theme="dark"] .step-circle.active {
            background-color: #3B82F6 !important; /* blue */
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
        }
        [data-theme="dark"] .step-circle.completed {
            background-color: #4CAF50 !important; /* green */
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4) !important;
        }
        [data-theme="dark"] .step-circle.rejected {
            background-color: #EF4444 !important; /* red */
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4) !important;
        }
        [data-theme="dark"] .step-circle.rejected:hover {
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.5) !important;
        }
        [data-theme="dark"] .step-circle.pending {
            background-color: #F59E0B !important; /* yellow/orange */
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4) !important;
        }
        
        [data-theme="dark"] .step-connector.active {
            background-color: #3B82F6 !important;
        }
        
        [data-theme="dark"] .step-connector.completed {
            background-color: #4CAF50 !important;
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
            background-image: url('{{ asset('storage/vits_bg_black.png') }}');
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
        :root:not([data-theme="dark"]) .record-checkbox:disabled { 
            opacity: .4; 
            cursor: not-allowed; 
            background-color: #f3f4f6; /* gray-100 */
            border-color: #d1d5db; /* gray-300 */
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
        .table thead tr{height:60px!important;max-height:60px!important}
        .table thead th{height:60px!important;max-height:60px!important;vertical-align:middle!important}
        /* Support tickets table hover effect - match consistent hover styling */
        #ticket-table-body tr:hover{background-color:#f3f4f6!important}
        [data-theme="dark"] #ticket-table-body tr:hover{background-color:#191E24!important}
        /* Record status table hover effect */
        #record-table-body tr:hover{background-color:#F2F2F2!important}
        [data-theme="dark"] #record-table-body tr:hover{background-color:#191E24!important}
        /* Support ticket status badges - dark theme */
        [data-theme="dark"] .badge.bg-yellow-100{background-color:#ff9d26!important;color:#fff!important}
        [data-theme="dark"] .badge.bg-green-100{background-color:#4CAF50!important;color:#fff!important}
        [data-theme="dark"] .badge.bg-gray-100{background-color:#6b7280!important;color:#fff!important}
        [data-theme="dark"] .badge.bg-blue-100{background-color:#3b82f6!important;color:#fff!important}
        [data-theme="dark"] .badge.text-yellow-800{color:#fff!important}
        [data-theme="dark"] .badge.text-green-800{color:#fff!important}
        [data-theme="dark"] .badge.text-gray-800{color:#fff!important}
        [data-theme="dark"] .badge.text-blue-800{color:#fff!important}
        
        /* Prevent page-level horizontal scroll while allowing table scroll */
        body {
            max-width: 100vw;
            overflow-x: hidden;
        }
        
        /* Ensure main content wrapper doesn't cause horizontal scroll */
        #page-container {
            max-width: 100%;
            overflow-x: hidden;
        }
        
        /* Table container: horizontal scroll only when needed */
        .overflow-x-auto {
            width: 100%;
            overflow-x: auto;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-custom">
    @include('partials.auto_logout')
    @php
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

        // Get the first super admin's name for PDF signature
        $superAdmin = \App\Models\SuperAdmin::first();
        $superAdminName = $superAdmin ? $superAdmin->name : 'Super Administrator';
    @endphp
    <div class="flex p-4 gap-4 min-h-screen"> 
        <aside class="flex flex-col bg-white rounded-2xl p-4 shadow-sm sticky top-4 self-start h-[calc(100vh-2rem)] overflow-hidden" style="width: 200px; min-width: 200px; max-width: 200px;">
            <div class="flex flex-col items-center text-center p-4 border-b border-gray-200">
                <div class="avatar placeholder mb-3">
                    <div class="w-24 h-24 rounded-full ring ring-[#6D28D9] ring-offset-2 ring-offset-base-100 bg-[#6D28D9] text-white flex items-center justify-center select-none" title="{{ auth()->user()->name }}" aria-label="{{ auth()->user()->name }}">
                        <span class="text-3xl font-bold leading-none">{{ $initials }}</span>
                    </div>
                </div>
                <h2 class="font-bold text-lg">{{ auth()->user()->name }}</h2>
                <p class="text-sm text-gray-500">Student Number: {{ auth()->user()->student_id ?? '—' }}</p>
                @php
                    $accountStatus = auth()->user()->status ?? 'active';
                    $isActive = strtolower($accountStatus) === 'active';
                @endphp
                <div class="mt-2">
                    @if($isActive)
                        <span class="badge badge-success badge-sm text-white font-semibold">Active</span>
                    @else
                        <span class="badge badge-error badge-sm text-white font-semibold">Inactive</span>
                    @endif
                </div>
            </div>

            <ul class="menu p-0 my-4 flex-grow">
                <li>
                    <a class="py-3 pl-2" id="nav-dashboard" onclick="showPage('dashboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2" id="nav-record-status" onclick="showPage('record-status')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Record Status
                    </a>
                </li>
            </ul>

            <ul class="menu p-0">
                <li>
                    <a class="py-3 pl-2" id="nav-support" onclick="showPage('support')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Support Tickets
                    </a>
                </li>
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
                    <form id="logout-form-visible" action="{{ route('logout') }}" method="POST" class="m-0 p-0 pl-2 pr-0" novalidate>
                        @csrf
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
                <div id="main-greeting">
                    <h4 id="page-title" class="text-4xl font-bold text-primary-purple">Student Dashboard</h4>
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

                <!-- Inactive Account Warning Banner -->
                @if(auth()->user()->status === 'inactive' && auth()->user()->inactive_at)
                    @php
                        $inactiveSince = \Carbon\Carbon::parse(auth()->user()->inactive_at);
                        $deletionDate = $inactiveSince->copy()->addDays(7);
                        $now = now();
                        
                        // Calculate days remaining - use ceiling to always round up
                        $totalHours = $now->diffInHours($deletionDate, false);
                        $daysRemaining = max(0, (int) ceil($totalHours / 24));
                        $hoursRemaining = max(0, (int) $totalHours);
                    @endphp
                    <div class="alert alert-error shadow-lg mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-800 rounded-lg" role="alert">
                        <div class="flex items-start w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6 text-red-600 dark:text-red-00" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div class="ml-3 flex-1">
                                <h3 class="text-lg font-bold dark:text-red-300" style="color: #790000ff;">Account Inactive - Scheduled for Deletion</h3>
                                <div class="mt-2 text-sm dark:text-red-400">
                                    <p class="font-semibold mb-2" style="color: #790000ff;">⚠️ Your account has been deactivated by an administrator.</p>
                                    <div class="bg-red-100 dark:bg-red-950/40 border border-red-200 dark:border-red-800 p-3 rounded-md mb-2">
                                        <p class="text-base font-bold dark:text-red-200" style="color: #790000ff;">
                                            @if($daysRemaining > 0)
                                                <span class="text-2xl">{{ $daysRemaining }}</span> day{{ $daysRemaining != 1 ? 's' : '' }} remaining
                                            @else
                                                <span class="text-2xl">{{ $hoursRemaining }}</span> hour{{ $hoursRemaining != 1 ? 's' : '' }} remaining
                                            @endif
                                        </p>
                                        <p class="text-sm dark:text-red-300" style="color: #790000ff;">Your account will be permanently deleted on <strong>{{ $deletionDate->format('F d, Y \a\t g:i A') }}</strong></p>
                                    </div>
                                    <p class="text-sm dark:text-red-400" style="color: #790000ff;">
                                        <strong>What this means:</strong> You currently have limited access to the system. 
                                        All your data, including social contract records, will be permanently removed on the deletion date.
                                    </p>
                                    <p class="text-sm mt-2 dark:text-red-400" style="color: #790000ff;">
                                        <strong>Action Required:</strong> Please contact your administrator immediately to reactivate your account 
                                        and prevent permanent deletion.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Personalized greeting and summary cards -->
                <!-- Outer wrapper (for background and overlay effect) -->
                <div class="relative rounded-2xl bg-transparent p-2 mb-10 h-[250px]">
                    <!-- Inner gray container -->
                    <div class="absolute inset-x-0 bottom-0 rounded-2xl bg-transparent text-white mb-2 p-2 h-[250px] flex justify-center items-end overflow-hidden z-10">
                        sd
                        <!-- Image overlay (on top of everything) -->
                        <div class="absolute right-150 bottom-0 z-20">
                            <img src="{{ asset('storage/images/PLVgirl.png') }}" class="w-[270px] h-auto object-contain drop-shadow-lg" />
                        </div>

                        <!-- Main card container (centered & behind) -->
                        <div 
                            id="personalized-greeting" 
                            class="absolute bottom-0 bg-gradient-primary-purple flex items-center rounded-2xl h-[190px] w-[90%] max-w-[800px] shadow-lg z-0 mx-auto left-0 right-0"
                        >
                            <!-- Purple curved accent -->
                           <div class="absolute top-0 left-0 w-[120px] h-[120px] bg-gradient-to-r from-primary-purple to-transparent rounded-br-full opacity-70"></div>

                            <!-- Left text content -->
                            <div class="relative z-10 ml-2 pl-4 sm:pl-6 md:pl-10 pr-2">
                                <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold text-white">
                                    Good Day, 
                                    <span class="text-white font-bold">
                                        {{ Str::of(auth()->user()->name)->explode(' ')->first() }}
                                    </span>
                                </h2>
                                <br class="hidden md:block">
                                <p class="text-white text-xs sm:text-sm md:text-base mt-1">
                                    Here you can manage your social <br class="hidden sm:block">
                                    contract and track your progress.
                                </p>
                                <p class="text-white font-bold text-xs sm:text-sm md:text-base mt-1">
                                    We make it easier for you ka-VITS!
                                </p>
                            </div>
                            <!-- Pending hours donut -->
                            <div class="flex flex-col items-center ml-auto mr-2 sm:mr-4 p-4">
                                <h2 class="text-sm sm:text-base md:text-xl font-bold text-white mb-2 md:mb-4">Pending Hours</h2>
                                <div class="relative w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40">
                                    <canvas id="pendingHoursChart"></canvas>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="text-xl sm:text-2xl md:text-3xl font-bold text-white" id="pending-hours-label">0%</span>
                                        <p class="text-xs sm:text-sm text-white" id="pending-amount">0h of 160h</p>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute top-0 right-0 w-[300px] h-full pointer-events-none"></div>
                            <!-- Optional space for balance -->
                            <div class="w-0 sm:w-[60px] md:w-[120px]"></div>
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
                <div class="flex flex-col md:flex-row justify-between px-4 items-start md:items-center mb-6 gap-4">
                    <button class="btn btn-primary-purple rounded-lg border-0 w-full md:w-auto" onclick="document.getElementById('add_record_modal').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="whitespace-nowrap">Add New Record</span>
                    </button>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 w-full md:w-auto">
                        <!-- Export PDF Button -->
                        <button id="export-pdf-btn" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg border-0" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                            </svg>
                            <span class="whitespace-nowrap">Export PDF</span>
                        </button>
                        
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label class="input input-bordered flex items-center gap-2 rounded-lg flex-1 sm:flex-initial">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                                <input id="record-search" type="text" class="grow" placeholder="Search by event, venue, or organization" />
                            </label>
                            
                            <!-- Refresh Button -->
                            <button id="refresh-records-btn" onclick="refreshRecords()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh records">
                                <svg id="refresh-records-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span class="hidden lg:inline">Refresh</span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Record Status Table -->
                <div class="bg-white rounded-2xl p-6 shadow-sm flex-1 flex flex-col min-h-0">
                     <div class="overflow-x-auto overflow-y-auto rounded-lg" style="max-height: calc(120vh - 280px);">
                        <table class="table table-xs table-pin-rows">
                            <thead class="text-gray-600" style="height: 60px; background-color: #f9fafb !important;">
                                <tr style="background-color: #f9fafb !important;">
                                    <th class="text-center" style="min-width: 50px; width: 50px; height: 60px;">
                                        <button id="delete-selected" class="btn btn-ghost btn-xs" title="Delete selected (Pending only)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </th>
                                    <th class="text-center" style="min-width: 80px; width: 80px; height: 60px; white-space: nowrap;">
                                        <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Date">
                                            Date
                                            <span id="date-sort-indicator">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="min-width: 110px; width: 110px; height: 60px; white-space: nowrap;">
                                        <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Event Name">
                                            Event Name
                                            <span id="eventname-sort-indicator">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="min-width: 100px; width: 100px; height: 60px; white-space: nowrap;">
                                        <button id="venue-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Venue">
                                            Venue
                                            <span id="venue-sort-indicator">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="min-width: 140px; width: 140px; height: 60px; white-space: nowrap;">
                                        <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Organization">
                                            Organization
                                            <span id="organization-sort-indicator">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="min-width: 120px; width: 120px; height: 60px; white-space: nowrap;">
                                        <button id="supervisor-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Supervisor">
                                            Supervisor
                                            <span id="supervisor-sort-indicator">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="min-width: 50px; width: 50px; height: 60px; white-space: nowrap;">
                                        <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Hours Rendered">
                                            Hours
                                            <span id="hours-sort-indicator">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="min-width: 180px; width: 180px; height: 60px;">
                                        <div class="flex items-center justify-center gap-1 font-bold">
                                            <button id="status-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Status">
                                                <span>Status</span>
                                                <span id="status-sort-indicator">⇅</span>
                                            </button>
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

            <div id="profile-page" class="page-content hidden flex-col">
                <div class="p-4 flex items-center justify-end">
                    <label class="label cursor-pointer flex items-center gap-3">
                        <span id="theme-label" class="text-sm text-gray-600">Light theme</span>
                        <input id="theme-toggle" type="checkbox" class="toggle toggle-primary" />
                    </label>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm flex flex-col gap-6">
                    <div id="profile-view" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Full Name</p>
                                <p class="font-semibold text-lg text-text-header break-words">{{ auth()->user()->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Student Number</p>
                                <p class="font-semibold text-lg text-text-header">{{ auth()->user()->student_id ?? '—' }}</p>
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <p class="text-gray-500 text-sm mb-1">Email Address</p>
                                <p class="font-semibold text-lg text-text-header break-all">{{ auth()->user()->email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Account Type</p>
                                <p class="font-semibold text-lg text-text-header">Student</p>
                            </div>
                            <div>
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
                                <input id="edit-full-name" type="text" value="{{ auth()->user()->name }}" class="input input-bordered w-full rounded-lg" required />
                                <div class="label"><span class="label-text-alt text-gray-500">Surname, First Name Middle Initial</span></div>
                            </label>
                            <label class="form-control w-full">
                                <div class="label"><span class="label-text">Student Number</span></div>
                                <input type="text" value="{{ auth()->user()->student_id ?? '' }}" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                            </label>
                            <label class="form-control w-full">
                                <div class="label"><span class="label-text">Email Address</span></div>
                                <input type="email" value="{{ auth()->user()->email }}" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
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

            <!-- Support Tickets Page -->
            <div id="support-page" class="page-content hidden flex flex-col">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 mb-6 gap-4">
                    <button class="btn btn-primary-purple rounded-lg border-0 w-full md:w-auto" onclick="document.getElementById('submit_ticket_modal').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="whitespace-nowrap">Submit New Ticket</span>
                    </button>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 w-full md:w-auto">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label class="input input-bordered flex items-center gap-2 rounded-lg flex-1 sm:flex-initial">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                                <input type="text" id="ticket-search-input" placeholder="Search tickets by ID, type, or details..." class="grow" />
                            </label>
                            
                            <!-- Refresh Button -->
                            <button id="refresh-tickets-btn" onclick="refreshTickets()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh tickets">
                                <svg id="refresh-tickets-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span class="hidden lg:inline">Refresh</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex-1 bg-white rounded-2xl p-6 shadow-sm overflow-y-auto mx-4">
                    <div class="mb-4 flex justify-end items-center">
                        <div id="ticket-limit-info" class="text-sm text-gray-600"></div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="text-center">
                                        <button id="student-ticket-id-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Ticket ID">
                                            Ticket ID
                                            <span id="student-ticket-id-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center">
                                        <button id="student-ticket-name-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Student Name">
                                            Student Name
                                            <span id="student-ticket-name-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center">
                                        <button id="student-ticket-issue-type-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Issue Type">
                                            Issue Type
                                            <span id="student-ticket-issue-type-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center font-bold">Details</th>
                                    <th class="text-center">
                                        <button id="student-ticket-status-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Status">
                                            Status
                                            <span id="student-ticket-status-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="text-center font-bold">Action</th>
                                </tr>
                            </thead>
                            <tbody id="ticket-table-body">
                                <tr><td colspan="6" class="text-center text-gray-500 py-4">Loading tickets...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- FAQs Page -->
            <div id="faqs-page" class="page-content hidden flex flex-col">
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
                    
                    <!-- FAQ 13 -->
                    <div tabindex="0" class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg shadow-sm">
                        <div class="collapse-title text-lg font-semibold text-text-header">
                            13. How do I use the Support Tickets system?
                        </div>
                        <div class="collapse-content">
                            <p class="text-text-muted mb-3">The Support Tickets system allows you to request assistance from administrators for various account-related issues. Here's how it works:</p>
                            <ul class="list-disc list-inside space-y-2 text-white">
                                <li><strong>Submit a Ticket:</strong> Click "Submit New Ticket" on the Support Tickets page, select your issue type (Incorrect Student Number, Inaccessible PLV Email, Incorrect/Misspelled Name, Reactivate Account, Submitted Record Linked to Wrong Academic Year, or Others), and provide details about your concern.</li>
                                <li><strong>Daily Limit:</strong> You can submit up to 2 support tickets per day to ensure fair access to support services.</li>
                                <li><strong>Track Your Tickets:</strong> View all your submitted tickets in the Support Tickets table, showing Ticket ID, Student Name, Issue Type, Details, Status, and Action buttons.</li>
                                <li><strong>Status Updates:</strong> Tickets have three statuses - <span class="text-yellow-700 font-semibold">Pending</span> (waiting for admin review), <span class="text-green-700 font-semibold">Resolved</span> (admin has addressed your issue), or <span class="text-gray-500 font-semibold">Closed</span> (ticket completed).</li>
                                <li><strong>Mark as Done:</strong> Once a ticket is resolved and you've verified the fix, click "Mark as Done" to close the ticket.</li>
                                <li><strong>When to Use:</strong> Submit a support ticket when you need corrections to your account information, have issues accessing your PLV email, or need help with records linked to the wrong academic year.</li>
                            </ul>
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
                        <span class="label-text font-semibold">Name of Organizing Committee or Supervisor</span>
                    </div>
                    <input type="text" id="organization" placeholder="Enter the name here" class="input input-bordered w-full rounded-lg" required />
                </label>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Supervisor Name</span>
                    </div>
                    <input type="text" id="supervisor-name" placeholder="Enter supervisor name here" class="input input-bordered w-full rounded-lg" />
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
                            <th class="text-center">Supervisor Name</th>
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
            
            <div class="bg-red-100 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-800 rounded-lg p-4">
                <p class="text-base whitespace-pre-wrap font-bold leading-relaxed" id="rejection-reason-text" style="color: #991b1b;">
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
                <!-- Inactive Account Warning (Always at top if account is inactive) -->
                @if(auth()->user()->status === 'inactive' && auth()->user()->inactive_at)
                    @php
                        $inactiveSince = \Carbon\Carbon::parse(auth()->user()->inactive_at);
                        $deletionDate = $inactiveSince->copy()->addDays(7);
                        $now = now();
                        
                        // Calculate days remaining - use ceiling to always round up
                        $totalHours = $now->diffInHours($deletionDate, false);
                        $daysRemaining = max(0, (int) ceil($totalHours / 24));
                        $hoursRemaining = max(0, (int) $totalHours);
                    @endphp
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm sticky top-0 z-10">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-red-800 text-sm">⚠️ Account Inactive - Deletion Scheduled</h4>
                                <p class="text-xs text-red-700 mt-1">
                                    Your account will be deleted in 
                                    <strong class="text-red-900">
                                        @if($daysRemaining > 0)
                                            {{ $daysRemaining }} day{{ $daysRemaining != 1 ? 's' : '' }}
                                        @else
                                            {{ $hoursRemaining }} hour{{ $hoursRemaining != 1 ? 's' : '' }}
                                        @endif
                                    </strong>
                                    ({{ $deletionDate->format('M d, Y') }})
                                </p>
                                <p class="text-xs text-red-600 mt-1">Contact your administrator immediately to reactivate your account.</p>
                            </div>
                        </div>
                    </div>
                @endif
                
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

    <!-- Submit Ticket Modal -->
    <dialog id="submit_ticket_modal" class="modal">
        <div class="modal-box p-6 max-w-lg rounded-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-6 text-center text-text-header">Submit New Support Ticket</h3>
            <form id="submit-ticket-form" class="space-y-4">
                <p class="text-sm text-text-muted mb-4">Please select the issue you need assistance with.</p>

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Select Issue Type</span>
                    </div>
                    <select id="ticket-issue-type" class="select select-bordered w-full rounded-lg" required>
                        <option value="" disabled selected>Select the issue...</option>
                        <option value="1">Incorrect Student Number</option>
                        <option value="2">Inaccessible PLV Email</option>
                        <option value="3">Incorrect/Misspelled Name</option>
                        <option value="4">Reactivate Account</option>
                        <option value="5">Submitted Record Linked to Wrong Academic Year</option>
                        <option value="99">Others (Specify below)</option>
                    </select>
                </label>

                <label id="other-issue-container" class="form-control w-full hidden">
                    <div class="label">
                        <span class="label-text font-semibold">Specific Reason (Required)</span>
                    </div>
                    <textarea id="ticket-details-other" class="textarea textarea-bordered h-24 rounded-lg"
                        placeholder="Please specify the exact nature of your issue..."></textarea>
                </label>

                <label id="record-selector-container" class="form-control w-full hidden">
                    <div class="label">
                        <span class="label-text font-semibold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 00-4 4v4a4 4 0 004 4h4a4 4 0 004-4V8a4 4 0 00-4-4H8zm0 2h4a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z" clip-rule="evenodd" />
                                <path d="M5 5a1 1 0 011-1h1a1 1 0 110 2H6a1 1 0 01-1-1zm0 4a1 1 0 011-1h1a1 1 0 110 2H6a1 1 0 01-1-1zm9-4a1 1 0 10-2 0v1a1 1 0 102 0V5z" />
                            </svg>
                            Attach/Link Record (Required)
                        </span>
                    </div>
                    <select id="ticket-record-id" class="select select-bordered w-full rounded-lg">
                        <option value="" disabled selected>Loading records...</option>
                    </select>
                    <div class="label">
                        <span class="label-text-alt text-text-muted">📌 Select the verified or approved record that was linked to the wrong academic year</span>
                    </div>
                </label>
                
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Reason/Details (required)</span>
                    </div>
                    <textarea id="ticket-details" class="textarea textarea-bordered h-24 rounded-lg"
                        placeholder="Please briefly explain the issue and what correction is needed." required></textarea>
                </label>

                <div class="mt-8 pt-4 flex justify-center">
                    <button type="button" id="confirm-ticket-submit"
                        class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Ticket Details Modal -->
    <dialog id="ticket_details_modal" class="modal">
        <div class="modal-box p-6 max-w-2xl rounded-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-2xl mb-6 text-center text-primary-purple">Ticket Details</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Ticket ID</p>
                        <p id="modal-ticket-id" class="text-lg font-bold text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Status</p>
                        <div id="modal-ticket-status" class="inline-block mt-1"></div>
                    </div>
                </div>
                
                <div>
                    <p class="text-sm font-semibold text-gray-600">Student Name</p>
                    <p id="modal-ticket-student" class="text-base text-gray-900"></p>
                </div>
                
                <div>
                    <p class="text-sm font-semibold text-gray-600">Issue Type</p>
                    <p id="modal-ticket-type" class="text-base text-gray-900"></p>
                </div>
                
                <div>
                    <p class="text-sm font-semibold text-gray-600">Details</p>
                    <p id="modal-ticket-details" class="text-base text-gray-900 whitespace-pre-wrap"></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Submitted</p>
                        <p id="modal-ticket-submitted" class="text-sm text-gray-700"></p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Last Updated</p>
                        <p id="modal-ticket-updated" class="text-sm text-gray-700"></p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center">
                <button type="button" onclick="document.getElementById('ticket_details_modal').close()" 
                    class="btn bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                    Close
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Export Options Modal -->
    <dialog id="export_options_modal" class="modal">
        <div class="modal-box p-6 max-w-sm rounded-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-xl mb-6 text-center text-text-header">Export Approved Records</h3>
            <div class="space-y-4">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Select School Year</span>
                    </div>
                    <select id="export-school-year" class="select select-bordered w-full rounded-lg">
                        <!-- Options will be populated by JS -->
                    </select>
                </label>
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Select Year Level</span>
                    </div>
                    <select id="export-year-level" class="select select-bordered w-full rounded-lg">
                        <option>1st Year</option>
                        <option>2nd Year</option>
                        <option selected>3rd Year</option>
                        <option>4th Year</option>
                    </select>
                </label>
                <div class="modal-action mt-6">
                    <button type="button" class="btn btn-neutral rounded-lg w-full" onclick="exportToPDF()">Generate PDF</button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"> <button>close</button> </form>
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
            
            // Update page title dynamically
            const pageTitle = document.getElementById('page-title');
            const pageTitles = {
                'dashboard': 'Student Dashboard',
                'record-status': 'Record Status',
                'support': 'Support Tickets',
                'faqs': 'Frequently Asked Questions',
                'profile': 'Profile Information'
            };
            if (pageTitle && pageTitles[pageId]) {
                pageTitle.textContent = pageTitles[pageId];
            }
            
            const newPage = document.getElementById(pageId + '-page'); if (newPage) newPage.classList.remove('hidden');
            const navLink = document.getElementById('nav-' + pageId); if (navLink) navLink.classList.add('bg-primary-purple', 'active-nav', 'rounded-lg');
            
            // Save current page to localStorage for student
            try {
                localStorage.setItem('scms_student_current_page', pageId);
            } catch(_) {}
            
            if (pageId === 'profile') { showViewMode(); }
            if (pageId === 'dashboard' && typeof renderCharts === 'function') { renderCharts(); }
            if (pageId === 'support') { loadTickets(); }
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

        // ==================== GLOBAL HELPERS ====================
        // Define these globally so they can be used by all functions
        const BASE_PATH = @json($BASE_PATH);
        const SUPER_ADMIN_NAME = @json($superAdminName);
        
        // ==================== CSRF TOKEN SETUP ====================
        // Simple helper to get CSRF token from meta tag
        function getCsrfToken() {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            return metaTag ? metaTag.getAttribute('content') : '';
        }
        
        // Auto-refresh CSRF token every 5 minutes to prevent expiration
        setInterval(async () => {
            try {
                const response = await fetch(`${BASE_PATH}/api/refresh-csrf`, {
                    method: 'GET',
                    credentials: 'include'
                });
                const data = await response.json();
                if (data.token) {
                    // Update meta tag with new token
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', data.token);
                    }
                }
            } catch (e) {
                console.warn('[CSRF] Failed to auto-refresh token');
            }
        }, 5 * 60 * 1000); // Every 5 minutes
        
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }
        
        async function ensureCsrfCookie() {
            try {
                // Always fetch fresh CSRF cookie to ensure it's valid
                const response = await fetch(`${BASE_PATH}/api/csrf-cookie`, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 
                        'Accept': 'application/json', 
                        'X-Requested-With': 'XMLHttpRequest', 
                        'Cache-Control': 'no-cache' 
                    }
                });
                
                // Wait a moment for cookie to be set
                await new Promise(resolve => setTimeout(resolve, 100));
                
                // Update CSRF token in meta tag
                if (response.ok) {
                    const xsrfToken = getCookie('XSRF-TOKEN');
                    if (xsrfToken) {
                        const csrfToken = decodeURIComponent(xsrfToken);
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) {
                            metaTag.setAttribute('content', csrfToken);
                        }
                        console.log('[CSRF] Token refreshed successfully');
                        return true;
                    } else {
                        console.warn('[CSRF] No XSRF-TOKEN cookie found after fetch');
                    }
                } else {
                    console.warn('[CSRF] Failed to fetch CSRF cookie:', response.status);
                }
            } catch (e) {
                console.error('[CSRF] Error fetching CSRF cookie:', e);
            }
            return false;
        }

        // ==================== SUPPORT TICKETS ====================
        let allTickets = [];

        /**
         * Refresh tickets with visual feedback
         */
        async function refreshTickets() {
            const refreshBtn = document.getElementById('refresh-tickets-btn');
            const refreshIcon = document.getElementById('refresh-tickets-icon');
            
            // Add spinning animation
            refreshBtn.disabled = true;
            refreshIcon.classList.add('animate-spin');
            
            try {
                await loadTickets();
                showToast('Tickets refreshed successfully', 'success');
            } catch (error) {
                showToast('Failed to refresh tickets', 'error');
            } finally {
                // Remove spinning animation
                refreshBtn.disabled = false;
                refreshIcon.classList.remove('animate-spin');
            }
        }

        /**
         * Load tickets from database
         */
        async function loadTickets() {
            try {
                const response = await fetch(`${BASE_PATH}/api/support-tickets`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();
                
                if (data.success) {
                    allTickets = data.tickets || [];
                    renderTicketsTable();
                    updateTicketLimitInfo(data.remaining_tickets);
                } else {
                    console.error('Failed to load tickets:', data.message);
                    showToast('Failed to load tickets', 'error');
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
                showToast('Error loading tickets', 'error');
            }
        }

        /**
         * Update ticket limit info display
         */
        function updateTicketLimitInfo(remaining) {
            const limitInfo = document.getElementById('ticket-limit-info');
            if (limitInfo) {
                if (remaining > 0) {
                    limitInfo.innerHTML = `<span class="text-green-600">📝 You can submit ${remaining} more ticket(s) today</span>`;
                } else {
                    limitInfo.innerHTML = `<span class="text-red-600">⚠️ Daily limit reached (2/2 tickets)</span>`;
                }
            }
        }

        /**
         * Show ticket details in modal
         */
        function showTicketDetails(ticketId) {
            const ticket = allTickets.find(t => t.id == ticketId);
            if (!ticket) return;

            document.getElementById('modal-ticket-id').textContent = ticket.id;
            document.getElementById('modal-ticket-student').textContent = ticket.student_name || 'N/A';
            document.getElementById('modal-ticket-type').textContent = ticket.type;
            document.getElementById('modal-ticket-details').textContent = ticket.details;
            document.getElementById('modal-ticket-submitted').textContent = ticket.submitted_at || ticket.date;
            document.getElementById('modal-ticket-updated').textContent = ticket.updated_at || ticket.date;

            // Status badge
            let statusBadgeClass = '';
            switch(ticket.status) {
                case 'Pending':
                    statusBadgeClass = 'badge bg-yellow-100 text-yellow-800 border-0';
                    break;
                case 'Resolved':
                    statusBadgeClass = 'badge bg-green-100 text-green-800 border-0';
                    break;
                case 'Closed':
                    statusBadgeClass = 'badge bg-gray-100 text-gray-800 border-0';
                    break;
                default:
                    statusBadgeClass = 'badge bg-blue-100 text-blue-800 border-0';
            }
            document.getElementById('modal-ticket-status').innerHTML = 
                `<div class="${statusBadgeClass} font-semibold">${ticket.status}</div>`;

            document.getElementById('ticket_details_modal').showModal();
        }

        /**
         * Delete a pending ticket
         */
        async function deleteTicket(ticketId) {
            if (!confirm('Are you sure you want to delete this ticket?')) {
                return;
            }

            try {
                await ensureCsrfCookie();
                
                const response = await fetch(`${BASE_PATH}/api/support-tickets/${ticketId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Ticket deleted successfully', 'success');
                    await loadTickets();
                } else {
                    showToast(data.message || 'Failed to delete ticket', 'error');
                }
            } catch (error) {
                console.error('Error deleting ticket:', error);
                showToast('Error deleting ticket', 'error');
            }
        }

        /**
         * Mark a resolved ticket as done
         */
        async function markTicketDone(ticketId) {
            if (!confirm('Mark this ticket as done? This will remove it from your list.')) {
                return;
            }

            try {
                await ensureCsrfCookie();
                
                const response = await fetch(`${BASE_PATH}/api/support-tickets/${ticketId}/done`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Ticket marked as done', 'success');
                    await loadTickets();
                } else {
                    showToast(data.message || 'Failed to mark ticket as done', 'error');
                }
            } catch (error) {
                console.error('Error marking ticket as done:', error);
                showToast('Error marking ticket as done', 'error');
            }
        }

        // Make ticket functions globally accessible
        window.showTicketDetails = showTicketDetails;
        window.deleteTicket = deleteTicket;
        window.markTicketDone = markTicketDone;

        /**
         * Renders the allTickets array into the HTML ticket table.
         */
        function renderTicketsTable() {
            const tableBody = document.getElementById('ticket-table-body');
            if (!tableBody) return;
            tableBody.innerHTML = ''; 

            // Filter logic for search
            const searchLower = document.getElementById('ticket-search-input')?.value.toLowerCase() || '';

            const filteredTickets = allTickets.filter(ticket => 
                !searchLower || 
                String(ticket.id).includes(searchLower) ||
                ticket.type.toLowerCase().includes(searchLower) ||
                ticket.details.toLowerCase().includes(searchLower) ||
                (ticket.student_name && ticket.student_name.toLowerCase().includes(searchLower))
            );

            if (filteredTickets.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="6" class="text-center text-gray-500 py-4">No tickets found.</td></tr>`;
                return;
            }

            filteredTickets.forEach(ticket => {
                let statusBadgeClass = '';
                switch(ticket.status) {
                    case 'Pending':
                        statusBadgeClass = 'bg-yellow-100 text-yellow-800';
                        break;
                    case 'Resolved':
                        statusBadgeClass = 'bg-green-100 text-green-800';
                        break;
                    case 'Closed':
                        statusBadgeClass = 'bg-gray-100 text-gray-800';
                        break;
                    default:
                        statusBadgeClass = 'bg-blue-100 text-blue-800';
                }

                const newRow = document.createElement('tr');
                
                const shortDetails = ticket.details.split('\n')[0]; // Shows first line of details
                
                // Action buttons based on status
                let actionButton = '';
                if (ticket.status === 'Pending') {
                    actionButton = `<button onclick="deleteTicket(${ticket.id})" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white rounded-lg">Delete</button>`;
                } else if (ticket.status === 'Resolved') {
                    actionButton = `<button onclick="markTicketDone(${ticket.id})" class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg">Done</button>`;
                }
                
                newRow.innerHTML = `
                    <td class="font-medium text-text-header cursor-pointer" onclick="showTicketDetails(${ticket.id})">${ticket.id}</td>
                    <td class="text-text-header cursor-pointer" onclick="showTicketDetails(${ticket.id})">${ticket.student_name || 'N/A'}</td>
                    <td class="text-text-header cursor-pointer" onclick="showTicketDetails(${ticket.id})">${ticket.type}</td>
                    <td class="text-text-muted text-sm truncate max-w-xs cursor-pointer" title="${ticket.details}" onclick="showTicketDetails(${ticket.id})">${shortDetails}</td>
                    <td class="cursor-pointer" onclick="showTicketDetails(${ticket.id})">
                        <div class="flex flex-col gap-1">
                            <div class="badge ${statusBadgeClass} font-semibold border-0">
                                ${ticket.status}
                            </div>
                            <span class="text-xs text-gray-500">${ticket.date}</span>
                        </div>
                    </td>
                    <td>${actionButton}</td>
                `;
                tableBody.appendChild(newRow);
            });
        }

        // Support tickets sorting for student
        var studentTicketsSortColumn = null;
        var studentTicketsSortDirection = 'asc';

        function sortStudentTickets(column) {
            // Toggle direction if same column, otherwise default to ascending
            if (studentTicketsSortColumn === column) {
                studentTicketsSortDirection = studentTicketsSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                studentTicketsSortColumn = column;
                studentTicketsSortDirection = 'asc';
            }
            
            // Reset all ticket sort icons
            document.querySelectorAll('[id^="student-ticket-"][id$="-sort-icon"]').forEach(icon => {
                icon.textContent = '⇅';
            });
            
            // Update current column icon
            var iconId = column + '-sort-icon';
            var icon = document.getElementById(iconId);
            if (icon) {
                icon.textContent = studentTicketsSortDirection === 'asc' ? '↑' : '↓';
            }
            
            // Sort the tickets array
            allTickets.sort((a, b) => {
                let aVal, bVal;
                
                switch(column) {
                    case 'student-ticket-id':
                        aVal = parseInt(a.id) || 0;
                        bVal = parseInt(b.id) || 0;
                        break;
                    case 'student-ticket-name':
                        aVal = (a.student_name || '').toLowerCase();
                        bVal = (b.student_name || '').toLowerCase();
                        break;
                    case 'student-ticket-issue-type':
                        aVal = (a.type || '').toLowerCase();
                        bVal = (b.type || '').toLowerCase();
                        break;
                    case 'student-ticket-status':
                        aVal = (a.status || '').toLowerCase();
                        bVal = (b.status || '').toLowerCase();
                        break;
                    default:
                        return 0;
                }
                
                if (studentTicketsSortDirection === 'asc') {
                    return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
                } else {
                    return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
                }
            });
            
            renderTicketsTable();
        }

        // --- Global Records Array (accessible to PDF export functions) ---
        let allRecords = [];

        // Helper function to get current date/time in Philippine timezone (Asia/Manila, UTC+8)
        // Defined globally so it can be used by all functions
        function getPhilippineDate(dateInput = null) {
            if (!dateInput) {
                // If no input, return current Philippine time
                return new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            }
            // Parse the input date and convert to Philippine timezone
            const date = new Date(dateInput);
            // Convert to Philippine timezone by getting the locale string and creating a new Date
            const phTimeString = date.toLocaleString('en-US', { timeZone: 'Asia/Manila' });
            return new Date(phTimeString);
        }

        // --- Table & Modal Logic ---
        function initDashboard() {
            // idempotent init: avoid double initialization if this script runs twice
            if (window.__scms_dashboard_inited) return;
            window.__scms_dashboard_inited = true;

            // BASE_PATH, getCookie, and ensureCsrfCookie are now defined globally above
            
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
            // All sort directions default to 'desc' for consistency
            let dateSortDirection = 'desc';
            const dateSortToggle = document.getElementById('date-sort-toggle');
            const dateSortIndicator = document.getElementById('date-sort-indicator');
            let hoursSortDirection = 'desc';
            let currentSortBy = 'date'; // 'date' or 'hours' or 'eventname' or 'venue' or 'organization' or 'supervisor' or 'status'
            const hoursSortToggle = document.getElementById('hours-sort-toggle');
            const hoursSortIndicator = document.getElementById('hours-sort-indicator');
            // Event Name sort
            let eventnameSortDirection = 'desc';
            const eventnameSortToggle = document.getElementById('eventname-sort-toggle');
            const eventnameSortIndicator = document.getElementById('eventname-sort-indicator');
            // Venue sort
            let venueSortDirection = 'desc';
            const venueSortToggle = document.getElementById('venue-sort-toggle');
            const venueSortIndicator = document.getElementById('venue-sort-indicator');
            // Organization sort
            let organizationSortDirection = 'desc';
            const organizationSortToggle = document.getElementById('organization-sort-toggle');
            const organizationSortIndicator = document.getElementById('organization-sort-indicator');
            // Supervisor sort
            let supervisorSortDirection = 'desc';
            const supervisorSortToggle = document.getElementById('supervisor-sort-toggle');
            const supervisorSortIndicator = document.getElementById('supervisor-sort-indicator');
            // Status sort
            let statusSortDirection = 'desc';
            const statusSortToggle = document.getElementById('status-sort-toggle');
            const statusSortIndicator = document.getElementById('status-sort-indicator');
            
            // Normalize API date to YYYY-MM-DD and format to MM-DD-YYYY without timezone shifts
            function normalizeDateString(dateVal) {
                if (!dateVal) return '';
                let s = String(dateVal);
                // handle ISO datetimes like 2025-10-04T00:00:00.000000Z
                if (s.includes('T')) {
                    // Extract just the date part (YYYY-MM-DD) to avoid timezone conversion
                    s = s.substring(0, 10);
                }
                // Expect s as YYYY-MM-DD now
                const parts = s.split('-');
                if (parts.length === 3) {
                    const [y, m, d] = parts;
                    // Return MM-DD-YYYY format (Month-Day-Year)
                    return `${m.padStart(2,'0')}-${d.padStart(2,'0')}-${y}`;
                }
                // If not in expected format, return as-is
                return s;
            }
            var lastRecordsData = null; // Store last data to detect changes
            var isLoadingRecords = false; // Prevent concurrent requests
            
            // Reset all sort indicators to default (double arrow for inactive)
            function resetAllSortIndicators() {
                dateSortIndicator.textContent = '⇅';
                hoursSortIndicator.textContent = '⇅';
                eventnameSortIndicator.textContent = '⇅';
                venueSortIndicator.textContent = '⇅';
                organizationSortIndicator.textContent = '⇅';
                supervisorSortIndicator.textContent = '⇅';
                statusSortIndicator.textContent = '⇅';
            }
            
            function loadRecords(showLoading = true) {
                // Prevent concurrent requests
                if (isLoadingRecords) {
                    console.log('Already loading records, skipping...');
                    return Promise.resolve();
                }
                
                // Only clear table on initial load
                if (showLoading && !lastRecordsData) {
                    tableBody.innerHTML = '';
                }
                
                isLoadingRecords = true;
                
                // Add timestamp to URL to prevent caching
                var timestamp = new Date().getTime();
                
                return fetch(`${BASE_PATH}/api/social-contract/records?_=${timestamp}`, {
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
                        console.log('✅ Records loaded into allRecords:', allRecords.length, 'records');
                        console.log('Sample record with all fields:', allRecords[0]);
                        console.log('Date fields in sample record:', {
                            date: allRecords[0]?.date,
                            date_of_activity: allRecords[0]?.date_of_activity,
                            verified_at: allRecords[0]?.verified_at,
                            approved_at: allRecords[0]?.approved_at,
                            rejected_at: allRecords[0]?.rejected_at,
                            action_date: allRecords[0]?.action_date
                        });
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
                let step2Date = '';
                let step3Class = '';
                let step3Icon = '3';
                let step3Label = 'Pending';
                let step3Date = '';
                let connector1Class = 'completed';
                let connector2Class = '';
                let deletionCountdown = '';
                
                if (record.status === 'Pending') {
                    step2Class = 'pending';
                    step2Label = 'Awaiting verification';
                    step2Date = '';
                } else if (record.status === 'Verified') {
                    step2Class = 'completed';
                    step2Icon = '2';
                    step2Label = 'Verified';
                    step2Date = record.verified_at || '';
                    // Format the date if it's in ISO format using Philippine timezone
                    if (step2Date && step2Date.includes('T')) {
                        const dateObj = getPhilippineDate(step2Date);
                        step2Date = `${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}-${dateObj.getFullYear()}`;
                    }
                    connector1Class = 'completed';
                    connector2Class = 'active';
                    step3Class = 'active';
                    step3Label = 'Awaiting approval';
                    step3Date = '';
                } else if (record.status === 'Approved') {
                    step2Class = 'completed';
                    step2Icon = '2';
                    step2Label = 'Verified';
                    step2Date = record.verified_at || record.approval?.verified_at || '';
                    // Format step2Date if it's in ISO format using Philippine timezone
                    if (step2Date && step2Date.includes('T')) {
                        const dateObj = getPhilippineDate(step2Date);
                        step2Date = `${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}-${dateObj.getFullYear()}`;
                    }
                    step3Class = 'completed';
                    step3Icon = '3';
                    step3Label = 'Approved';
                    step3Date = record.approved_at || record.approval?.approved_at || '';
                    // Format step3Date if it's in ISO format using Philippine timezone
                    if (step3Date && step3Date.includes('T')) {
                        const dateObj = getPhilippineDate(step3Date);
                        step3Date = `${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}-${dateObj.getFullYear()}`;
                    }
                    connector1Class = 'completed';
                    connector2Class = 'completed';
                } else if (record.status === 'Rejected') {
                    // Determine if rejected at step 2 or 3
                    // For now, assume rejection at step 2
                    step2Class = 'rejected';
                    step2Icon = '✕';
                    step2Label = 'Rejected';
                    // Try rejected_at, then approval date, then the raw date field, then action_date
                    const rejectedAtRaw = record.rejected_at || record.approval?.rejected_at || '';
                    step2Date = rejectedAtRaw;
                    
                    // Calculate deletion countdown
                    if (rejectedAtRaw && rejectedAtRaw.includes('T')) {
                        const rejectedDate = getPhilippineDate(rejectedAtRaw);
                        const deletionDate = new Date(rejectedDate.getTime() + (7 * 24 * 60 * 60 * 1000)); // 7 days from rejection
                        const now = new Date();
                        const timeLeft = deletionDate - now;
                        
                        if (timeLeft > 0) {
                            const daysLeft = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                            const hoursLeft = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            deletionCountdown = `Time left before deletion: ${daysLeft} days ${hoursLeft} hours`;
                        } else {
                            deletionCountdown = 'Scheduled for deletion';
                        }
                        
                        // Format the date for display
                        step2Date = `${String(rejectedDate.getMonth() + 1).padStart(2, '0')}-${String(rejectedDate.getDate()).padStart(2, '0')}-${rejectedDate.getFullYear()}`;
                    }
                    
                    connector1Class = 'completed';
                    
                    // Debug log for rejected records
                    console.log('Rejected record stepper:', {
                        rejected_at: record.rejected_at,
                        'approval?.rejected_at': record.approval?.rejected_at,
                        date: record.date,
                        action_date: record.action_date,
                        step2Date: step2Date,
                        deletionCountdown: deletionCountdown,
                        record: record
                    });
                }
                
                detailsRow.innerHTML = `
                    <td colspan="8">
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
                                ${step2Date && step2Date.trim() ? '<div class="step-sublabel" style="margin-top: 0.25rem;">' + step2Date + '</div>' : ''}
                                ${deletionCountdown ? '<div class="step-sublabel text-error" style="margin-top: 0.25rem; font-weight: 600;">' + deletionCountdown + '</div>' : ''}
                            </div>
                            <div class="step-item">
                                <div class="step-circle ${step3Class}">${step3Icon}</div>
                                <div class="step-label">Super Admin Review</div>
                                <div class="step-sublabel">${step3Label}</div>
                                ${step3Date && step3Date.trim() ? '<div class="step-sublabel" style="margin-top: 0.25rem;">' + step3Date + '</div>' : ''}
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
                            <td class="text-center">${rec.supervisor_name || '-'}</td>
                            <td class="text-center">${rec.organization || '-'}</td>
                            <td class="text-center">${rec.hours_rendered} hours</td>
                            <td class="text-center">${renderStatusBadge(rec.status, rec)}</td>
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
            
            // Navigate to a specific record
            async function goToRecord(recordId) {
                try {
                    // switch to record status page
                    showPage('record-status');

                    // ensure records are loaded
                    if (!Array.isArray(allRecords) || allRecords.length === 0) {
                        // loadRecords returns immediately if already fetching, otherwise populates allRecords
                        await loadRecords();
                        // small delay to allow DOM render
                        await new Promise(r => setTimeout(r, 200));
                    }

                    // try to find the row
                    const selector = `#record-table-body tr[data-record-id="${recordId}"]`;
                    let row = document.querySelector(selector);

                    // if not found, wait a bit and try again (records may still be rendering)
                    if (!row) {
                        await new Promise(r => setTimeout(r, 300));
                        row = document.querySelector(selector);
                    }

                    if (row) {
                        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // trigger click to expand details (same as user click)
                        row.click();
                        return;
                    }

                    // fallback: if record exists in allRecords but row not rendered yet, open status modal for its status
                    const rec = (Array.isArray(allRecords) && allRecords.find(r => String(r.id) === String(recordId)));
                    if (rec && rec.status) {
                        showStatusModal(rec.status);
                        showToast('Record found but table row not rendered; opened status modal.', 'info');
                        return;
                    }

                    showToast('Record not found', 'warning');
                } catch (err) {
                    console.error('goToRecord error', err);
                    showToast('Failed to open record', 'error');
                }
            }
            window.goToRecord = goToRecord;
            
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
                    case 'deleted':
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />';
                        iconColor = 'text-orange-500';
                        statusText = 'Record Deleted';
                        statusColor = 'text-orange-600 dark:text-orange-600';
                        statusHex = '#f97316';
                        break;
                    default:
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                        iconColor = 'text-gray-500';
                        statusText = 'Notification'; // Default text
                        statusColor = 'text-gray-700 dark:text-gray-700';
                        statusHex = '#374151';
                }
                
                const isUnread = !notif.is_read;
                const bgClass = isUnread ? 'bg-purple-50 dark:bg-purple-900/10' : '';
                
                // Use the 'message' field from your database
                const messageDetails = notif.message ? `<p class="text-xs text-gray-700 dark:text-gray mt-1">${notif.message}</p>` : '';
                
                let reasonSection = '';
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
                            <div class="reason-content hidden mt-2 p-3 bg-red-100 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-800 rounded-lg">
                                <p class="text-xs font-bold mb-1" style="color: #7f1d1d;">Rejection Reason:</p>
                                <p class="text-sm whitespace-pre-wrap font-bold leading-relaxed" style="color: #991b1b;">${notif.rejection_reason}</p>
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

                // 1. Build the inner content
                const innerContent = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ${iconColor} mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        ${icon}
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-black">
                            ${notif.title || `Your submission has been ${statusText}`}
                        </p>
                        ${messageDetails}
                        <p class="text-xs text-gray dark:text-gray mt-1">${notif.created_at}</p>
                        ${reasonSection}
                    </div>
                `;

                // 2. Check if it should be clickable
                // We use social_contract_record_id, which you already have in your table
                if (notif.social_contract_record_id) {
                    // This notification is for a record. Use the SPA navigation.
                    return `
                        <li id="notif-${notif.id}" class="relative ${bgClass} p-0">
                            <button onclick="goToRecord(${notif.social_contract_record_id})" class="flex items-start p-3 w-full text-left border-b border-gray-100 dark:border-gray-300 hover:bg-purple-100 dark:hover:bg-purple-800/30 transition-all duration-200 cursor-pointer">
                                ${innerContent}
                            </button>
                            ${deleteBtn}
                        </li>
                    `;
                } else if (notif.link) {
                    // This is a general link (e.g., to profile page) that was in your model
                    // We will make it navigate to the 'profile' page using your SPA function
                    // This assumes links are relative, like '/profile'
                    let pageId = 'dashboard'; // default
                    if (notif.link.includes('profile')) {
                        pageId = 'profile';
                    } else if (notif.link.includes('support')) {
                        pageId = 'support';
                    } // etc.
                    
                    // A better way: just check for a record ID.
                    // If you have other links, we can add them here.
                    return `
                        <li id="notif-${notif.id}" class="relative ${bgClass}">
                            <div class="flex items-start p-3 w-full border-b border-gray-100 dark:border-gray-300">
                                ${innerContent}
                                ${deleteBtn}
                            </div>
                        </li>
                    `;
                } else {
                    // Not clickable (e.g., a general announcement)
                    return `
                        <li id="notif-${notif.id}" class="relative ${bgClass}">
                            <div class="flex items-start p-3 w-full border-b border-gray-100 dark:border-gray-300">
                                ${innerContent}
                                ${deleteBtn}
                            </div>
                        </li>
                    `;
                }
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
                    // Ensure fresh CSRF token
                    await ensureCsrfCookie();
                    
                    const response = await fetch(`/api/notifications/${notifId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
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
                        (r.organization || '').toLowerCase().includes(query) ||
                        (r.supervisor_name || '').toLowerCase().includes(query)
                    );
                });
                // sort by current sort column
                filtered.sort((a, b) => {
                    if (currentSortBy === 'hours') {
                        const ha = parseInt(a.hours_rendered) || 0;
                        const hb = parseInt(b.hours_rendered) || 0;
                        return hoursSortDirection === 'asc' ? ha - hb : hb - ha;
                    } else if (currentSortBy === 'eventname') {
                        const ea = (a.event_name || '').toLowerCase();
                        const eb = (b.event_name || '').toLowerCase();
                        if (ea < eb) return eventnameSortDirection === 'asc' ? -1 : 1;
                        if (ea > eb) return eventnameSortDirection === 'asc' ? 1 : -1;
                        return 0;
                    } else if (currentSortBy === 'venue') {
                        const va = (a.venue || '').toLowerCase();
                        const vb = (b.venue || '').toLowerCase();
                        if (va < vb) return venueSortDirection === 'asc' ? -1 : 1;
                        if (va > vb) return venueSortDirection === 'asc' ? 1 : -1;
                        return 0;
                    } else if (currentSortBy === 'organization') {
                        const oa = (a.organization || '').toLowerCase();
                        const ob = (b.organization || '').toLowerCase();
                        if (oa < ob) return organizationSortDirection === 'asc' ? -1 : 1;
                        if (oa > ob) return organizationSortDirection === 'asc' ? 1 : -1;
                        return 0;
                    } else if (currentSortBy === 'supervisor') {
                        const sa = (a.supervisor_name || '').toLowerCase();
                        const sb = (b.supervisor_name || '').toLowerCase();
                        if (sa < sb) return supervisorSortDirection === 'asc' ? -1 : 1;
                        if (sa > sb) return supervisorSortDirection === 'asc' ? 1 : -1;
                        return 0;
                    } else if (currentSortBy === 'status') {
                        const sta = (a.status || '').toLowerCase();
                        const stb = (b.status || '').toLowerCase();
                        if (sta < stb) return statusSortDirection === 'asc' ? -1 : 1;
                        if (sta > stb) return statusSortDirection === 'asc' ? 1 : -1;
                        return 0;
                    } else {
                        // sort by date using Philippine timezone
                        const da = getPhilippineDate(a.date);
                        const db = getPhilippineDate(b.date);
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

                    // Add action date if available
                    let statusHtml = renderStatusBadge(rec.status, rec);
                    
                    row.innerHTML = `
                        <td class="text-center" style="min-width: 50px; width: 50px;"><input type="checkbox" class="record-checkbox" ${rec.status !== 'Pending' ? 'disabled' : ''}></td>
                        <td class="text-center" style="min-width: 80px; width: 80px; white-space: nowrap;">${formattedDate}</td>
                        <td class="text-center" style="min-width: 110px; width: 110px; white-space: nowrap;">${rec.event_name}</td>
                        <td class="text-center" style="min-width: 100px; width: 100px; white-space: nowrap;">${rec.venue}</td>
                        <td class="text-center" style="min-width: 140px; width: 140px; white-space: nowrap;">${rec.organization}</td>
                        <td class="text-center" style="min-width: 120px; width: 120px; white-space: nowrap;">${rec.supervisor_name || '-'}</td>
                        <td class="text-center" style="min-width: 50px; width: 50px; white-space: nowrap;">${rec.hours_rendered} hours</td>
                        <td class="text-center" style="min-width: 180px; width: 180px;">${statusHtml}</td>
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
                resetAllSortIndicators();
                dateSortIndicator.textContent = dateSortDirection === 'asc' ? '↑' : '↓';
                renderTable();
            });
            // Hours sort toggle
            hoursSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'hours';
                hoursSortDirection = hoursSortDirection === 'asc' ? 'desc' : 'asc';
                resetAllSortIndicators();
                hoursSortIndicator.textContent = hoursSortDirection === 'asc' ? '↑' : '↓';
                renderTable();
            });
            // Event Name sort toggle
            eventnameSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'eventname';
                eventnameSortDirection = eventnameSortDirection === 'asc' ? 'desc' : 'asc';
                resetAllSortIndicators();
                eventnameSortIndicator.textContent = eventnameSortDirection === 'asc' ? '↑' : '↓';
                renderTable();
            });
            // Venue sort toggle
            venueSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'venue';
                venueSortDirection = venueSortDirection === 'asc' ? 'desc' : 'asc';
                resetAllSortIndicators();
                venueSortIndicator.textContent = venueSortDirection === 'asc' ? '↑' : '↓';
                renderTable();
            });
            // Organization sort toggle
            organizationSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'organization';
                organizationSortDirection = organizationSortDirection === 'asc' ? 'desc' : 'asc';
                resetAllSortIndicators();
                organizationSortIndicator.textContent = organizationSortDirection === 'asc' ? '↑' : '↓';
                renderTable();
            });
            // Supervisor sort toggle
            supervisorSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'supervisor';
                supervisorSortDirection = supervisorSortDirection === 'asc' ? 'desc' : 'asc';
                resetAllSortIndicators();
                supervisorSortIndicator.textContent = supervisorSortDirection === 'asc' ? '↑' : '↓';
                renderTable();
            });
            // Status sort toggle
            statusSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                currentSortBy = 'status';
                statusSortDirection = statusSortDirection === 'asc' ? 'desc' : 'asc';
                resetAllSortIndicators();
                statusSortIndicator.textContent = statusSortDirection === 'asc' ? '↑' : '↓';
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
                    supervisor_name: document.getElementById('supervisor-name').value || null,
                };
                
                fetch(`${BASE_PATH}/api/social-contract/records`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include',
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
                        const originalName = '{{ auth()->user()->name }}';
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
            document.getElementById('delete-selected').addEventListener('click', async () => {
                // Ensure fresh CSRF token before deletion
                await ensureCsrfCookie();
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
            
            // ==================== TICKET SUPPORT INITIALIZATION ====================
            // Ticket DOM elements
            const ticketElements = {
                ticketIssueType: document.getElementById('ticket-issue-type'),
                ticketDetails: document.getElementById('ticket-details'),
                otherIssueContainer: document.getElementById('other-issue-container'),
                ticketDetailsOther: document.getElementById('ticket-details-other'),
                recordSelectorContainer: document.getElementById('record-selector-container'),
                ticketRecordId: document.getElementById('ticket-record-id'),
                submitTicketForm: document.getElementById('submit-ticket-form'),
                submitTicketModal: document.getElementById('submit_ticket_modal'),
                submitTicketButton: document.getElementById('confirm-ticket-submit'),
                ticketSearchInput: document.getElementById('ticket-search-input')
            };

            // Function to populate record selector with verified/approved records
            async function populateRecordSelector() {
                if (!ticketElements.ticketRecordId) {
                    console.error('Record selector element not found');
                    return;
                }
                
                // Show loading state
                ticketElements.ticketRecordId.innerHTML = '<option value="" disabled selected>Loading records...</option>';
                
                console.log('PopulateRecordSelector called. Current allRecords:', allRecords?.length || 0);
                
                // Ensure records are loaded
                if (!allRecords || allRecords.length === 0) {
                    console.log('No records in memory, loading...');
                    try {
                        await loadRecords(false);
                        console.log('Records loaded. allRecords now has:', allRecords?.length || 0);
                    } catch (e) {
                        console.error('Failed to load records:', e);
                        ticketElements.ticketRecordId.innerHTML = '<option value="" disabled selected>Error loading records</option>';
                        return;
                    }
                }
                
                // Check again after loading
                if (!allRecords || allRecords.length === 0) {
                    console.warn('Still no records after loading attempt');
                    ticketElements.ticketRecordId.innerHTML = '<option value="" disabled selected>No records found</option>';
                    return;
                }
                
                // Filter for verified or approved records only
                const eligibleRecords = allRecords.filter(rec => 
                    rec.status === 'Verified' || rec.status === 'Approved'
                );
                
                console.log('Total records:', allRecords.length);
                console.log('Eligible records (Verified/Approved):', eligibleRecords.length);
                console.log('Sample statuses:', allRecords.slice(0, 5).map(r => ({ id: r.id, status: r.status })));
                
                if (eligibleRecords.length === 0) {
                    ticketElements.ticketRecordId.innerHTML = '<option value="" disabled selected>No verified or approved records available</option>';
                    return;
                }
                
                ticketElements.ticketRecordId.innerHTML = '<option value="" disabled selected>Select a record...</option>';
                
                eligibleRecords.forEach(rec => {
                    try {
                        const option = document.createElement('option');
                        option.value = rec.id;
                        
                        // Format date safely - extract only date part without time
                        let dateStr = 'No date';
                        if (rec.date) {
                            try {
                                let dateValue = String(rec.date);
                                // Remove timestamp if present (e.g., "2025-10-28T00:00:00.000000Z" -> "2025-10-28")
                                if (dateValue.includes('T')) {
                                    dateValue = dateValue.split('T')[0];
                                }
                                // Convert YYYY-MM-DD to MM-DD-YYYY
                                const parts = dateValue.split('-');
                                if (parts.length === 3) {
                                    const [y, m, d] = parts;
                                    dateStr = `${m.padStart(2,'0')}-${d.padStart(2,'0')}-${y}`;
                                } else {
                                    dateStr = dateValue;
                                }
                            } catch (e) {
                                console.error('Error formatting date:', e, rec.date);
                                // Fallback: try to extract just date part
                                let fallback = String(rec.date);
                                if (fallback.includes('T')) {
                                    fallback = fallback.split('T')[0];
                                }
                                dateStr = fallback;
                            }
                        }
                        
                        const eventName = rec.event_name || rec.organization || 'No event name';
                        const venue = rec.venue || 'No venue';
                        const status = rec.status || 'Unknown';
                        option.textContent = `${dateStr} - ${eventName} at ${venue} (${status})`;
                        ticketElements.ticketRecordId.appendChild(option);
                    } catch (e) {
                        console.error('Error creating option for record:', rec.id, e);
                    }
                });
                
                console.log('✅ Populated dropdown with', eligibleRecords.length, 'records');
            }

            // Show/hide "Others" textarea and record selector based on dropdown selection
            if (ticketElements.ticketIssueType) {
                ticketElements.ticketIssueType.addEventListener('change', (e) => {
                    const selectedValue = e.target.value;
                    
                    // Handle "Others" option
                    if (selectedValue === '99') {
                        ticketElements.otherIssueContainer.classList.remove('hidden');
                        ticketElements.ticketDetailsOther.required = true;
                    } else {
                        ticketElements.otherIssueContainer.classList.add('hidden');
                        ticketElements.ticketDetailsOther.required = false;
                        ticketElements.ticketDetailsOther.value = '';
                    }
                    
                    // Handle "Submitted Record Linked to Wrong Academic Year" option
                    if (selectedValue === '5') {
                        ticketElements.recordSelectorContainer.classList.remove('hidden');
                        ticketElements.ticketRecordId.required = true;
                        populateRecordSelector();
                    } else {
                        ticketElements.recordSelectorContainer.classList.add('hidden');
                        ticketElements.ticketRecordId.required = false;
                        ticketElements.ticketRecordId.value = '';
                    }
                });
            }

            // Ticket submission handler
            if (ticketElements.submitTicketButton) {
                ticketElements.submitTicketButton.addEventListener('click', async (e) => {
                    e.preventDefault();
                    
                    const isOtherSelected = ticketElements.ticketIssueType.value === '99';
                    const isWrongAcademicYear = ticketElements.ticketIssueType.value === '5';
                    let specificReason = "";
                    
                    // Manual validation for 'Others' field
                    if (isOtherSelected) {
                        specificReason = ticketElements.ticketDetailsOther.value.trim();
                        if (specificReason === "") {
                            showToast('Please specify the exact reason for your issue.', 'error');
                            ticketElements.ticketDetailsOther.focus();
                            return;
                        }
                    }
                    
                    // Manual validation for record selector
                    if (isWrongAcademicYear) {
                        const selectedRecordId = ticketElements.ticketRecordId.value;
                        if (!selectedRecordId) {
                            showToast('Please select the record that was linked to the wrong academic year.', 'error');
                            ticketElements.ticketRecordId.focus();
                            return;
                        }
                    }
                    
                    // General form validation
                    if (!ticketElements.submitTicketForm.checkValidity()) {
                        ticketElements.submitTicketForm.reportValidity();
                        showToast('Please ensure all required fields are filled.', 'error');
                        return;
                    }

                    const selectedIssueText = ticketElements.ticketIssueType.options[ticketElements.ticketIssueType.selectedIndex].textContent;
                    
                    // Construct final details
                    let finalDetails = ticketElements.ticketDetails.value;
                    if (isOtherSelected && specificReason) {
                        finalDetails = `Specific Issue: ${specificReason}\nDetails: ${finalDetails}`;
                    }
                    
                    // Add record information if wrong academic year issue
                    if (isWrongAcademicYear && ticketElements.ticketRecordId.value) {
                        const selectedRecordText = ticketElements.ticketRecordId.options[ticketElements.ticketRecordId.selectedIndex].textContent;
                        finalDetails = `Record: ${selectedRecordText}\nDetails: ${finalDetails}`;
                    }

                    // Disable button to prevent double submission
                    ticketElements.submitTicketButton.disabled = true;
                    ticketElements.submitTicketButton.textContent = 'Submitting...';

                    try {
                        const requestBody = {
                            issue_type: selectedIssueText,
                            details: finalDetails
                        };
                        
                        // Include record ID if applicable
                        if (isWrongAcademicYear && ticketElements.ticketRecordId.value) {
                            requestBody.record_id = ticketElements.ticketRecordId.value;
                        }
                        
                        const response = await fetch(`${BASE_PATH}/api/support-tickets`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'include',
                            body: JSON.stringify(requestBody)
                        });

                        let data;
                        try {
                            data = await response.json();
                        } catch (jsonError) {
                            console.error('Failed to parse JSON response:', jsonError);
                            throw new Error('Invalid JSON response from server');
                        }

                        if (data.success) {
                            // Reload tickets to show the new one
                            await loadTickets();
                            
                            // Cleanup
                            ticketElements.submitTicketForm.reset();
                            ticketElements.ticketDetailsOther.value = '';
                            ticketElements.otherIssueContainer.classList.add('hidden');
                            ticketElements.ticketRecordId.value = '';
                            ticketElements.recordSelectorContainer.classList.add('hidden');
                            ticketElements.submitTicketModal.close();

                            showToast(data.message || `Ticket #${data.ticket.id} submitted successfully!`, 'success');
                        } else {
                            console.error('API Error:', data);
                            const errorMsg = data.message || (data.errors ? JSON.stringify(data.errors) : 'Failed to submit ticket');
                            showToast(errorMsg, 'error');
                        }
                    } catch (error) {
                        console.error('Caught error:', error);
                        console.error('Error stack:', error.stack);
                        showToast('Error submitting ticket. Please try again.', 'error');
                    } finally {
                        ticketElements.submitTicketButton.disabled = false;
                        ticketElements.submitTicketButton.textContent = 'Submit Ticket';
                    }
                });
            }

            // Ticket search functionality
            if (ticketElements.ticketSearchInput) {
                ticketElements.ticketSearchInput.addEventListener('input', () => {
                    renderTicketsTable();
                });
            }
            
            // ========== SUPPORT TICKETS TABLE SORTING EVENT LISTENERS ==========
            // Ticket ID sort
            var studentTicketIdSort = document.getElementById('student-ticket-id-sort');
            if (studentTicketIdSort) {
                studentTicketIdSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudentTickets('student-ticket-id');
                });
            }
            
            // Ticket Student Name sort
            var studentTicketNameSort = document.getElementById('student-ticket-name-sort');
            if (studentTicketNameSort) {
                studentTicketNameSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudentTickets('student-ticket-name');
                });
            }
            
            // Ticket Issue Type sort
            var studentTicketIssueTypeSort = document.getElementById('student-ticket-issue-type-sort');
            if (studentTicketIssueTypeSort) {
                studentTicketIssueTypeSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudentTickets('student-ticket-issue-type');
                });
            }
            
            // Ticket Status sort
            var studentTicketStatusSort = document.getElementById('student-ticket-status-sort');
            if (studentTicketStatusSort) {
                studentTicketStatusSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudentTickets('student-ticket-status');
                });
            }
            
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
                            try { window.location.replace(@json(url('/'))); } catch(_) { window.location.href = '/'; }
                        });
                    } catch (err) {
                        try { window.location.replace(@json(url('/'))); } catch(_) { window.location.href = '/'; }
                    }
                }, { passive: true });
            } catch (_) {}
        }
        // execute attachLogoutHandler once after DOM ready
        try { attachLogoutHandler(); } catch (_) {}
        function renderStatusBadge(status, record = null) {
            let badgeHtml = '';
            let dateHtml = '';
            let countdownHtml = '';
            
            if (status === 'Approved') {
                badgeHtml = '<span class="scms-badge scms-badge--approved">Approved</span>';
                // Use approved_at timestamp and format with Philippine timezone
                const approvedTimestamp = record?.approved_at;
                if (approvedTimestamp) {
                    try {
                        const dateObj = getPhilippineDate(approvedTimestamp);
                        const formattedDate = `${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}-${dateObj.getFullYear()}`;
                        dateHtml = '<div class="text-xs mt-1" style="color: #6b7280; font-size: 0.75rem; line-height: 1rem;">' + formattedDate + '</div>';
                    } catch(e) {
                        console.error('Error formatting approved date:', e);
                    }
                }
            } else if (status === 'Verified') {
                badgeHtml = '<span class="scms-badge scms-badge--verified">Verified</span>';
                // Use verified_at timestamp and format with Philippine timezone
                const verifiedTimestamp = record?.verified_at;
                if (verifiedTimestamp) {
                    try {
                        const dateObj = getPhilippineDate(verifiedTimestamp);
                        const formattedDate = `${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}-${dateObj.getFullYear()}`;
                        dateHtml = '<div class="text-xs mt-1" style="color: #6b7280; font-size: 0.75rem; line-height: 1rem;">' + formattedDate + '</div>';
                    } catch(e) {
                        console.error('Error formatting verified date:', e);
                    }
                }
            } else if (status === 'Rejected') {
                badgeHtml = '<span class="scms-badge scms-badge--rejected">Rejected</span>';
                // Use rejected_at timestamp and format with Philippine timezone
                const rejectedTimestamp = record?.rejected_at;
                console.log('Rejected record:', { rejected_at: rejectedTimestamp, record });
                if (rejectedTimestamp) {
                    try {
                        const dateObj = getPhilippineDate(rejectedTimestamp);
                        const formattedDate = `${String(dateObj.getMonth() + 1).padStart(2, '0')}-${String(dateObj.getDate()).padStart(2, '0')}-${dateObj.getFullYear()}`;
                        dateHtml = '<div class="text-xs mt-1" style="color: #6b7280; font-size: 0.75rem; line-height: 1rem;">' + formattedDate + '</div>';
                        console.log('Rejected date formatted:', formattedDate);
                        
                        // Add deletion countdown
                        try {
                            const rej = getPhilippineDate(String(rejectedTimestamp));
                            console.log('Rejection date object:', rej);
                            if (!isNaN(rej.getTime())) {
                                // Countdown starts from exact rejection time + 7 days
                                const deleteAt = new Date(rej.getTime() + 7 * 24 * 60 * 60 * 1000);
                                const now = getPhilippineDate();
                                const diff = deleteAt - now;
                                console.log('Countdown calculation:', { deleteAt, now, diff, diffInDays: diff / (1000 * 60 * 60 * 24) });
                                
                                if (diff > 0) {
                                    // Calculate exact time remaining from the rejection timestamp
                                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    countdownHtml = `<div class="text-xs mt-1" style="color: #9ca3af; font-size: 0.75rem; line-height: 1rem;">Time left before deletion: ${days} day${days!==1?'s':''} ${hours} hour${hours!==1?'s':''}</div>`;
                                    console.log('Countdown HTML created:', countdownHtml);
                                } else {
                                    countdownHtml = '<div class="text-xs text-red-500 mt-1">Deleting soon</div>';
                                    console.log('Deletion imminent');
                                }
                            } else {
                                console.warn('Invalid rejection date');
                            }
                        } catch(e) {
                            console.error('Error calculating deletion countdown:', e);
                        }
                    } catch(e) {
                        console.error('Error formatting rejected date:', e);
                    }
                } else {
                    console.warn('No rejected_at timestamp found for Rejected record');
                }
            } else {
                badgeHtml = '<span class="scms-badge scms-badge--pending">Pending</span>';
            }
            
            return badgeHtml + dateHtml + countdownHtml;
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
                const d = getPhilippineDate(iso);
                return isNaN(d) ? null : d;
            } catch (_) { return null; }
        }

        function safeYear(val) {
            const d = safeDate(val);
            return d ? d.getFullYear() : null;
        }

        // ==================== PDF Export Functions ====================
        
        // Global variable to store logo as Base64
        let logoBase64 = null;

        // Function to convert image to Base64
        function imageToBase64(url, callback) {
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            img.onload = function() {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                const dataURL = canvas.toDataURL('image/png');
                callback(dataURL);
            };
            img.onerror = function() {
                console.error('Failed to load image:', url);
                callback(null);
            };
            img.src = url;
        }

        // Load logo on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Try to load PLV logo (optional - PDF will work without it)
            imageToBase64('{{ asset("C:\Users\janar\Herd\scms\storage\app\public\VITS_logo.png") }}', function(base64) {
                if (base64) {
                    logoBase64 = base64;
                    console.log('PLV logo loaded for PDF export');
                } else {
                    console.warn('PLV logo not found, PDF will generate without logo');
                }
            });
            
            // Verify functions are available
            console.log('PDF Export functions loaded:', {
                openExportModal: typeof openExportModal,
                exportToPDF: typeof exportToPDF,
                populateSchoolYearDropdown: typeof populateSchoolYearDropdown
            });
            
            // Test that functions are globally accessible
            if (typeof window.openExportModal === 'function') {
                console.log('✅ openExportModal is globally accessible');
                
                // Test modal element exists
                const modal = document.getElementById('export_options_modal');
                if (modal) {
                    console.log('✅ Export modal element found in DOM');
                } else {
                    console.error('❌ Export modal element NOT found in DOM');
                }
                
                // Attach event listener to Export PDF button
                const exportBtn = document.getElementById('export-pdf-btn');
                if (exportBtn) {
                    console.log('✅ Export PDF button found, attaching click listener...');
                    exportBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('🔘 Export PDF button clicked!');
                        window.openExportModal();
                    });
                } else {
                    console.error('❌ Export PDF button NOT found');
                }
            } else {
                console.error('❌ openExportModal is NOT globally accessible');
            }
        });

        // Helper: Format database date to readable format using Philippine timezone
        window.formatDbDate = function(dateStr) {
            if (!dateStr) return '';
            const date = getPhilippineDate(dateStr);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', timeZone: 'Asia/Manila' });
        };

        // Helper: Check if a date falls within a school year using Philippine timezone
        window.isDateInSchoolYear = function(dateStr, schoolYear) {
            if (!dateStr || !schoolYear) return false;
            const date = getPhilippineDate(dateStr);
            const [startYear, endYear] = schoolYear.split('-').map(Number);
            const syStart = new Date(startYear, 7, 1); // August 1
            const syEnd = new Date(endYear, 6, 31);    // July 31
            return date >= syStart && date <= syEnd;
        };

        // Populate School Year dropdown with unique years from approved records
        window.populateSchoolYearDropdown = function() {
            console.log('=== populateSchoolYearDropdown called ===');
            console.log('Total records in allRecords:', allRecords.length);
            console.log('typeof allRecords:', typeof allRecords);
            console.log('Is array?:', Array.isArray(allRecords));
            if (allRecords.length > 0) {
                console.log('First 3 records:', allRecords.slice(0, 3));
                console.log('Sample statuses:', allRecords.slice(0, 5).map(r => ({ status: r.status, date: r.date })));
            }
            
            const dropdown = document.getElementById('export-school-year');
            dropdown.innerHTML = ''; // Clear existing options

            // Get all approved records with dates (only Approved status, not Verified)
            const approvedRecords = allRecords.filter(r => 
                r.status === 'Approved' && r.date
            );
            
            console.log('Approved records with dates:', approvedRecords.length);
            
            if (approvedRecords.length === 0) {
                dropdown.innerHTML = '<option disabled selected>No approved records</option>';
                return;
            }

            // Extract years and create school year ranges
            const years = new Set();
            approvedRecords.forEach(record => {
                const date = getPhilippineDate(record.date);
                const year = date.getFullYear();
                const month = date.getMonth(); // 0-11
                // If Aug-Dec, belongs to current-next school year
                // If Jan-Jul, belongs to previous-current school year
                const schoolYearStart = month >= 7 ? year : year - 1;
                years.add(schoolYearStart);
            });

            // Sort years and create school year options
            const sortedYears = Array.from(years).sort((a, b) => b - a);
            sortedYears.forEach((year, index) => {
                const option = document.createElement('option');
                option.value = `${year}-${year + 1}`;
                option.textContent = `S.Y. ${year}-${year + 1}`;
                if (index === 0) option.selected = true; // Select most recent by default
                dropdown.appendChild(option);
            });
        };

        // Open Export Modal
        window.openExportModal = function() {
            console.log('=== openExportModal called ===');
            
            try {
                // Check if modal exists
                const modal = document.getElementById('export_options_modal');
                console.log('Modal element:', modal);
                
                if (!modal) {
                    console.error('Modal element not found!');
                    alert('Modal not found! Please refresh the page.');
                    return;
                }
                
                // Populate dropdown
                console.log('Populating school year dropdown...');
                populateSchoolYearDropdown();
                
                // Show modal using DaisyUI method
                console.log('Showing modal...');
                modal.showModal();
                console.log('Modal should be visible now');
                
            } catch (error) {
                console.error('Error opening modal:', error);
                alert('Error opening modal: ' + error.message);
            }
        };

        // Export to PDF
        window.exportToPDF = function() {
            console.log('exportToPDF called');
            console.log('allRecords:', allRecords);
            
            const schoolYear = document.getElementById('export-school-year').value;
            const yearLevel = document.getElementById('export-year-level').value;

            console.log('Selected school year:', schoolYear);
            console.log('Selected year level:', yearLevel);

            if (!schoolYear || schoolYear === 'No approved records') {
                Toastify({
                    text: "No approved records available for export",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#EF4444",
                        borderRadius: "8px",
                        padding: "12px 20px"
                    }
                }).showToast();
                return;
            }

            // Filter approved records by selected school year (only Approved status, not Verified)
            const filteredRecords = allRecords.filter(record => 
                record.status === 'Approved' && 
                isDateInSchoolYear(record.date, schoolYear)
            );

            if (filteredRecords.length === 0) {
                Toastify({
                    text: `No approved records found for S.Y. ${schoolYear}`,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#F59E0B",
                        borderRadius: "8px",
                        padding: "12px 20px"
                    }
                }).showToast();
                return;
            }

            // Close modal
            document.getElementById('export_options_modal').close();

            // Show loading toast
            Toastify({
                text: "Generating PDF...",
                duration: 2000,
                gravity: "top",
                position: "right",
                style: {
                    background: "#3B82F6",
                    borderRadius: "8px",
                    padding: "12px 20px"
                }
            }).showToast();

            // Generate PDF
            setTimeout(() => {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF();
                    
                    // IMPORTANT: Set opacity to full before anything else
                    doc.setGState(new doc.GState({ opacity: 1.0 }));
                    
                    // Set default drawing color to black for all elements
                    doc.setDrawColor(0, 0, 0);
                    doc.setTextColor(0, 0, 0);
                    doc.setFont('helvetica', 'normal');

                    // Add VITS logo on the left
                    const vitsLogoPath = '/storage/VITS_logo.png';
                    try {
                        // Left logo (VITS)
                        doc.addImage(vitsLogoPath, 'PNG', 14, 10, 20, 20);
                    } catch (err) {
                        console.warn('Could not load VITS logo:', err);
                    }

                    // Add PLV logo on the right (if available)
                    if (logoBase64) {
                        doc.addImage(logoBase64, 'PNG', 175, 10, 20, 20);
                    }

                    // Header text - explicitly set black
                    doc.setTextColor(0, 0, 0);
                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Pamantasan ng Lungsod ng Valenzuela', 105, 15, { align: 'center' });
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'normal');
                    doc.text('Tongco Street, Maysan, Valenzuela City', 105, 20, { align: 'center' });
                    doc.text('Tel. No.: 292-3247', 105, 25, { align: 'center' });

                    // Document title
                    doc.setFontSize(14);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Social Contract Record', 105, 35, { align: 'center' });

                    // Student info
                    doc.setFontSize(10);
                    doc.setFont('helvetica', 'normal');
                    const studentName = '{{ Auth::user()->name ?? "Student Name" }}';
                    const studentId = '{{ Auth::user()->student_id ?? "N/A" }}';
                    doc.text(`Student Name: ${studentName}`, 14, 45);
                    doc.text(`Student ID: ${studentId}`, 14, 50);
                    doc.text(`Year Level: ${yearLevel}`, 14, 55);
                    doc.text(`School Year: ${schoolYear}`, 14, 60);

                    // Debug: Check venue data
                    console.log('First record venue:', filteredRecords[0]?.venue);
                    console.log('First record full:', filteredRecords[0]);

                    // Prepare table data matching the format from the image
                    const tableData = filteredRecords.map((record) => {
                        const venue = record.venue || record.location || '';
                        const supervisorName = record.supervisor_name || '';
                        console.log('Record venue:', venue, 'supervisor:', supervisorName, 'from record:', record);
                        return [
                            formatDbDate(record.date),
                            venue,
                            (record.organization ? record.organization + ' - ' : '') + (record.event_name || ''),
                            supervisorName, // Supervisor name from the record
                            record.hours_rendered || 0
                        ];
                    });

                    // Calculate total hours
                    const totalHours = filteredRecords.reduce((sum, r) => sum + (parseFloat(r.hours_rendered) || 0), 0);

                    // Add table with the exact columns from the image
                    // RESET EVERYTHING before table
                    doc.setGState(new doc.GState({ opacity: 1.0 }));
                    doc.setTextColor(0, 0, 0);
                    doc.setDrawColor(0, 0, 0);
                    doc.setFont('helvetica', 'normal');
                    
                    doc.autoTable({
                        startY: 70,
                        head: [['Date', 'Venue', 'Name of Organizing Committee\n/Activity', 'Printed Name and Signature of\nSupervisor', 'Hours\nRendered']],
                        body: tableData,
                        foot: [['', '', '', 'Total Hours:', totalHours.toFixed(2)]],
                        theme: 'grid',
                        styles: { 
                            fontSize: 9, 
                            cellPadding: 3,
                            valign: 'middle',
                            halign: 'center',
                            textColor: 0,           // Try single value black
                            lineColor: 0,           // Try single value black
                            lineWidth: 0.1,
                            fontStyle: 'normal'
                        },
                        headStyles: { 
                            fillColor: [109, 40, 217],  // Purple header
                            textColor: 255,             // White text (single value)
                            fontStyle: 'bold',
                            halign: 'center',
                            valign: 'middle',
                            lineColor: 0,
                            lineWidth: 0.1
                        },
                        bodyStyles: {
                            fillColor: 255,         // White background
                            textColor: 0,           // BLACK text (single value)
                            lineColor: 0,           // BLACK borders
                            lineWidth: 0.1,
                            fontStyle: 'normal'
                        },
                        footStyles: { 
                            fillColor: 255,         // White background
                            textColor: 0,           // BLACK text (single value)
                            lineColor: 0,           // BLACK borders
                            fontStyle: 'bold',
                            halign: 'right',
                            lineWidth: 0.1
                        },
                        columnStyles: {
                            0: { cellWidth: 25, halign: 'center' },  // Date
                            1: { cellWidth: 30, halign: 'left' },    // Venue
                            2: { cellWidth: 60, halign: 'left' },    // Activity
                            3: { cellWidth: 50, halign: 'center' },  // Supervisor
                            4: { cellWidth: 20, halign: 'center' }   // Hours Rendered
                        }
                    });

                    // Signature section
                    const finalY = doc.lastAutoTable.finalY + 20;
                    doc.setTextColor(0, 0, 0);  // Ensure black text
                    doc.setFontSize(10);
                    doc.text('Prepared by:', 14, finalY);
                    doc.text('_______________________', 14, finalY + 15);
                    doc.setFontSize(9);
                    doc.text(studentName, 14, finalY + 20);
                    doc.text('Student', 14, finalY + 25);

                    doc.setFontSize(10);
                    doc.text('Verified by:', 120, finalY);
                    doc.text('_______________________', 120, finalY + 15);
                    doc.setFontSize(9);
                    doc.text(SUPER_ADMIN_NAME, 120, finalY + 20);
                    doc.text('Super Administrator', 120, finalY + 25);

                    // Footer
                    doc.setFontSize(8);
                    doc.setTextColor(128, 128, 128);  // Gray for footer
                    const phDate = getPhilippineDate();
                    doc.text(`Generated on: ${phDate.toLocaleString('en-US', { timeZone: 'Asia/Manila' })}`, 105, 285, { align: 'center' });

                    // Add watermark LAST (on top of content with transparency)
                    const watermarkPath = '/storage/vits_white.png';  // Fixed: vits_white.png not vits_whites.png
                    try {
                        doc.setGState(new doc.GState({ opacity: 0.1 })); // 10% opacity watermark
                        doc.addImage(watermarkPath, 'PNG', 30, 85, 150, 150); // Centered, large
                        doc.setGState(new doc.GState({ opacity: 1.0 })); // Reset
                        console.log('Watermark added successfully');
                    } catch (err) {
                        console.error('Could not load watermark:', err);
                    }

                    // Save PDF
                    const fileName = `Social_Contract_${studentId}_${schoolYear}.pdf`;
                    doc.save(fileName);

                    // Success toast
                    Toastify({
                        text: `PDF exported successfully! (${filteredRecords.length} records)`,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#10B981",
                            borderRadius: "8px",
                            padding: "12px 20px"
                        }
                    }).showToast();

                } catch (error) {
                    console.error('PDF generation error:', error);
                    Toastify({
                        text: "Failed to generate PDF. Please try again.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#EF4444",
                            borderRadius: "8px",
                            padding: "12px 20px"
                        }
                    }).showToast();
                }
            }, 500);
        };

        // ==================== End PDF Export Functions ====================
    </script>

    <!-- Session Keeper: Keeps session alive and CSRF token fresh -->
    <script src="{{ asset('js/session-keeper.js') }}"></script>
    <script>
        // Initialize Session Keeper for Student Dashboard
        if (window.SessionKeeper) {
            SessionKeeper.init({
                debug: false, // Set to true for debugging
                autoRefreshEnabled: true,
                dataRefreshInterval: 30 * 1000, // Refresh data every 30 seconds
                onDataRefresh: function() {
                    // Refresh records data automatically
                    if (typeof fetchRecords === 'function') {
                        console.log('[Student Dashboard] Auto-refreshing records...');
                        fetchRecords();
                    }
                }
            });
        }
    </script>

    <!-- Additional Keep-Alive Scripts (Fallback & Simplicity) -->
    <script>
        // Simple CSRF refresh every 30 minutes (fallback)
        async function refreshCsrf() {
            try {
                const res = await fetch('/refresh-csrf', { credentials: 'same-origin' });
                const data = await res.json();
                document.querySelector('meta[name="csrf-token"]').content = data.token;
                
                if (window.axios) {
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = data.token;
                }
            } catch (e) {
                console.warn('[Keep-Alive] CSRF refresh failed', e);
            }
        }

        // Simple keep-alive ping every 20 minutes (fallback)
        function keepAlive() {
            fetch('/keep-alive', { credentials: 'same-origin' }).catch(() => {});
        }

        // Run both periodically (works alongside SessionKeeper)
        setInterval(refreshCsrf, 30 * 60 * 1000); // 30 minutes
        setInterval(keepAlive, 20 * 60 * 1000);   // 20 minutes
    </script>
</body>
</html>