<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<title>Super Admin - Student Contract Management</title>
<script>
    // CRITICAL: Apply saved theme IMMEDIATELY before any rendering
    (function() {
        try {
            var savedTheme = localStorage.getItem('scms_superadmin_theme');
            // Default to light if no saved preference
            if (savedTheme !== 'dark' && savedTheme !== 'light') {
                savedTheme = 'light';
                localStorage.setItem('scms_superadmin_theme', 'light');
            }
            document.documentElement.setAttribute('data-theme', savedTheme);
        } catch(e) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    })();
</script>
<script>
tailwind=typeof tailwind==='object'?tailwind:{};tailwind.config={theme:{extend:{colors:{'background-light':'#EDF1FA','primary-purple':'#6D28D9','primary-purple-hover':'#5B21B6','text-header':'#2B3674','text-muted':'#707EAE','badge-pending-text':'#E29C44','badge-pending-bg':'#FAEAD0','badge-verified-text':'#399552','badge-verified-bg':'#CCEED6','badge-rejected-text':'#CC525D','badge-rejected-bg':'#FFD7DB','success-green':'#4CAF50','success-green-hover':'#45a049','danger-red':'#CC525D','danger-red-hover':'#b33e46'},fontFamily:{sans:['Inter','sans-serif']}}}};
</script>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css">
<?php
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
?>
<link rel="icon" href="<?php echo e($iconUrl); ?>" sizes="any">
<link rel="icon" href="<?php echo e($iconUrl); ?>" type="image/png">
<link rel="shortcut icon" href="<?php echo e($iconUrl); ?>" type="image/png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
body{font-family:'Inter',sans-serif}
.active-nav{background-color:#6D28D9;color:#fff;border-radius:0.5rem}
.flex-1-dynamic{flex:1 1 auto;min-height:0}
.content-area-auto{height:auto;max-height:100%}
.btn-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
.btn-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
.btn-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
.btn-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
.btn-primary-purple svg{stroke:#fff!important}
.btn.bg-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
.btn.bg-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
.btn.bg-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
.btn.bg-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
.btn.bg-primary-purple svg{stroke:#fff!important}
.btn.bg-success-green,.btn-success-green{background-color:#4CAF50!important;color:#fff!important;border-color:transparent!important}
.btn.bg-success-green:hover,.btn-success-green:hover{background-color:#45a049!important;color:#fff!important}
.btn.bg-success-green:active,.btn-success-green:active{background-color:#3d9341!important;color:#fff!important}
.btn.bg-success-green:focus,.btn-success-green:focus{outline:none!important;box-shadow:0 0 0 2px rgba(34,197,94,0.35)!important}
.btn.bg-danger-red,.btn-danger-red{background-color:#CC525D!important;color:#fff!important;border-color:transparent!important}
.btn.bg-danger-red:hover,.btn-danger-red:hover{background-color:#b33e46!important;color:#fff!important}
.btn.bg-danger-red:active,.btn-danger-red:active{background-color:#9c2936!important;color:#fff!important}
.btn.bg-danger-red:focus,.btn-danger-red:focus{outline:none!important;box-shadow:0 0 0 2px rgba(204,82,93,0.35)!important}
.btn-action{font-weight:600;border-width:1.5px;border-radius:6px;padding:4px 12px;font-size:0.75rem;height:auto;min-height:auto;background-color:transparent;transition:all 0.2s ease-in-out}
.btn-action-verify{border-color:#13AAAA;color:#13AAAA}
.btn-action-verify:hover{background-color:#13AAAA;color:white}
.btn-action-approve{border-color:#4CAF50;color:#4CAF50}
.btn-action-approve:hover{background-color:#4CAF50;color:white}
.btn-action-reject{border-color:#CC525D;color:#CC525D}
.btn-action-reject:hover{background-color:#CC525D;color:white}
.bg-gradient-primary-purple{background-image:linear-gradient(to bottom,#bbacffff,#6D28D9)}
.bg-gradient-pending{background-image:linear-gradient(to bottom,#FFF4DE,#FFE0A2)}
.bg-gradient-accepted{background-image:linear-gradient(to bottom,#DCFCE7,#81FFAC)}
.bg-gradient-rejected{background-image:linear-gradient(to bottom,#FFE2E5,#FFB7BE)}
.bg-gradient-primary-purple h2,.bg-gradient-primary-purple p{word-wrap:break-word;overflow-wrap:break-word;-ms-word-wrap:break-word}
.bg-gradient-primary-purple{-ms-flex-wrap:nowrap;flex-wrap:nowrap}
.custom-tab-wrapper{background-color:white;border-radius:0.5rem;box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px -1px rgba(0,0,0,0.1);padding:0.5rem}
.custom-tab{font-weight:600;color:#707EAE;padding:0.5rem 1.25rem;border-bottom:3px solid transparent;transition:all 0.2s ease-in-out;cursor:pointer}
.custom-tab:hover{color:#6D28D9}
.custom-tab-active{color:#6D28D9!important;border-bottom-color:#6D28D9!important}
.details-input{width:100%;padding:0.75rem 1rem;border:1px solid #D1D5DB;border-radius:0.5rem;background-color:#F9FAFB;font-size:0.875rem;color:#111827}
.details-label{font-weight:600;color:#374151;margin-bottom:0.5rem;display:block}
.status-badge{display:inline-flex;align-items:center;padding:0.5rem 1.25rem;border-radius:0.5rem;font-weight:600;font-size:0.875rem}
.status-badge.verified{background-color:#D1FAE5;color:#065F46}
.status-badge.approved{background-color:#D1FAE5;color:#065F46}
.status-badge.rejected{background-color:#FFD1D3;color:#CC525D}
.status-badge.for-approval{background-color:#FFF4DE;color:#E29C44}
.scms-badge{display:inline-flex;align-items:center;justify-content:center;font-weight:600;border-radius:9999px;padding:0.25rem 0.5rem;font-size:0.75rem;line-height:1;border:0!important}
.scms-badge--pending{background-color:#FAEAD0!important;color:#E29C44!important}
.scms-badge--verified{background-color:#B2F5EA!important;color:#0D9488!important}
.scms-badge--approved{background-color:#C8E6C9!important;color:#2E7D32!important}
.scms-badge--rejected{background-color:#FFD7DB!important;color:#CC525D!important}
.scms-badge--for-approval{background-color:#FFF4DE!important;color:#E29C44!important}
.bg-custom{background-color:#EDF1FA;background-image:url('<?php echo e(asset("vits_bg_white.png")); ?>');background-repeat:no-repeat;background-size:cover;background-position:center;background-attachment:fixed}
#toast-root{position:fixed;right:1rem;bottom:1rem;z-index:2000;display:flex;flex-direction:column;gap:0.75rem;pointer-events:none}
#toast-root .alert{pointer-events:auto}
.scms-toast{position:relative;display:inline-flex;align-items:center;gap:0.5rem;padding:0.625rem 0.875rem;border-radius:9999px;color:#fff;box-shadow:0 10px 24px rgba(0,0,0,0.18),0 2px 6px rgba(0,0,0,0.08);border:1px solid rgba(255,255,255,0.08);max-width:520px}
.scms-toast--success{background:linear-gradient(90deg,#16A34A,#22C55E)}
.scms-toast--error{background:linear-gradient(90deg,#EF4444,#DC2626)}
.scms-toast--info{background:linear-gradient(90deg,#6D28D9,#7C3AED)}
.scms-toast__msg{font-weight:600;font-size:0.925rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.scms-toast__close{margin-left:0.25rem;display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:9999px;background:rgba(255,255,255,0.12);color:#fff;border:0;cursor:pointer;transition:background 0.18s ease,transform 0.12s ease}
.scms-toast__close:hover{background:rgba(255,255,255,0.22);transform:translateY(-1px)}
.scms-toast__progress{position:absolute;left:6px;right:6px;bottom:4px;height:3px;border-radius:9999px;background:rgba(255,255,255,0.55);transform-origin:left center}
@keyframes scms-toast-progress{from{transform:scaleX(1)}to{transform:scaleX(0)}}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
[data-theme="dark"] body{color:#fff}
[data-theme="dark"] .text-black,[data-theme="dark"] .text-gray-900,[data-theme="dark"] .text-gray-800,[data-theme="dark"] .text-gray-700,[data-theme="dark"] .text-gray-600,[data-theme="dark"] .text-gray-500,[data-theme="dark"] .text-text-header,[data-theme="dark"] .text-text-muted,[data-theme="dark"] h1,[data-theme="dark"] h2,[data-theme="dark"] h3,[data-theme="dark"] h4,[data-theme="dark"] h5,[data-theme="dark"] h6,[data-theme="dark"] p,[data-theme="dark"] span,[data-theme="dark"] label,[data-theme="dark"] td,[data-theme="dark"] th,[data-theme="dark"] a{color:#fff!important}
[data-theme="dark"] .scms-badge--pending{background-color:#ff9d26ff!important;color:#ffffffff!important}
[data-theme="dark"] .scms-badge--verified{background-color:#14B8A6!important;color:#ffffffff!important}
[data-theme="dark"] .scms-badge--approved{background-color:#4CAF50!important;color:#ffffffff!important}
[data-theme="dark"] .scms-badge--rejected{background-color:#b8000fff!important;color:#ffffffff!important}
[data-theme="dark"] .scms-badge--for-approval{background-color:#ff9d26ff!important;color:#ffffffff!important}
[data-theme="dark"] .bg-gradient-pending{background-image:linear-gradient(to top,#6D28D9,#FFE0A2)}
[data-theme="dark"] .bg-gradient-accepted{background-image:linear-gradient(to top,#6D28D9,#81FFAC)}
[data-theme="dark"] .bg-gradient-rejected{background-image:linear-gradient(to top,#6D28D9,#FFB7BE)}
[data-theme="dark"] .bg-custom{background-color:#0b0f19;background-image:url('<?php echo e(asset("storage/vits_bg_black.png")); ?>')}
[data-theme="dark"] .table thead,[data-theme="dark"] .table thead tr,[data-theme="dark"] .table thead th{background-color:#374151!important}
[data-theme="dark"] .table th,[data-theme="dark"] .table td{border-color:#374151!important}
[data-theme="dark"] .bg-white{background-color:#1f2937!important}
[data-theme="dark"] .bg-gray-100{background-color:#374151!important}
[data-theme="dark"] .border-gray-200{border-color:#374151!important}
[data-theme="dark"] .bg-base-100{background-color:#111827!important}
[data-theme="dark"] .scms-toast{border-color:rgba(255,255,255,0.14);box-shadow:0 10px 24px rgba(0,0,0,0.35),0 2px 6px rgba(0,0,0,0.2)}
[data-theme="dark"] .custom-tab-wrapper{background-color:#1f2937}
[data-theme="dark"] .details-input{background-color:#374151;border-color:#4b5563;color:#fff}
[data-theme="dark"] .btn-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
[data-theme="dark"] .btn-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
[data-theme="dark"] .btn-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
[data-theme="dark"] .btn-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
[data-theme="dark"] .btn-primary-purple svg{stroke:#fff!important}
[data-theme="dark"] .btn-primary-purple span{color:#fff!important}
[data-theme="dark"] .btn.bg-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
[data-theme="dark"] .btn.bg-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
[data-theme="dark"] .btn.bg-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
[data-theme="dark"] .btn.bg-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
[data-theme="dark"] .btn.bg-primary-purple svg{stroke:#fff!important}
[data-theme="dark"] .btn.bg-primary-purple span{color:#fff!important}
[data-theme="dark"] .btn.bg-success-green,[data-theme="dark"] .btn-success-green{background-color:#4CAF50!important;color:#fff!important;border-color:transparent!important}
[data-theme="dark"] .btn.bg-success-green:hover,[data-theme="dark"] .btn-success-green:hover{background-color:#45a049!important;color:#fff!important}
[data-theme="dark"] .btn.bg-success-green:active,[data-theme="dark"] .btn-success-green:active{background-color:#3d9341!important;color:#fff!important}
[data-theme="dark"] .btn.bg-success-green:focus,[data-theme="dark"] .btn-success-green:focus{outline:none!important;box-shadow:0 0 0 2px rgba(34,197,94,0.45)!important}
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
#table-header-row{transition:none!important}
#table-header-row th{transition:none!important}
.table thead tr{height:60px!important;max-height:60px!important}
.table thead th{height:60px!important;max-height:60px!important;vertical-align:middle!important}
/* Support tickets table hover effect - match Students Information table */
#ticket-table-body tr:hover{background-color:#f3f4f6!important}
[data-theme="dark"] #ticket-table-body tr:hover{background-color:#191E24!important}
/* Students Information table hover effect */
.table tbody#students-table-body tr:hover,
.table tbody#students-table-body tr.hover:hover{background-color:#f3f4f6!important}
[data-theme="dark"] .table tbody#students-table-body tr:hover,
[data-theme="dark"] .table tbody#students-table-body tr.hover:hover{background-color:#191E24!important}
/* Fix modal centering and backdrop */
.modal{display:flex!important;align-items:center!important;justify-content:center!important;padding:0!important}
.modal-box{margin:0!important;position:relative!important;z-index:1!important}
.modal-backdrop{background-color:rgba(0,0,0,0.5)!important;position:fixed!important;top:0!important;left:0!important;right:0!important;bottom:0!important;z-index:0!important}
.modal::backdrop{background-color:rgba(0,0,0,0.5)!important}
/* Support ticket status badges - dark theme */
[data-theme="dark"] .badge.bg-yellow-100{background-color:#ff9d26!important;color:#fff!important}
[data-theme="dark"] .badge.bg-green-100{background-color:#4CAF50!important;color:#fff!important}
[data-theme="dark"] .badge.bg-gray-100{background-color:#6b7280!important;color:#fff!important}
[data-theme="dark"] .badge.text-yellow-800{color:#fff!important}
[data-theme="dark"] .badge.text-green-800{color:#fff!important}
[data-theme="dark"] .badge.text-gray-800{color:#fff!important}
/* Activity calendar legend - light theme */
.activity-legend-0{background-color:#E5E7EB}
.activity-legend-1{background-color:#E5D4FF}
.activity-legend-2{background-color:#C9A9FF}
.activity-legend-3{background-color:#A475FF}
.activity-legend-4{background-color:#6D28D9}
/* Activity calendar legend - dark theme */
[data-theme="dark"] .activity-legend-0{background-color:#2c354aff}
[data-theme="dark"] .activity-legend-1{background-color:#411b7a}
[data-theme="dark"] .activity-legend-2{background-color:#5A3590}
[data-theme="dark"] .activity-legend-3{background-color:#804ED6}
[data-theme="dark"] .activity-legend-4{background-color:#A770FF}

/* Sidebar header styles */
#sidebar.collapsed #sidebar-header { justify-content: center; padding-bottom: 0.75rem; }
#sidebar.collapsed #sidebar-title-text { display: none; }
#sidebar.collapsed #sidebar-logo-light,
#sidebar.collapsed #sidebar-logo-dark { margin: 0 auto; }

/* When sidebar is collapsed on desktop, show only the sun/moon icons
    and hide the checkbox input and label text to keep the UI compact. */
#sidebar.collapsed #theme-toggle { display: none !important; }
#sidebar.collapsed #theme-label { display: none !important; }
#sidebar.collapsed #theme-toggle-container { justify-content: center; padding-left: 0.5rem; padding-right: 0.5rem; }
#sidebar.collapsed #theme-icon-sun,
#sidebar.collapsed #theme-icon-moon { margin: 0 auto; }

/* Sidebar header - Light Mode */
html:not([data-theme="dark"]) #sidebar-logo-light { display: block; }
html:not([data-theme="dark"]) #sidebar-logo-dark { display: none; }
html:not([data-theme="dark"]) .sidebar-title-main,
html:not([data-theme="dark"]) .sidebar-title-sub { color: #6D28D9 !important; }

/* Sidebar header - Dark Mode */
[data-theme="dark"] #sidebar-logo-light { display: none !important; }
[data-theme="dark"] #sidebar-logo-dark { display: block !important; }
[data-theme="dark"] .sidebar-title-main,
[data-theme="dark"] .sidebar-title-sub { color: #ffffff !important; }
[data-theme="dark"] #sidebar-header { border-color: #374151 !important; }

/* Theme toggle styles */
html:not([data-theme="dark"]) #theme-icon-moon { color: #374151 !important; stroke: #374151 !important; }
html:not([data-theme="dark"]) #theme-icon-sun { color: #374151 !important; stroke: #374151 !important; }
html:not([data-theme="dark"]) #theme-toggle-container { color: #374151 !important; }
html:not([data-theme="dark"]) #theme-label { color: #374151 !important; }
html:not([data-theme="dark"]) .toggle-primary { --tglbg: #6D28D9; background-color: #d1d5db; border-color: #9ca3af; }
html:not([data-theme="dark"]) .toggle-primary:checked { background-color: #6D28D9; border-color: #6D28D9; }

/* Prevent heavy dark tap/active highlight on long-press in light mode for notifications/dropdowns */
html:not([data-theme="dark"]) .dropdown-content li,
html:not([data-theme="dark"]) .dropdown-content li a,
html:not([data-theme="dark"]) .dropdown-content .notification-message,
html:not([data-theme="dark"]) .dropdown-content .notification-title {
    -webkit-tap-highlight-color: transparent !important;
    tap-highlight-color: transparent !important;
}
html:not([data-theme="dark"]) .dropdown-content li:active,
html:not([data-theme="dark"]) .dropdown-content li a:active {
    background-color: #f3f4f6 !important;
    color: inherit !important;
    box-shadow: none !important;
}

/* Mobile Header Bar */
#mobile-header {
    display: none;
}

/* Mobile hamburger button - now inside header */
#mobile-menu-btn {
    display: flex;
    align-items: center;
    justify-content: center;
}
#mobile-menu-btn svg {
    width: 1.5rem;
    height: 1.5rem;
}

/* Mobile Header - Dark Mode */
[data-theme="dark"] #mobile-header { background-color: #1f2937 !important; border-color: #374151 !important; }
[data-theme="dark"] #mobile-page-title { color: #a78bfa !important; }

/* Sidebar text colors for light mode */
html:not([data-theme="dark"]) #sidebar { background-color: #ffffff; }
html:not([data-theme="dark"]) #sidebar h2 { color: #1f2937 !important; }
html:not([data-theme="dark"]) #sidebar p { color: #6b7280 !important; }
html:not([data-theme="dark"]) #sidebar .menu-text { color: #374151 !important; }
html:not([data-theme="dark"]) #sidebar .menu a { color: #374151 !important; }
html:not([data-theme="dark"]) #sidebar .menu a:hover { background-color: #f3f4f6; }
/* Hover state for non-active nav items: very light purple bg, icons/text use primary purple */
#sidebar .menu a:hover, #sidebar ul.menu a:hover, #sidebar .menu button:hover {
    background-color: #F5F3FF !important; /* almost white */
    color: #6D28D9 !important;
}
#sidebar .menu a:hover .menu-text, #sidebar ul.menu a:hover .menu-text, #sidebar .menu button:hover .menu-text {
    color: #6D28D9 !important;
}
#sidebar .menu a:hover svg, #sidebar ul.menu a:hover svg, #sidebar .menu button:hover svg {
    stroke: #6D28D9 !important;
}
/* Smooth color transition for hover */
#sidebar .menu a, #sidebar .menu button { transition: background-color .18s ease, color .18s ease; }
html:not([data-theme="dark"]) #sidebar .active-nav { background-color: #6D28D9; color: #fff !important; }
html:not([data-theme="dark"]) #sidebar .active-nav .menu-text { color: #fff !important; }
html:not([data-theme="dark"]) #sidebar svg { stroke: #374151; }
html:not([data-theme="dark"]) #sidebar .active-nav svg { stroke: #fff; }
#sidebar .active-nav, #sidebar a.active-nav {
    background-color: #6D28D9 !important;
    color: #ffffff !important;
    border-radius: 0.5rem !important;
}
#sidebar .active-nav .menu-text, #sidebar a.active-nav .menu-text {
    color: #ffffff !important;
}
#sidebar .active-nav svg, #sidebar a.active-nav svg {
    stroke: #ffffff !important;
}
/* Dark theme: ensure active nav still appears prominent */
[data-theme="dark"] #sidebar .active-nav, [data-theme="dark"] #sidebar a.active-nav {
    background-color: #6D28D9 !important;
    color: #ffffff !important;
}
[data-theme="dark"] #sidebar .active-nav .menu-text, [data-theme="dark"] #sidebar a.active-nav .menu-text {
    color: #ffffff !important;
}
/* Hover state for active nav: very light purple bg, switch text/icon to primary purple for contrast */
#sidebar .active-nav:hover, #sidebar a.active-nav:hover,
[data-theme="dark"] #sidebar .active-nav:hover, [data-theme="dark"] #sidebar a.active-nav:hover {
    background-color: #F5F3FF !important;
    color: #6D28D9 !important;
}
#sidebar .active-nav:hover .menu-text, #sidebar a.active-nav:hover .menu-text {
    color: #6D28D9 !important;
}
#sidebar .active-nav:hover svg, #sidebar a.active-nav:hover svg {
    stroke: #6D28D9 !important;
}

        /* Theme toggle hover - match menu hover treatment; remove border/outline on hover */
        #theme-toggle-container:hover { background-color: #F5F3FF !important; color: #6D28D9 !important; border-radius: 0.5rem; border: none !important; box-shadow: none !important; outline: none !important; }
        #theme-toggle-container:hover svg { stroke: #6D28D9 !important; color: #6D28D9 !important; }

        /* Dark theme: subtler pale hover and lighter icon tint; remove border/outline */
        [data-theme="dark"] #theme-toggle-container:hover { background-color: rgba(167,139,250,0.08) !important; color: #A78BFA !important; border: none !important; box-shadow: none !important; outline: none !important; }
        [data-theme="dark"] #theme-toggle-container:hover svg { stroke: #A78BFA !important; color: #A78BFA !important; }
html:not([data-theme="dark"]) #collapse-text { color: #374151 !important; }
html:not([data-theme="dark"]) #collapse-btn { color: #374151 !important; }
html:not([data-theme="dark"]) #collapse-btn svg { stroke: #374151 !important; }

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

/* Student view modal info boxes - Light mode */
.student-info-box {
    background-color: #f9fafb !important;
    color: #111827 !important;
}

/* Student view modal info boxes - Dark mode */
[data-theme="dark"] .student-info-box {
    background-color: #374151 !important;
    color: #ffffff !important;
}

/* Collapsible sidebar styles */
#sidebar {
    transition: width 0.3s ease, min-width 0.3s ease, max-width 0.3s ease;
}

#sidebar.collapsed {
    width: 80px !important;
    min-width: 80px !important;
    max-width: 80px !important;
}

#sidebar.collapsed .menu-text,
#sidebar.collapsed #admin-name,
#sidebar.collapsed #admin-role,
#sidebar.collapsed #collapse-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
    white-space: nowrap;
}

/* Avatar section when collapsed - match the image spacing */
#sidebar.collapsed #avatar-section {
    padding: 1rem 0.5rem;
}

#sidebar.collapsed #avatar-container {
    margin-bottom: 0;
}

#sidebar.collapsed #avatar-circle {
    width: 48px !important;
    height: 48px !important;
    ring-width: 2px;
}

#sidebar.collapsed #avatar-initials {
    font-size: 1.25rem;
}

/* Menu items when collapsed - centered with proper spacing */
#sidebar.collapsed #menu-list {
    margin-top: 1.5rem;
    margin-bottom: 1.5rem;
}

#sidebar.collapsed #menu-list li {
    margin-bottom: 0.5rem;
}

#sidebar.collapsed #menu-list a,
#sidebar.collapsed ul.menu a,
#sidebar.collapsed ul.menu button {
    justify-content: center;
    padding: 0.75rem 0;
}

#sidebar.collapsed #collapse-btn {
    padding: 0.5rem;
    margin-top: 0.5rem;
}

#sidebar.collapsed #collapse-icon {
    transform: rotate(180deg);
}

/* Mobile sidebar overlay/backdrop */

/* Mobile sidebar overlay/backdrop */
#sidebar-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    transition: opacity 0.3s ease;
}
#sidebar-backdrop.active {
    opacity: 1;
}

/* Mobile sidebar styles */
@media (max-width: 768px) {
    #mobile-menu-btn {
        display: flex;
        align-items: center;
        justify-content: center;
    }

            /* Show mobile header on mobile */
            #mobile-header {
                display: flex !important;
            }
            
            /* Hide mobile header when sidebar is open */
            #mobile-header.hidden-when-open {
                display: none !important;
            }
            
            /* Add padding to main content to account for mobile header */
            body {
                padding-top: 60px;
            }
            
            /* Hide page titles on mobile since they're in the mobile header */
            .page-content > h1,
            .page-content > div > h4,
            .page-content > .flex > h4 {
                display: none !important;
            }    #sidebar-backdrop {
        display: block;
        pointer-events: none;
        opacity: 0;
    }
    #sidebar-backdrop.active {
        pointer-events: auto;
        opacity: 1;
    }

    #sidebar {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        height: 100vh !important;
        width: 280px !important;
        min-width: 280px !important;
        max-width: 280px !important;
        border-radius: 0 !important;
        z-index: 1000;
        transform: translateX(-100%);
        transition: transform 0.3s ease !important;
    }

    #sidebar.mobile-open {
        transform: translateX(0);
    }

    /* Hide collapse button on mobile */
    #sidebar #collapse-btn {
        display: none;
    }

    /* Override collapsed state on mobile */
    #sidebar.collapsed {
        width: 280px !important;
        min-width: 280px !important;
        max-width: 280px !important;
    }
    #sidebar.collapsed .menu-text,
    #sidebar.collapsed #admin-name,
    #sidebar.collapsed #admin-role {
        opacity: 1;
        width: auto;
    }
    #sidebar.collapsed #avatar-circle {
        width: 6rem !important;
        height: 6rem !important;
    }
    #sidebar.collapsed #avatar-initials {
        font-size: 1.875rem;
    }
    #sidebar.collapsed #menu-list a,
    #sidebar.collapsed ul.menu a,
    #sidebar.collapsed ul.menu button {
        justify-content: center;
        padding: 0.75rem 0.5rem;
    }
    /* Show sidebar header text on mobile even when collapsed */
    #sidebar.collapsed #sidebar-title-text {
        display: flex !important;
    }

    /* Adjust main content for mobile */
    .flex.p-4.gap-4.min-h-screen {
        padding-top: 4.5rem;
    }
    

    /* Mobile close button styling */
    #mobile-close-btn {
        display: flex;
    }
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
}

/* Hide mobile close button on desktop */
@media (min-width: 769px) {
    #mobile-close-btn {
        display: none !important;
    }
}

/* Support Ticket Card Styles (mobile) */
.ticket-card {
    background: white;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: relative;
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
}

.ticket-card:active {
    transform: scale(0.98);
}

.ticket-card.status-open {
    border-left-color: #F59E0B;
}

.ticket-card.status-in-progress {
    border-left-color: #3B82F6;
}

.ticket-card.status-resolved {
    border-left-color: #10B981;
}

.ticket-card.status-closed {
    border-left-color: #6B7280;
}

.ticket-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.ticket-card-id {
    font-weight: 700;
    font-size: 14px;
    color: #6D28D9;
}

.ticket-card-title {
    font-weight: 600;
    font-size: 16px;
    color: #1F2937;
    margin-bottom: 8px;
}

.ticket-card-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 12px;
}

.ticket-card-row {
    display: flex;
    align-items: flex-start;
    font-size: 14px;
}

.ticket-card-label {
    font-weight: 500;
    color: #6B7280;
    min-width: 80px;
    flex-shrink: 0;
}

.ticket-card-value {
    color: #374151;
    word-break: break-word;
}

.ticket-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #E5E7EB;
}

.ticket-card-actions {
    display: flex;
    gap: 8px;
}

/* Dark theme for ticket cards */
[data-theme="dark"] .ticket-card {
    background: #1F2937;
}

[data-theme="dark"] .ticket-card-id {
    color: #A78BFA;
}

[data-theme="dark"] .ticket-card-title {
    color: #F9FAFB;
}

[data-theme="dark"] .ticket-card-label {
    color: #9CA3AF;
}

[data-theme="dark"] .ticket-card-value {
    color: #E5E7EB;
}

[data-theme="dark"] .ticket-card-footer {
    border-top-color: #374151;
}
</style>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>

<body class="min-h-screen bg-custom">
<?php
    $BASE_PATH = rtrim(parse_url(url('/'), PHP_URL_PATH) ?? '', '/');
    $fullName = trim(auth('superadmin')->user()->name ?? 'Super Admin');
    
    // Check if name starts with "admin" (case-insensitive)
    $initials = '';
    if (preg_match('/^admin(.+)/i', $fullName, $matches)) {
        // Extract the part after "admin"
        $afterAdmin = trim($matches[1]);
        if (!empty($afterAdmin)) {
            $initials = mb_strtoupper(mb_substr($afterAdmin, 0, 1));
        }
    }
    
    // Fallback: use standard first letter logic if no "admin" prefix
    if (!$initials) {
        $nameWords = $fullName !== '' ? preg_split('/\s+/', $fullName) : [];
        if (!empty($nameWords)) {
            $initials = mb_strtoupper(mb_substr($nameWords[0], 0, 1));
            if (isset($nameWords[1]) && mb_strlen($nameWords[1]) > 0) {
                $initials .= mb_strtoupper(mb_substr($nameWords[1], 0, 1));
            }
        }
    }
    
    if (!$initials)
        $initials = 'SA';
?>

    <!-- Mobile Header Bar -->
    <header id="mobile-header" class="fixed top-0 left-0 right-0 z-[998] bg-white shadow-md px-4 py-3 flex items-center justify-between">
        <!-- Hamburger Menu Button -->
        <button id="mobile-menu-btn" class="p-2 rounded-lg bg-primary-purple text-white hover:bg-primary-purple-hover transition-colors" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        
        <!-- Page Title -->
        <h1 id="mobile-page-title" class="text-lg font-bold text-primary-purple flex-1 text-center">Super Admin Dashboard</h1>
        
        <!-- Spacer for balance (replaces notification button) -->
        <div class="w-10 h-10"></div>
    </header>

    <!-- Sidebar Backdrop -->
    <div id="sidebar-backdrop"></div>

    <div class="flex p-4 gap-4 min-h-screen">
        <!-- Sidebar -->
         <aside id="sidebar" class="flex flex-col bg-white rounded-2xl p-4 shadow-sm sticky top-4 self-start h-[calc(100vh-2rem)] overflow-hidden transition-all duration-300" style="width: 200px; min-width: 200px; max-width: 200px;">
            <!-- Mobile Close Button -->
            <button id="mobile-close-btn" class="md:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-gray-100 transition-colors z-10" aria-label="Close menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Logo Header Section -->
            <div id="sidebar-header" class="flex flex-col items-center gap-2 pb-4 border-b border-gray-200 mb-2 text-center">
                <img id="sidebar-logo-light" src="<?php echo e(asset('storage/vits_purple.png')); ?>" alt="VITS Logo" class="h-10 w-10 object-contain" />
                <img id="sidebar-logo-dark" src="<?php echo e(asset('storage/vits_white.png')); ?>" alt="VITS Logo" class="h-10 w-10 object-contain hidden" />
                <div id="sidebar-title-text" class="flex flex-col leading-tight">
                    <span class="text-sm font-bold text-primary-purple sidebar-title-main">VITS Social Contract</span>
                    <span class="text-xs font-semibold text-primary-purple sidebar-title-sub">Management</span>
                    <span class="text-xs font-semibold text-primary-purple sidebar-title-sub">& Monitoring System</span>
                </div>
            </div>

            <!-- Profile Section -->
            <div id="avatar-section" class="flex flex-col items-center text-center p-4 border-b border-gray-200 transition-all duration-300">
                <div id="avatar-container" class="avatar placeholder mb-3 transition-all duration-300">
                    <div id="avatar-circle" class="w-24 h-24 rounded-full ring ring-[#6D28D9] ring-offset-2 ring-offset-base-100 bg-[#6D28D9] text-white flex items-center justify-center select-none transition-all duration-300" 
                         title="<?php echo e($fullName); ?>" 
                         aria-label="<?php echo e($fullName); ?>">
                        <span id="avatar-initials" class="text-3xl font-bold leading-none transition-all duration-300"><?php echo e($initials); ?></span>
                    </div>
                </div>
                <h2 id="admin-name" class="font-bold text-lg transition-opacity duration-300"><?php echo e($fullName); ?></h2>
                <p id="admin-role" class="text-sm text-gray-500 transition-opacity duration-300">Super Administrator</p>
            </div>

            <!-- Main Navigation -->
            <ul id="menu-list" class="menu p-0 my-4 flex-grow transition-all duration-300">
                <li>
                    <a class="py-3 pl-2 transition-all duration-300" id="nav-dashboard" onclick="showPage('dashboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2 transition-all duration-300" id="nav-submission" onclick="showPage('submission')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="menu-text">Submission</span>
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2 transition-all duration-300" id="nav-students" onclick="showPage('students')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="menu-text">Students Information</span>
                    </a>
                </li>
            </ul>

            <!-- Bottom Navigation -->
            <ul class="menu p-0 transition-all duration-300">
                <li>
                    <a class="py-3 pl-2 transition-all duration-300" id="nav-support" onclick="showPage('support')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="menu-text">Support Tickets</span>
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2 w-full text-left flex items-center gap-2 min-h-0 transition-all duration-300" 
                       id="nav-settings" 
                       onclick="showPage('settings')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.096 2.572-1.065z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <span class="menu-text">Settings</span>
                    </a>
                </li>
                <li>
                    <form id="logout-form-visible" 
                          action="<?php echo e(route('superadmin.logout')); ?>" 
                          method="POST" 
                          class="m-0 p-0 w-full flex" 
                          novalidate>
                        <?php echo csrf_field(); ?>
                        <button id="logout-button-visible" 
                                type="button" 
                                class="py-3 pl-2 pr-0 w-full text-left flex items-center gap-2 min-h-0 transition-all duration-300"
                                onclick="document.getElementById('logout_modal').showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="menu-text">Log Out</span>
                        </button>
                    </form>
                </li>
            </ul>

            <!-- Theme Toggle -->
            <ul class="menu p-0 pb-2">
                <li>
                    <label id="theme-toggle-container" class="py-3 pl-2 pr-2 w-full flex items-center gap-2 min-h-0 transition-all duration-300 cursor-pointer">
                        <!-- Sun icon (light mode) -->
                        <svg id="theme-icon-sun" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon (dark mode) -->
                        <svg id="theme-icon-moon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <span id="theme-label" class="menu-text">Dark Mode</span>
                        <input id="theme-toggle" type="checkbox" class="toggle toggle-primary toggle-sm ml-auto" />
                    </label>
                </li>
            </ul>
            
            <!-- Collapse button -->
            <button id="collapse-btn" class="btn btn-ghost btn-sm w-full mt-2">
                <svg id="collapse-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <span id="collapse-text">Hide</span>
            </button>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col gap-6 min-w-0" id="page-container">
            
            <!-- Flash Messages -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success shadow-lg mx-4" id="flash-message">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="alert alert-error shadow-lg mx-4" id="flash-message">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <?php echo $__env->make('partials.super_admin.dashboard-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <?php echo $__env->make('partials.super_admin.submission-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            
            <?php echo $__env->make('partials.super_admin.students-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php echo $__env->make('partials.super_admin.settings-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php echo $__env->make('partials.super_admin.support-page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </main>
    </div>

    <?php echo $__env->make('partials.super_admin.modals', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- Toast Container -->
    <div id="toast-root" class="toast toast-bottom toast-end fixed bottom-4 right-4 z-[5000] space-y-2"></div>

    <!-- Scripts -->
    <script>
        // Global variables
        var activeRow = null;
        var allSubmissions = []; // Store all submissions data
        var lastSubmissionsData = null; // Track last loaded data to prevent unnecessary updates
        var BASE_PATH = <?php echo json_encode($BASE_PATH, 15, 512) ?>;
        var recordToDelete = null; // Store record ID for deletion
        
        // Request cache to prevent duplicate API calls
        const requestCache = new Map();
        const pendingRequests = new Map();
        
        // Smart fetch with caching, deduplication, and retry logic
        async function smartFetch(url, options = {}, cacheTime = 5000) {
            const cacheKey = url + JSON.stringify(options);
            if (requestCache.has(cacheKey)) {
                const cached = requestCache.get(cacheKey);
                if (Date.now() - cached.timestamp < cacheTime) {
                    return cached.response.clone();
                }
                requestCache.delete(cacheKey);
            }
            if (pendingRequests.has(cacheKey)) {
                return pendingRequests.get(cacheKey);
            }
            const fetchPromise = (async () => {
                let lastError;
                for (let attempt = 0; attempt < 3; attempt++) {
                    try {
                        const response = await fetch(url, {
                            ...options,
                            signal: AbortSignal.timeout(15000)
                        });
                        if (!response.ok && response.status >= 500) {
                            throw new Error(`Server error: ${response.status}`);
                        }
                        if (!options.method || options.method === 'GET') {
                            requestCache.set(cacheKey, {
                                response: response.clone(),
                                timestamp: Date.now()
                            });
                        }
                        return response;
                    } catch (error) {
                        lastError = error;
                        if (attempt < 2) {
                            await new Promise(resolve => setTimeout(resolve, 500 * Math.pow(2, attempt)));
                        }
                    }
                }
                throw lastError;
            })();
            pendingRequests.set(cacheKey, fetchPromise);
            try {
                const result = await fetchPromise;
                return result;
            } finally {
                pendingRequests.delete(cacheKey);
            }
        }
        
        // Helper function to get current date/time in Philippine timezone (Asia/Manila, UTC+8)
        function getPhilippineDate(dateInput = null) {
            if (!dateInput) {
                // Return current time in Philippine timezone
                const now = new Date();
                const phTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
                return phTime;
            }
            
            // Parse input date - assume it's UTC from database
            const date = new Date(dateInput);
            
            // Convert to Philippine timezone (UTC+8)
            const phTime = new Date(date.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            return phTime;
        }
        
        // ==================== CSRF TOKEN SETUP ====================
        let csrfTokenCache = null;
        let csrfTokenExpiry = 0;
        
        function getCsrfToken() {
            if (csrfTokenCache && Date.now() < csrfTokenExpiry) return csrfTokenCache;
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            const token = metaTag ? metaTag.getAttribute('content') : '';
            if (token) {
                csrfTokenCache = token;
                csrfTokenExpiry = Date.now() + 60000;
            }
            return token;
        }
        
        setInterval(async () => {
            try {
                const response = await smartFetch(`${BASE_PATH}/api/refresh-csrf`, {
                    method: 'GET',
                    credentials: 'include',
                    headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
                }, 0);
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const data = await response.json();
                if (data.token) {
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.setAttribute('content', data.token);
                        csrfTokenCache = data.token;
                        csrfTokenExpiry = Date.now() + 60000;
                    }
                }
            } catch (e) {
                console.warn('[CSRF] Failed to auto-refresh token:', e.message);
            }
        }, 10 * 60 * 1000);
        
        // CSRF Cookie Helper Functions
        const cookieCache = new Map();
        let cookieCacheExpiry = 0;
        
        function getCookie(name) {
            if (Date.now() > cookieCacheExpiry) {
                cookieCache.clear();
                const cookies = document.cookie.split(';');
                cookies.forEach(cookie => {
                    const [key, value] = cookie.trim().split('=');
                    if (key) cookieCache.set(key, value);
                });
                cookieCacheExpiry = Date.now() + 5000;
            }
            return cookieCache.get(name) || null;
        }
        
        let lastCsrfFetch = 0;
        let csrfFetchPromise = null;
        
        async function ensureCsrfCookie() {
            if (csrfFetchPromise) return csrfFetchPromise;
            const now = Date.now();
            if (getCookie('XSRF-TOKEN') && now - lastCsrfFetch < 30000) return true;
            try {
                csrfFetchPromise = (async () => {
                    const response = await smartFetch(`${BASE_PATH}/api/csrf-cookie`, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'}
                    }, 0);
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    await new Promise(resolve => setTimeout(resolve, 50));
                    cookieCache.clear();
                    cookieCacheExpiry = 0;
                    lastCsrfFetch = Date.now();
                    return getCookie('XSRF-TOKEN') ? true : false;
                })();
                return await csrfFetchPromise;
            } catch (e) {
                console.error('[CSRF] Error:', e.message);
                return false;
            } finally {
                csrfFetchPromise = null;
            }
        }
        
        var hoursSortDirection = 'desc'; // 'asc' or 'desc'
        var studentIdSortDirection = 'desc'; // 'asc' or 'desc'
        var dateSortDirection = 'desc'; // 'asc' or 'desc'
        var studentNameSortDirection = 'desc'; // 'asc' or 'desc'
        var eventNameSortDirection = 'desc'; // 'asc' or 'desc'
        var organizationSortDirection = 'desc'; // 'asc' or 'desc'
        var actionDateSortDirection = 'desc'; // 'asc' or 'desc'
        var statusSortDirection = 'desc'; // 'asc' or 'desc'
        var currentSortBy = null; // 'hours', 'studentid', 'date', 'studentname', 'eventname', 'organization', 'actiondate', 'status'
        var currentStatusFilter = 'All'; // Track current status filter for archived tab

        // Support tickets data
        var allTickets = [];
        var currentTicketId = null;

        // Refresh support tickets with loading animation
        async function refreshSupportTickets() {
            const refreshBtn = document.getElementById('refresh-support-tickets-btn');
            const refreshIcon = document.getElementById('refresh-support-tickets-icon');
            
            // Add spinning animation
            refreshBtn.disabled = true;
            refreshIcon.classList.add('animate-spin');
            
            try {
                await loadSupportTickets();
                showToast('Support tickets refreshed successfully', 'success');
            } catch (error) {
                showToast('Failed to refresh support tickets', 'error');
            } finally {
                // Remove spinning animation
                refreshBtn.disabled = false;
                refreshIcon.classList.remove('animate-spin');
            }
        }

        // Load support tickets from API
        async function loadSupportTickets() {
            try {
                const response = await fetch(`${BASE_PATH}/super-admin/api/support-tickets`, {
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
                    // Also load student counts for quick-stats
                    if (typeof loadStudentCounts === 'function') {
                        loadStudentCounts();
                    }
                } else {
                    console.error('Failed to load tickets:', data.message);
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
            }
        }

        // Show ticket details in modal
        function showTicketDetails(ticketId) {
            const ticket = allTickets.find(t => t.id == ticketId);
            if (!ticket) return;

            currentTicketId = ticketId;
            document.getElementById('modal-ticket-id').textContent = ticket.id;
            document.getElementById('modal-ticket-student').textContent = ticket.student_name || 'N/A';
            document.getElementById('modal-ticket-student-id').textContent = ticket.student_id || 'N/A';
            document.getElementById('modal-ticket-type').textContent = ticket.type;
            document.getElementById('modal-ticket-details').textContent = ticket.details;
            document.getElementById('modal-ticket-submitted').textContent = ticket.submitted_at || ticket.date;
            document.getElementById('modal-ticket-updated').textContent = ticket.updated_at || ticket.date;

            // Show linked record if available and show Find Record button
            const linkedRecordContainer = document.getElementById('modal-ticket-linked-record-container');
            const linkedRecordElement = document.getElementById('modal-ticket-linked-record');
            const findRecordBtn = document.getElementById('find-record-btn');
            
            console.log('Ticket data:', {
                id: ticket.id,
                type: ticket.type,
                record_id: ticket.record_id,
                has_linked_record: !!ticket.linked_record
            });
            
            // Show Find Record button for "Submitted Record Linked to Wrong Academic Year" tickets
            const isWrongAcademicYearIssue = ticket.type && ticket.type.includes('Submitted Record Linked to Wrong Academic Year');
            
            if (isWrongAcademicYearIssue) {
                findRecordBtn.classList.remove('hidden');
                
                // If we have the linked record data, show it
                if (ticket.record_id && ticket.linked_record) {
                    const record = ticket.linked_record;
                    let dateStr = 'No date';
                    if (record.date) {
                        let dateValue = String(record.date);
                        if (dateValue.includes('T')) {
                            dateValue = dateValue.split('T')[0];
                        }
                        const parts = dateValue.split('-');
                        if (parts.length === 3) {
                            const [y, m, d] = parts;
                            dateStr = `${d.padStart(2,'0')}-${m.padStart(2,'0')}-${y}`;
                        } else {
                            dateStr = dateValue;
                        }
                    }
                    const eventName = record.event_name || record.organization || 'No event name';
                    const venue = record.venue || 'No venue';
                    const status = record.status || 'Unknown';
                    linkedRecordElement.textContent = `${dateStr} - ${eventName} at ${venue} (${status})`;
                    linkedRecordContainer.classList.remove('hidden');
                    
                    // Store record_id for the find function
                    findRecordBtn.setAttribute('data-record-id', ticket.record_id);
                } else {
                    // No linked record data, but still show button - it will try to find based on details
                    linkedRecordContainer.classList.add('hidden');
                    findRecordBtn.setAttribute('data-record-id', ticket.record_id || '');
                }
            } else {
                linkedRecordContainer.classList.add('hidden');
                findRecordBtn.classList.add('hidden');
            }

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

            // Show/hide resolve button based on status
            const resolveContainer = document.getElementById('resolve-action-container');
            const resolveBtn = document.getElementById('resolve-ticket-btn');
            if (ticket.status === 'Pending') {
                resolveContainer.style.display = 'block';
                resolveBtn.disabled = false;
            } else {
                resolveContainer.style.display = 'none';
            }

            document.getElementById('ticket_details_modal').showModal();
        }

        // Resolve ticket (super admin marks as resolved)
        async function resolveTicket() {
            if (!currentTicketId) return;

            const resolveBtn = document.getElementById('resolve-ticket-btn');
            
            resolveBtn.disabled = true;
            resolveBtn.textContent = 'Resolving...';

            try {
                const response = await fetch(`${BASE_PATH}/super-admin/api/support-tickets/${currentTicketId}/resolve`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'include'
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Ticket resolved successfully. Student has been notified.', 'success');
                    await loadSupportTickets();
                    document.getElementById('ticket_details_modal').close();
                } else {
                    showToast(data.message || 'Failed to resolve ticket', 'error');
                }
            } catch (error) {
                console.error('Error resolving ticket:', error);
                showToast('Error resolving ticket', 'error');
            } finally {
                resolveBtn.disabled = false;
                resolveBtn.textContent = 'Mark as Resolved';
            }
        }

        // Find and highlight linked record in archived tab
        function findLinkedRecord() {
            const findBtn = document.getElementById('find-record-btn');
            let recordId = findBtn.getAttribute('data-record-id');
            
            const ticketDetails = document.getElementById('modal-ticket-details').textContent;
            const studentId = document.getElementById('modal-ticket-student-id').textContent;
            
            console.log('Finding record. Record ID:', recordId);
            console.log('Ticket details:', ticketDetails);
            console.log('Student ID:', studentId);
            
            // Extract information from ticket details
            // Format: "Record: DD-MM-YYYY - Event Name at Venue (Status)\nDetails: ..."
            let searchCriteria = {
                studentId: studentId,
                date: null,
                eventName: null,
                venue: null,
                status: null
            };
            
            // Try to parse record info from details
            const recordMatch = ticketDetails.match(/Record:\s*([^\n]+)/);
            if (recordMatch) {
                const recordInfo = recordMatch[1].trim();
                console.log('Extracted record info:', recordInfo);
                
                // Parse: "21-10-2025 - ITLYMPICS at Main Building (Approved)"
                const dateMatch = recordInfo.match(/(\d{2}-\d{2}-\d{4})/);
                
                if (dateMatch) {
                    searchCriteria.date = dateMatch[1].trim();
                    
                    // Remove the date from the beginning to get the rest
                    const afterDate = recordInfo.substring(recordInfo.indexOf(dateMatch[1]) + dateMatch[1].length).trim();
                    
                    // Now parse: "- ITLYMPICS at Main Building (Approved)"
                    const eventVenueMatch = afterDate.match(/^-\s*([^at]+)\s+at\s+([^(]+)\s*\(([^)]+)\)/);
                    
                    if (eventVenueMatch) {
                        searchCriteria.eventName = eventVenueMatch[1].trim();
                        searchCriteria.venue = eventVenueMatch[2].trim();
                        searchCriteria.status = eventVenueMatch[3].trim();
                    }
                }
                
                console.log('Search criteria:', searchCriteria);
            }
            
            // If we have record_id, use it directly
            if (recordId) {
                console.log('Finding record with ID:', recordId);
            } else {
                console.log('No record_id, will search by criteria');
            }

            // Close the ticket modal
            document.getElementById('ticket_details_modal').close();

            // Navigate to submission page
            showPage('submission');

            // Wait a moment for the page to load, then switch to archived tab
            setTimeout(function() {
                // Click on the archived tab
                const tabs = document.querySelectorAll('.custom-tab');
                let archivedTab = null;
                
                tabs.forEach(function(tab) {
                    const tabText = tab.textContent.trim().toLowerCase();
                    if (tabText === 'archived') {
                        archivedTab = tab;
                    }
                });

                if (archivedTab) {
                    archivedTab.click();
                    
                    // Wait longer for archived records to fully load, then highlight the record
                    setTimeout(function() {
                        let targetRow = null;
                        
                        // Check if records are loaded
                        const tbody = document.getElementById('submission-table-body');
                        if (!tbody) {
                            console.error('Submission table body not found!');
                            showToast('Could not find submission records table', 'error');
                            return;
                        }
                        
                        // First try to find by record ID
                        if (recordId) {
                            targetRow = tbody.querySelector(`tr[data-record-id="${recordId}"]`);
                            console.log('Searching by record ID:', recordId, 'Found:', !!targetRow);
                        }
                        
                        // If not found by ID, search by matching criteria
                        if (!targetRow && searchCriteria.date) {
                            console.log('Searching by criteria:', searchCriteria);
                            const allRows = tbody.querySelectorAll('tr');
                            
                            // The date from ticket details is already in MM-DD-YYYY format (extracted from student submission)
                            // No conversion needed - use it as-is
                            let searchDateConverted = searchCriteria.date;
                            
                            console.log('Search date (already MM-DD-YYYY):', searchDateConverted);
                            console.log('Total rows to check:', allRows.length);
                            
                            allRows.forEach(function(row, index) {
                                // Get row data - columns: Student ID, Student Name, Event Name, Organization/Supervisor, Hours, Date, Action
                                const rowStudentId = row.cells[0]?.textContent.trim();
                                const rowEventName = row.cells[2]?.textContent.trim();
                                const rowDate = row.cells[5]?.textContent.trim();
                                
                                // Get venue and status from data attributes
                                const rowVenue = row.dataset.venue || '';
                                const rowStatus = row.dataset.recordStatus || '';
                                
                                // Match by student ID, date, and event name (primary criteria)
                                const studentMatch = rowStudentId === searchCriteria.studentId;
                                const dateMatch = rowDate === searchDateConverted;
                                const eventMatch = searchCriteria.eventName && rowEventName.includes(searchCriteria.eventName);
                                const venueMatch = !searchCriteria.venue || rowVenue.includes(searchCriteria.venue);
                                const statusMatch = !searchCriteria.status || rowStatus.includes(searchCriteria.status);
                                
                                // Only log rows that match student and date
                                if (studentMatch && dateMatch) {
                                    console.log(`Row ${index + 1} (potential match):`, {
                                        rowData: { rowStudentId, rowDate, rowEventName, rowVenue, rowStatus },
                                        searching: { 
                                            studentId: searchCriteria.studentId, 
                                            date: searchDateConverted, 
                                            eventName: searchCriteria.eventName, 
                                            venue: searchCriteria.venue, 
                                            status: searchCriteria.status 
                                        },
                                        matches: { studentMatch, dateMatch, eventMatch, venueMatch, statusMatch }
                                    });
                                }
                                
                                // Match requires student ID, date, and event name at minimum
                                if (studentMatch && dateMatch && eventMatch) {
                                    targetRow = row;
                                    console.log('✅ Found matching row!');
                                }
                            });
                        }
                        
                        if (targetRow) {
                            // Remove any existing highlights
                            document.querySelectorAll('#submission-table-body tr').forEach(function(row) {
                                row.classList.remove('bg-yellow-100', 'dark:bg-yellow-900/30');
                                row.style.animation = '';
                            });
                            
                            // Scroll to the row
                            targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            
                            // Highlight the row with animation
                            targetRow.classList.add('bg-yellow-100', 'dark:bg-yellow-900/30');
                            targetRow.style.animation = 'pulse 2s ease-in-out 3';
                            
                            showToast('Record found and highlighted!', 'success');
                            
                            // Remove highlight after 10 seconds
                            setTimeout(function() {
                                targetRow.classList.remove('bg-yellow-100', 'dark:bg-yellow-900/30');
                                targetRow.style.animation = '';
                            }, 10000);
                        } else {
                            console.log('No matching record found');
                            showToast('Record not found in archived records. It may have been deleted or moved.', 'error');
                        }
                    }, 2000); // Increased timeout to 2 seconds for records to load
                } else {
                    showToast('Could not find archived tab', 'error');
                }
            }, 500); // Increased tab switching delay
        }

        // Toast notification function
        function showToast(m, t) {
            t = t || 'success';
            try {
                var r = document.getElementById('toast-root');
                if (!r) return alert(m);
                
                var e = document.createElement('div');
                e.className = 'scms-toast scms-toast--' + t;
                e.setAttribute('role', 'status');
                e.setAttribute('aria-live', 'polite');
                e.style.pointerEvents = 'auto';
                e.innerHTML = '<span class="scms-toast__msg">' + ((m || '').replace(/</g, '&lt;')) + '</span>' +
                    '<button class="scms-toast__close" aria-label="Close">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                    '<line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>' +
                    '</svg></button>' +
                    '<div class="scms-toast__progress"></div>';
                
                var c = e.querySelector('.scms-toast__close');
                var p = e.querySelector('.scms-toast__progress');
                
                if (p) p.style.animation = 'scms-toast-progress 3500ms linear forwards';
                
                var rm = null;
                var remove = function() {
                    try { clearTimeout(rm); } catch(_) {}
                    e.style.opacity = '0';
                    e.style.transform = 'translateY(6px)';
                    setTimeout(function() { e.remove(); }, 180);
                };
                
                c.addEventListener('click', remove, {passive: true});
                r.appendChild(e);
                e.style.opacity = '0';
                e.style.transform = 'translateY(6px)';
                
                requestAnimationFrame(function() {
                    e.style.transition = 'opacity .18s ease, transform .18s ease';
                    e.style.opacity = '1';
                    e.style.transform = 'translateY(0)';
                });
                
                rm = setTimeout(remove, 3500);
            } catch(_) {
                try { alert(m); } catch {}
            }
        }

        // Ensure CSRF cookie exists
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
                    }
                }
            } catch (e) {
                console.warn('Could not fetch CSRF cookie:', e);
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility(i) {
            var inp = document.getElementById(i);
            if (inp) inp.type = inp.type === "password" ? "text" : "password";
        }

        // Show/hide pages
        function showPage(p) {
            document.querySelectorAll('aside a').forEach(function(a) {
                a.classList.remove('active-nav');
            });
            document.querySelectorAll('.page-content').forEach(function(pg) {
                pg.classList.add('hidden');
            });
            
            var np = document.getElementById(p + '-page');
            if (np) np.classList.remove('hidden');
            
            var nl = document.getElementById('nav-' + p);
            if (nl) nl.classList.add('active-nav');

            // Update mobile page title
            var pageTitles = {
                'dashboard': 'Super Admin Dashboard',
                'submission': 'Submission',
                'students': 'Students Information',
                'support': 'Support Tickets',
                'settings': 'Settings'
            };
            var mobileTitle = document.getElementById('mobile-page-title');
            if (mobileTitle) {
                mobileTitle.textContent = pageTitles[p] || 'Super Admin Dashboard';
            }
            
            // Save current page to localStorage for super admin
            try {
                localStorage.setItem('scms_superadmin_current_page', p);
            } catch(_) {}
            
            // Load submissions when showing submission page
            if (p === 'submission') {
                loadSubmissions();
                
                // Restore saved tab or default to pending
                setTimeout(function() {
                    var savedTab = 'pending';
                    try {
                        savedTab = localStorage.getItem('scms_superadmin_current_tab') || 'pending';
                    } catch(_) {}
                    
                    // Find the tab element based on saved tab
                    var tabs = document.querySelectorAll('.custom-tab');
                    var targetTab = null;
                    
                    tabs.forEach(function(tab) {
                        var tabText = tab.textContent.trim().toLowerCase();
                        if (tabText === savedTab || tabText === savedTab.replace('-', ' ')) {
                            targetTab = tab;
                        }
                    });
                    
                    // If no matching tab found, use first tab
                    if (!targetTab && tabs.length > 0) {
                        targetTab = tabs[0];
                    }
                    
                    if (targetTab) {
                        targetTab.classList.add('custom-tab-active');
                        filterSubmissions(savedTab, targetTab);
                    }
                }, 100);
            }
            
            // Render tickets when showing support page
            if (p === 'support') {
                loadSupportTickets();
            }
            
            // Load students when showing students page
            if (p === 'students') {
                loadStudents();
            }
        }

        // Attach sort event listeners to table headers
        function attachSortEventListeners() {
            // Helper function to reset all indicators
            function resetAllIndicators(exceptId) {
                var indicators = ['studentid', 'studentname', 'eventname', 'organization', 'hours', 'date', 'actiondate', 'status'];
                indicators.forEach(function(id) {
                    if (id !== exceptId) {
                        var indicator = document.getElementById(id + '-sort-indicator');
                        if (indicator) indicator.textContent = '⇅';
                    }
                });
            }
            
            // Student ID sort
            var studentIdSortToggle = document.getElementById('studentid-sort-toggle');
            var studentIdSortIndicator = document.getElementById('studentid-sort-indicator');
            if (studentIdSortToggle && studentIdSortIndicator) {
                studentIdSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'studentid';
                    studentIdSortDirection = studentIdSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('studentid');
                    studentIdSortIndicator.textContent = studentIdSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'studentid');
                };
            }
            
            // Student Name sort
            var studentNameSortToggle = document.getElementById('studentname-sort-toggle');
            var studentNameSortIndicator = document.getElementById('studentname-sort-indicator');
            if (studentNameSortToggle && studentNameSortIndicator) {
                studentNameSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'studentname';
                    studentNameSortDirection = studentNameSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('studentname');
                    studentNameSortIndicator.textContent = studentNameSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'studentname');
                };
            }
            
            // Event Name sort
            var eventNameSortToggle = document.getElementById('eventname-sort-toggle');
            var eventNameSortIndicator = document.getElementById('eventname-sort-indicator');
            if (eventNameSortToggle && eventNameSortIndicator) {
                eventNameSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'eventname';
                    eventNameSortDirection = eventNameSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('eventname');
                    eventNameSortIndicator.textContent = eventNameSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'eventname');
                };
            }
            
            // Organization sort
            var organizationSortToggle = document.getElementById('organization-sort-toggle');
            var organizationSortIndicator = document.getElementById('organization-sort-indicator');
            if (organizationSortToggle && organizationSortIndicator) {
                organizationSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'organization';
                    organizationSortDirection = organizationSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('organization');
                    organizationSortIndicator.textContent = organizationSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'organization');
                };
            }
            
            // Hours sort
            var hoursSortToggle = document.getElementById('hours-sort-toggle');
            var hoursSortIndicator = document.getElementById('hours-sort-indicator');
            if (hoursSortToggle && hoursSortIndicator) {
                hoursSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'hours';
                    hoursSortDirection = hoursSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('hours');
                    hoursSortIndicator.textContent = hoursSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'hours');
                };
            }
            
            // Date sort
            var dateSortToggle = document.getElementById('date-sort-toggle');
            var dateSortIndicator = document.getElementById('date-sort-indicator');
            if (dateSortToggle && dateSortIndicator) {
                dateSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'date';
                    dateSortDirection = dateSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('date');
                    dateSortIndicator.textContent = dateSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'date');
                };
            }
            
            // Action Date sort
            var actionDateSortToggle = document.getElementById('actiondate-sort-toggle');
            var actionDateSortIndicator = document.getElementById('actiondate-sort-indicator');
            if (actionDateSortToggle && actionDateSortIndicator) {
                actionDateSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'actiondate';
                    actionDateSortDirection = actionDateSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('actiondate');
                    actionDateSortIndicator.textContent = actionDateSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'actiondate');
                };
            }
            
            // Status sort
            var statusSortToggle = document.getElementById('status-sort-toggle');
            var statusSortIndicator = document.getElementById('status-sort-indicator');
            if (statusSortToggle && statusSortIndicator) {
                statusSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'status';
                    statusSortDirection = statusSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('status');
                    statusSortIndicator.textContent = statusSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'status');
                };
            }
        }

        // Render support tickets table
        function renderTicketsTable(tickets = allTickets) {
            const tbody = document.getElementById('ticket-table-body');
            const cardsContainer = document.getElementById('ticket-cards-container');
            if (!tbody && !cardsContainer) return;

            if (!tickets || tickets.length === 0) {
                if (tbody) tbody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-4">No tickets found.</td></tr>';
                if (cardsContainer) cardsContainer.innerHTML = '<div class="text-center text-gray-500 py-4">No tickets found.</div>';
                // Update counts to zero when no tickets
                const pendingEl = document.getElementById('pending-tickets-count');
                const totalEl = document.getElementById('total-tickets-count');
                if (pendingEl) pendingEl.textContent = '0';
                if (totalEl) totalEl.textContent = '0';
                return;
            }

            // Update quick-stats counts
            try {
                const totalCount = tickets.length;
                const pendingCount = tickets.filter(t => (t.status || '').toLowerCase() === 'pending').length;
                const pendingEl = document.getElementById('pending-tickets-count');
                const totalEl = document.getElementById('total-tickets-count');
                if (pendingEl) pendingEl.textContent = pendingCount;
                if (totalEl) totalEl.textContent = totalCount;
            } catch (e) {
                console.error('Error updating ticket counts', e);
            }

            let statusBadges = {
                'Pending': '<span class="badge bg-yellow-100 text-yellow-800 border-0">Pending</span>',
                'Resolved': '<span class="badge bg-green-100 text-green-800 border-0">Resolved</span>',
                'Closed': '<span class="badge bg-gray-100 text-gray-800 border-0">Closed</span>'
            };

            // Render desktop table if tbody exists
            if (tbody) {
                tbody.innerHTML = '';
                tickets.forEach(ticket => {
                    const tr = document.createElement('tr');
                    tr.className = 'cursor-pointer';
                    tr.onclick = () => showTicketDetails(ticket.id);

                    const shortDetails = (ticket.details || '').substring(0, 100) + (ticket.details && ticket.details.length > 100 ? '...' : '');

                    tr.innerHTML = `
                        <td class="font-medium text-text-header" style="min-width: 80px; width: 80px; white-space: nowrap;">${ticket.id}</td>
                        <td class="text-text-header" style="min-width: 90px; width: 90px; white-space: nowrap;">${ticket.student_id || 'N/A'}</td>
                        <td class="text-text-header" style="min-width: 150px; width: 150px; white-space: nowrap;">${ticket.student_name || 'N/A'}</td>
                        <td class="text-text-header" style="min-width: 120px; width: 120px; white-space: nowrap;">${ticket.type}</td>
                        <td class="text-text-muted text-sm" style="min-width: 200px; width: 200px;" title="${ticket.details}">${shortDetails}</td>
                        <td style="min-width: 90px; width: 90px;">
                            <div class="flex flex-col gap-1">
                                ${statusBadges[ticket.status] || ticket.status}
                                <span class="text-xs text-gray-500">${ticket.date}</span>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            // Render mobile cards if container exists
            if (cardsContainer) {
                cardsContainer.innerHTML = '';
                tickets.forEach(ticket => {
                    const shortDetails = (ticket.details || '').split('\n')[0];
                    let statusClass = '';
                    switch ((ticket.status || '').toLowerCase()) {
                        case 'pending': statusClass = 'status-open'; break;
                        case 'resolved': statusClass = 'status-resolved'; break;
                        case 'closed': statusClass = 'status-closed'; break;
                        default: statusClass = 'status-in-progress';
                    }

                    const card = document.createElement('div');
                    card.className = `ticket-card ${statusClass}`;
                    card.onclick = () => showTicketDetails(ticket.id);

                    card.innerHTML = `
                        <div class="ticket-card-header">
                            <div class="ticket-card-id">#${ticket.id}</div>
                            <div class="badge ${ticket.status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : ticket.status === 'Resolved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'} font-semibold border-0">${ticket.status}</div>
                        </div>
                        <div class="ticket-card-title">${ticket.type}</div>
                        <div class="ticket-card-info">
                            <div class="ticket-card-row"><span class="ticket-card-label">Student ID:</span><span class="ticket-card-value">${ticket.student_id || 'N/A'}</span></div>
                            <div class="ticket-card-row"><span class="ticket-card-label">Student:</span><span class="ticket-card-value">${ticket.student_name || 'N/A'}</span></div>
                            <div class="ticket-card-row"><span class="ticket-card-label">Details:</span><span class="ticket-card-value">${shortDetails}</span></div>
                            <div class="ticket-card-row"><span class="ticket-card-label">Date:</span><span class="ticket-card-value">${ticket.date}</span></div>
                        </div>
                        <div class="ticket-card-footer"><div class="ticket-card-actions"></div></div>
                    `;

                    cardsContainer.appendChild(card);
                });
            }
        }

        // Load student counts for quick-stats (verified / unverified)
        async function loadStudentCounts() {
            try {
                const response = await fetch(`${BASE_PATH}/super-admin/api/students`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    console.warn('Failed to fetch students list, status:', response.status);
                    return;
                }

                const data = await response.json();
                // Try multiple possible payload shapes
                const students = Array.isArray(data) ? data : (data.students || data.data || []);

                if (!Array.isArray(students)) {
                    console.warn('Unexpected students payload format', data);
                    return;
                }

                let verified = 0, unverified = 0;
                students.forEach(s => {
                    // Heuristics to determine verification
                    const isVerified = !!(
                        s.verified === true ||
                        s.is_verified === true ||
                        (s.verified_at && s.verified_at !== null) ||
                        (s.status && String(s.status).toLowerCase() === 'verified') ||
                        (s.status && String(s.status).toLowerCase() === 'active')
                    );
                    if (isVerified) verified++; else unverified++;
                });

                const verifiedEl = document.getElementById('verified-students-count');
                const unverifiedEl = document.getElementById('unverified-students-count');
                if (verifiedEl) verifiedEl.textContent = verified;
                if (unverifiedEl) unverifiedEl.textContent = unverified;
            } catch (err) {
                console.error('Error loading student counts', err);
            }
        }

        // Support tickets sorting
        var ticketsSortColumn = null;
        var ticketsSortDirection = 'asc';

        function sortTickets(column) {
            // Toggle direction if same column, otherwise default to ascending
            if (ticketsSortColumn === column) {
                ticketsSortDirection = ticketsSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                ticketsSortColumn = column;
                ticketsSortDirection = 'asc';
            }
            
            // Reset all ticket sort icons
            document.querySelectorAll('[id^="ticket-"][id$="-sort-icon"]').forEach(icon => {
                icon.textContent = '⇅';
            });
            
            // Update current column icon
            var iconId = column + '-sort-icon';
            var icon = document.getElementById(iconId);
            if (icon) {
                icon.textContent = ticketsSortDirection === 'asc' ? '↑' : '↓';
            }
            
            // Sort the tickets array
            var sortedTickets = [...allTickets].sort((a, b) => {
                let aVal, bVal;
                
                switch(column) {
                    case 'ticket-id':
                        aVal = parseInt(a.id) || 0;
                        bVal = parseInt(b.id) || 0;
                        break;
                    case 'ticket-student-id':
                        aVal = (a.student_id || '').toLowerCase();
                        bVal = (b.student_id || '').toLowerCase();
                        break;
                    case 'ticket-student-name':
                        aVal = (a.student_name || '').toLowerCase();
                        bVal = (b.student_name || '').toLowerCase();
                        break;
                    case 'ticket-issue-type':
                        aVal = (a.type || '').toLowerCase();
                        bVal = (b.type || '').toLowerCase();
                        break;
                    case 'ticket-status':
                        aVal = (a.status || '').toLowerCase();
                        bVal = (b.status || '').toLowerCase();
                        break;
                    default:
                        return 0;
                }
                
                if (ticketsSortDirection === 'asc') {
                    return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
                } else {
                    return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
                }
            });
            
            renderTicketsTable(sortedTickets);
        }

        // Mobile ticket sort handler (maps mobile select to existing sortTickets)
        (function attachMobileTicketSortHandler(){
            const mobileSelect = document.getElementById('mobile-admin-ticket-sort-select');
            if (!mobileSelect) return;
            mobileSelect.addEventListener('change', (e) => {
                const value = e.target.value || '';
                // Expected formats: id-desc, id-asc, student_id-asc, student_name-desc, type-asc, status-desc
                const [col, dir] = value.split('-');
                let sortColumn = null;

                switch(col) {
                    case 'id': sortColumn = 'ticket-id'; break;
                    case 'student_id': sortColumn = 'ticket-student-id'; break;
                    case 'student_name': sortColumn = 'ticket-student-name'; break;
                    case 'type': sortColumn = 'ticket-issue-type'; break;
                    case 'status': sortColumn = 'ticket-status'; break;
                    default: sortColumn = null;
                }

                if (sortColumn) {
                    // Force the direction to the selected one
                    ticketsSortColumn = sortColumn;
                    ticketsSortDirection = dir === 'desc' ? 'desc' : 'asc';
                    sortTickets(sortColumn);
                }
            });
        })();

        // Track the current active tab to avoid unnecessary header updates
        var currentActiveTab = null;

        // Update table headers based on active tab
        function updateTableHeaders(tabName) {
            var headerRow = document.getElementById('table-header-row');
            if (!headerRow) return;
            
            var normalizedTab = tabName.toLowerCase().trim();
            
            // Skip update if we're already on this tab (prevents flicker when sorting)
            if (currentActiveTab === normalizedTab) {
                return;
            }
            
            currentActiveTab = normalizedTab;
            
            if (normalizedTab === 'archived') {
                // Archived tab: show Status with filter and Rejection Reason columns + Delete
                headerRow.innerHTML = `
                    <th class="w-[11%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="studentid-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student ID">
                            Student ID
                            <span id="studentid-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[13%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="studentname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student Name">
                            Student Name
                            <span id="studentname-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[14%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Event Name">
                            Event Name
                            <span id="eventname-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[13%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1 flex-col font-bold" title="Sort by Organization">
                            <span>Organization/Supervisor</span>
                            <span id="organization-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[9%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Hours Rendered">
                            Hours
                            <span id="hours-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[9%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Date">
                            Date
                            <span id="date-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[20%] text-center" style="height: 60px; max-height: 60px;">
                        <div class="flex items-center justify-center gap-1 font-bold">
                            <button id="status-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Status">
                                Status
                                <span id="status-sort-indicator">⇅</span>
                            </button>
                            <div class="dropdown dropdown-bottom dropdown-end" id="status-filter-dropdown">
                                <div tabindex="0" role="button" class="btn btn-ghost btn-xs m-1" title="Filter by status">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1.5A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                                    </svg>
                                </div>
                                <ul tabindex="0" class="dropdown-content z-[9999] menu p-2 shadow bg-base-100 rounded-box w-32">
                                    <li><a onclick="filterTableByStatus('All', event)">All</a></li>
                                    <li><a onclick="filterTableByStatus('Verified', event)">Verified</a></li>
                                    <li><a onclick="filterTableByStatus('Approved', event)">Approved</a></li>
                                    <li><a onclick="filterTableByStatus('Rejected', event)">Rejected</a></li>
                                </ul>
                            </div>
                        </div>
                    </th>
                    <th class="w-[11%] text-center" style="height: 60px; max-height: 60px;">
                        <span class="font-bold">Action</span>
                    </th>
                `;
            } else {
                // Pending and For Approval tabs: show Action column with status filter
                headerRow.innerHTML = `
                    <th class="w-[12%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="studentid-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student ID">
                            Student ID
                            <span id="studentid-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[15%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="studentname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student Name">
                            Student Name
                            <span id="studentname-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[16%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Event Name">
                            Event Name
                            <span id="eventname-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[15%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1 flex-col font-bold" title="Sort by Organization">
                            <span>Organization/Supervisor</span>
                            <span id="organization-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[10%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Hours Rendered">
                            Hours
                            <span id="hours-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[10%] text-center" style="height: 60px; max-height: 60px;">
                        <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Date">
                            Date
                            <span id="date-sort-indicator">⇅</span>
                        </button>
                    </th>
                    <th class="w-[22%] text-center" style="height: 60px; max-height: 60px;">
                        <div class="flex items-center justify-center gap-1 font-bold">
                            <span>Action</span>
                        </div>
                    </th>
                `;
            }
            
            // Reattach sort event listeners after updating headers
            attachSortEventListeners();
            
            // Restore the current sort indicator if there's an active sort
            if (currentSortBy) {
                var indicator = document.getElementById(currentSortBy + '-sort-indicator');
                if (indicator) {
                    var direction = 'desc'; // default
                    switch(currentSortBy) {
                        case 'hours':
                            direction = hoursSortDirection;
                            break;
                        case 'studentid':
                            direction = studentIdSortDirection;
                            break;
                        case 'date':
                            direction = dateSortDirection;
                            break;
                        case 'studentname':
                            direction = studentNameSortDirection;
                            break;
                        case 'eventname':
                            direction = eventNameSortDirection;
                            break;
                        case 'organization':
                            direction = organizationSortDirection;
                            break;
                        case 'actiondate':
                            direction = actionDateSortDirection;
                            break;
                        case 'status':
                            direction = statusSortDirection;
                            break;
                    }
                    indicator.textContent = direction === 'asc' ? '↑' : '↓';
                }
            }
            
            // Reattach dropdown positioning fix after header update
            setTimeout(function() {
                var dropdownBtn = document.querySelector('#status-filter-dropdown [role="button"]');
                if (dropdownBtn) {
                    dropdownBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
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
            }, 100);
        }

        // Filter submissions by status
        function filterSubmissions(s, t) {
            document.querySelectorAll('.custom-tab').forEach(function(tb) {
                tb.classList.remove('custom-tab-active');
            });
            t.classList.add('custom-tab-active');
            
            // Update table headers based on tab
            updateTableHeaders(s);
            
            // Reset status filter when switching tabs
            currentStatusFilter = 'All';
            
            // Save current tab for super admin
            try {
                localStorage.setItem('scms_superadmin_current_tab', s);
            } catch(_) {}
            
            var st = document.getElementById('search-input').value.toLowerCase();
            var rs = document.querySelectorAll('#submission-table-body tr');
            
            // Normalize the status string for comparison
            var normalizedStatus = s.toLowerCase().trim();
            var statusMap = {
                'pending': 'pending',
                'for approval': 'for approval',
                'for-approval': 'for approval',
                'forapproval': 'for approval',
                'verified': 'for approval', // Admin verified records appear in "For Approval" tab
                'archived': 'archived'
            };
            var filterStatus = statusMap[normalizedStatus] || normalizedStatus;
            var isArchivedTab = filterStatus === 'archived';
            
            rs.forEach(function(r) {
                // Skip if it's the loading row or header row
                if (!r.dataset.status) {
                    return;
                }
                
                var dataStatus = (r.dataset.status || '').toLowerCase().trim();
                
                // Handle comma-separated status values (e.g., "for approval,archived" for Verified records)
                var statuses = dataStatus.split(',').map(function(status) { return status.trim(); });
                
                // Check if status matches (record can belong to multiple tabs)
                var statusMatch = statuses.includes(filterStatus);
                
                // For search term matching
                var ms = true;
                if (st) {
                    var id = r.cells[0].textContent.toLowerCase();
                    var sn = r.cells[1].textContent.toLowerCase();
                    var en = r.cells[2].textContent.toLowerCase();
                    var sb = r.cells[3].textContent.toLowerCase();
                    var hr = r.cells[4].textContent.toLowerCase();
                    var dt = r.cells[5].textContent.toLowerCase();
                    ms = id.includes(st) || sn.includes(st) || en.includes(st) || 
                         sb.includes(st) || hr.includes(st) || dt.includes(st);
                }
                
                if (statusMatch && ms) {
                    r.classList.remove('hidden');
                    
                    // Toggle visibility of action buttons vs status badges for Verified records
                    var actionButtons = r.querySelector('.for-approval-actions');
                    var statusBadge = r.querySelector('.archived-status');
                    var deleteCell = r.querySelector('.delete-action-cell');
                    
                    if (actionButtons && statusBadge) {
                        if (isArchivedTab) {
                            // In Archived tab: hide action buttons, show status badge, show delete button
                            actionButtons.style.display = 'none';
                            statusBadge.style.display = 'block';
                            if (deleteCell) deleteCell.style.display = 'table-cell';
                        } else {
                            // In For Approval tab: show action buttons, hide status badge, hide delete button
                            actionButtons.style.display = 'block';
                            statusBadge.style.display = 'none';
                            if (deleteCell) deleteCell.style.display = 'none';
                        }
                    } else if (deleteCell) {
                        // For non-Verified archived records, show/hide delete button
                        deleteCell.style.display = isArchivedTab ? 'table-cell' : 'none';
                    }
                } else {
                    r.classList.add('hidden');
                }
            });
        }

        // Filter table by status (for Archived tab)
        function filterTableByStatus(status, event) {
            if (event) event.preventDefault();
            
            currentStatusFilter = status;
            
            var st = document.getElementById('search-input').value.toLowerCase();
            var rs = document.querySelectorAll('#submission-table-body tr');
            
            rs.forEach(function(r) {
                var rowStatus = (r.dataset.status || '').toLowerCase();
                var archiveStatus = r.dataset.archiveStatus;
                
                // Check if row belongs to Archived tab
                var statuses = rowStatus.split(',').map(function(s) { return s.trim(); });
                var isInArchivedTab = statuses.includes('archived');
                
                // Only filter rows that are in the Archived tab
                if (!isInArchivedTab) {
                    return;
                }
                
                var id = r.cells[0].textContent.toLowerCase();
                var sn = r.cells[1].textContent.toLowerCase();
                var en = r.cells[2].textContent.toLowerCase();
                var sb = r.cells[3].textContent.toLowerCase();
                var hr = r.cells[4].textContent.toLowerCase();
                var dt = r.cells[5].textContent.toLowerCase();
                var ms = id.includes(st) || sn.includes(st) || en.includes(st) || 
                         sb.includes(st) || hr.includes(st) || dt.includes(st);
                
                // Apply both status filter and search filter
                var statusMatch = status === 'All' || archiveStatus === status;
                
                if (statusMatch && ms) {
                    r.classList.remove('hidden');
                    
                    // Make sure status badge is shown and action buttons are hidden in Archived tab
                    var actionButtons = r.querySelector('.for-approval-actions');
                    var statusBadge = r.querySelector('.archived-status');
                    
                    if (actionButtons && statusBadge) {
                        actionButtons.style.display = 'none';
                        statusBadge.style.display = 'block';
                    }
                } else {
                    r.classList.add('hidden');
                }
            });
        }

        // Load dashboard statistics
        async function loadDashboardStats() {
            // Ensure CSRF cookie exists before making request
            await ensureCsrfCookie();
            
            smartFetch(`${BASE_PATH}/super-admin/api/dashboard-stats`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                credentials: 'same-origin'
            }, 30000)
            .then(async function(response) {
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok) {
                    throw response;
                }
                // Check if response is JSON (not redirected to login page)
                if (!contentType.includes('application/json')) {
                    console.warn('loadDashboardStats: non-JSON response', { 
                        status: response.status, 
                        url: response.url, 
                        contentType: contentType 
                    });
                    // Likely session expired, redirect to login
                    try { 
                        window.location.replace(BASE_PATH + '/super-admin/login'); 
                    } catch(_) { 
                        window.location.href = BASE_PATH + '/super-admin/login'; 
                    }
                    return Promise.reject(new Error('Non-JSON response'));
                }
                return response.json();
            })
            .then(function(result) {
                if (result.success && result.data) {
                    // Update the counts in the dashboard
                    document.getElementById('pending-requests-count').textContent = result.data.pending;
                    document.getElementById('accepted-requests-count').textContent = result.data.approved_this_week;
                    document.getElementById('rejected-requests-count').textContent = result.data.rejected_this_week;
                } else {
                    console.warn('Invalid dashboard stats format');
                }
            })
            .catch(function(error) {
                console.error('Error loading dashboard stats:', error);
            });
        }

        // Refresh submissions with visual feedback
        function refreshSubmissions() {
            var refreshBtn = document.getElementById('refresh-submissions-btn');
            var refreshIcon = document.getElementById('refresh-icon');
            
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
            
            // Call loadSubmissions with loading indicator
            loadSubmissions(true);
            
            // Re-enable button after 2 seconds
            setTimeout(function() {
                if (refreshBtn && refreshIcon) {
                    refreshBtn.disabled = false;
                    refreshIcon.style.animation = '';
                }
            }, 2000);
        }

        // Chart instances
        let yearlyApprovedChart = null;
        let yearlyRejectedChart = null;

        // Initialize yearly charts
        function initYearlyCharts() {
            const approvedCanvas = document.getElementById('yearlyApprovedChart');
            const rejectedCanvas = document.getElementById('yearlyRejectedChart');
            
            if (!approvedCanvas || !rejectedCanvas) return;
            
            const approvedCtx = approvedCanvas.getContext('2d');
            const rejectedCtx = rejectedCanvas.getContext('2d');
            
            // Destroy existing charts if they exist
            if (yearlyApprovedChart) yearlyApprovedChart.destroy();
            if (yearlyRejectedChart) yearlyRejectedChart.destroy();
            
            // Detect current theme
            const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColor = isDarkTheme ? '#ffffff' : '#2B3674';
            const gridColor = isDarkTheme ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.06)';
            const borderColor = isDarkTheme ? 'rgba(255,255,255,0.2)' : 'rgba(0,0,0,0.12)';
            const tooltipBg = isDarkTheme ? '#1f2937' : '#ffffff';
            const tooltipText = isDarkTheme ? '#ffffff' : '#111827';
            const tooltipBorder = isDarkTheme ? '#374151' : '#e5e7eb';
            
            // Create approved chart (green)
            yearlyApprovedChart = new Chart(approvedCtx, {
                type: 'bar',
                data: {
                    labels: ['2022', '2023', '2024', '2025', '2026'],
                    datasets: [{
                        label: 'Approved Records',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: '#10B981',
                        borderRadius: 8,
                        barThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: tooltipBg,
                            titleColor: tooltipText,
                            bodyColor: tooltipText,
                            borderColor: tooltipBorder,
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                color: textColor,
                                stepSize: 1
                            },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        }
                    }
                }
            });
            
            // Create rejected chart (red)
            yearlyRejectedChart = new Chart(rejectedCtx, {
                type: 'bar',
                data: {
                    labels: ['2022', '2023', '2024', '2025', '2026'],
                    datasets: [{
                        label: 'Rejected Records',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: '#EF4444',
                        borderRadius: 8,
                        barThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: tooltipBg,
                            titleColor: tooltipText,
                            bodyColor: tooltipText,
                            borderColor: tooltipBorder,
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                color: textColor,
                                stepSize: 1
                            },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { 
                                color: gridColor,
                                borderColor: borderColor
                            }
                        }
                    }
                }
            });
        }

        // Update yearly charts with data
        function updateYearlyCharts(submissions) {
            if (!yearlyApprovedChart || !yearlyRejectedChart || !submissions) return;
            
            // Create maps for approved and rejected records by year
            const approvedByYear = new Map();
            const rejectedByYear = new Map();
            
            submissions.forEach(function(record) {
                try {
                    const dateStr = record.approved_at || record.rejected_at || record.updated_at || record.created_at;
                    if (!dateStr) return;
                    
                    const date = new Date(dateStr);
                    if (isNaN(date.getTime())) return;
                    
                    const year = date.getFullYear();
                    
                    if (record.status === 'Approved') {
                        approvedByYear.set(year, (approvedByYear.get(year) || 0) + 1);
                    } else if (record.status === 'Rejected') {
                        rejectedByYear.set(year, (rejectedByYear.get(year) || 0) + 1);
                    }
                } catch (e) {
                    console.warn('Error processing record for yearly chart:', e);
                }
            });
            
            // Get sorted years
            const allYears = new Set([...approvedByYear.keys(), ...rejectedByYear.keys()]);
            const sortedYears = Array.from(allYears).sort((a, b) => a - b);
            
            // If no data, use default years
            const yearLabels = sortedYears.length > 0 ? sortedYears : [2022, 2023, 2024, 2025, 2026];
            const approvedData = yearLabels.map(year => approvedByYear.get(year) || 0);
            const rejectedData = yearLabels.map(year => rejectedByYear.get(year) || 0);
            
            // Update charts
            yearlyApprovedChart.data.labels = yearLabels.map(String);
            yearlyApprovedChart.data.datasets[0].data = approvedData;
            yearlyApprovedChart.update();
            
            yearlyRejectedChart.data.labels = yearLabels.map(String);
            yearlyRejectedChart.data.datasets[0].data = rejectedData;
            yearlyRejectedChart.update();
        }

        // Load submissions from database
        var lastSubmissionsData = null; // Store last data to detect changes
        var isLoadingSubmissions = false; // Prevent concurrent requests
        
        async function loadSubmissions(showLoading = true) {
            // Prevent concurrent requests
            if (isLoadingSubmissions) {
                console.log('Already loading submissions, skipping...');
                return;
            }
            
            var tbody = document.getElementById('submission-table-body');
            
            // Only show loading state on initial load or explicit refresh
            if (showLoading && !lastSubmissionsData) {
                tbody.innerHTML = '<tr id="loading-row"><td colspan="7" class="text-center py-8">' +
                    '<span class="loading loading-spinner loading-lg text-primary-purple"></span>' +
                    '<p class="mt-2 text-text-muted">Loading submissions...</p></td></tr>';
            }
            
            isLoadingSubmissions = true;
            
            // Ensure CSRF cookie exists before making request
            await ensureCsrfCookie();
            
            // Fetch submissions from API with caching
            smartFetch(`${BASE_PATH}/super-admin/api/submissions`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                credentials: 'same-origin'
            }, 20000)
            .then(async function(response) {
                const contentType = response.headers.get('content-type') || '';
                if (!response.ok) {
                    throw response;
                }
                // Check if response is JSON (not redirected to login page)
                if (!contentType.includes('application/json')) {
                    console.warn('loadSubmissions: non-JSON response', { 
                        status: response.status, 
                        url: response.url, 
                        contentType: contentType 
                    });
                    // Likely session expired, redirect to login
                    try { 
                        window.location.replace(BASE_PATH + '/super-admin/login'); 
                    } catch(_) { 
                        window.location.href = BASE_PATH + '/super-admin/login'; 
                    }
                    return Promise.reject(new Error('Non-JSON response'));
                }
                return response.json();
            })
            .then(function(result) {
                isLoadingSubmissions = false;
                
                if (result.success && result.data) {
                    // Always update the data when we get a successful response
                    lastSubmissionsData = result.data;
                    allSubmissions = result.data;
                    renderSubmissions(result.data);
                    updateDashboardStats(result.data);
                    updateYearlyCharts(result.data);
                } else {
                    throw new Error('Invalid response format');
                }
            })
            .catch(function(error) {
                isLoadingSubmissions = false;
                console.error('Error loading submissions:', error);
                
                // Only show error if we have no cached data AND it's the first load
                if (!lastSubmissionsData && showLoading) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">' +
                        'Failed to load submissions. Please refresh the page.</td></tr>';
                }
                // If we have cached data, silently keep using it - no toast notification
                // The data will automatically update on next successful refresh
            });
        }
        
        // Render submissions in the table
        function renderSubmissions(submissions, sortBy = null) {
            var tbody = document.getElementById('submission-table-body');
            
            if (!submissions || submissions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-text-muted">' +
                    'No submissions found.</td></tr>';
                return;
            }
            
            // Sort if requested
            var sortedSubmissions = submissions.slice(); // Create a copy
            if (sortBy === 'hours') {
                sortedSubmissions.sort(function(a, b) {
                    var ha = parseInt(a.hours_rendered) || 0;
                    var hb = parseInt(b.hours_rendered) || 0;
                    return hoursSortDirection === 'asc' ? ha - hb : hb - ha;
                });
            } else if (sortBy === 'studentid') {
                sortedSubmissions.sort(function(a, b) {
                    var idA = (a.student_id || '').toString();
                    var idB = (b.student_id || '').toString();
                    return studentIdSortDirection === 'asc' ? idA.localeCompare(idB) : idB.localeCompare(idA);
                });
            } else if (sortBy === 'date') {
                sortedSubmissions.sort(function(a, b) {
                    var dateA = getPhilippineDate(a.date || 0);
                    var dateB = getPhilippineDate(b.date || 0);
                    return dateSortDirection === 'asc' ? dateA - dateB : dateB - dateA;
                });
            } else if (sortBy === 'studentname') {
                sortedSubmissions.sort(function(a, b) {
                    var nameA = (a.student_name || '').toString().toLowerCase();
                    var nameB = (b.student_name || '').toString().toLowerCase();
                    return studentNameSortDirection === 'asc' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
                });
            } else if (sortBy === 'eventname') {
                sortedSubmissions.sort(function(a, b) {
                    var eventA = (a.event_name || '').toString().toLowerCase();
                    var eventB = (b.event_name || '').toString().toLowerCase();
                    return eventNameSortDirection === 'asc' ? eventA.localeCompare(eventB) : eventB.localeCompare(eventA);
                });
            } else if (sortBy === 'organization') {
                sortedSubmissions.sort(function(a, b) {
                    var orgA = (a.organization || '').toString().toLowerCase();
                    var orgB = (b.organization || '').toString().toLowerCase();
                    return organizationSortDirection === 'asc' ? orgA.localeCompare(orgB) : orgB.localeCompare(orgA);
                });
            } else if (sortBy === 'actiondate') {
                sortedSubmissions.sort(function(a, b) {
                    // Parse action_date in format mm-dd-yyyy
                    var parseActionDate = function(dateStr) {
                        if (!dateStr || dateStr === 'â€”') return new Date(0);
                        var parts = dateStr.split('-');
                        if (parts.length === 3) {
                            // month-day-year format
                            return new Date(parts[2], parts[0] - 1, parts[1]);
                        }
                        return new Date(0);
                    };
                    var dateA = parseActionDate(a.action_date);
                    var dateB = parseActionDate(b.action_date);
                    return actionDateSortDirection === 'asc' ? dateA - dateB : dateB - dateA;
                });
            } else if (sortBy === 'status') {
                sortedSubmissions.sort(function(a, b) {
                    var statusA = (a.status || '').toString().toLowerCase();
                    var statusB = (b.status || '').toString().toLowerCase();
                    return statusSortDirection === 'asc' ? statusA.localeCompare(statusB) : statusB.localeCompare(statusA);
                });
            }
            
                var html = '';
            sortedSubmissions.forEach(function(record) {
                var status = record.status || 'Pending';
                var isPending = status === 'Pending';
                var isVerified = status === 'Verified'; // Admin verified - shown in BOTH "For Approval" tab AND "Archived" tab
                var isApproved = status === 'Approved';
                var isRejected = status === 'Rejected';
                var isArchived = isApproved || isRejected || isVerified; // Include Verified in Archived
                
                // Map status to tab: Pending â†’ Pending tab, Verified â†’ BOTH For Approval AND Archived, Approved/Rejected â†’ Archived tab only
                // Use special handling for Verified: it appears in both tabs
                var dataStatus = isPending ? 'Pending' : (isVerified ? 'For Approval,Archived' : 'Archived');
                var dataArchiveStatus = (isApproved || isRejected || isVerified) ? status : '';
                
                var dateStr = record.date ? formatDate(record.date) : 'â€”';
                
                // Escape rejection reason for HTML attribute
                var rejectionReason = (record.rejection_reason || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                
                // Get action date based on status - use the actual timestamp fields
                var actionDateStr = '';
                var actionTimestamp = null;
                
                if (isRejected && record.rejected_at) {
                    actionTimestamp = record.rejected_at;
                } else if (isApproved && record.approved_at) {
                    actionTimestamp = record.approved_at;
                } else if (isVerified && record.verified_at) {
                    actionTimestamp = record.verified_at;
                } else if (record.updated_at) {
                    // Fallback to updated_at if specific timestamp not available
                    actionTimestamp = record.updated_at;
                }
                
                // Format the action date in Philippine timezone
                if (actionTimestamp) {
                    actionDateStr = formatDate(actionTimestamp);
                }
                
                html += '<tr data-status="' + dataStatus + '" ' +
                        (dataArchiveStatus ? 'data-archive-status="' + dataArchiveStatus + '" ' : '') +
                        'data-record-id="' + record.id + '" ' +
                        'data-venue="' + (record.venue || '') + '" ' +
                        'data-organization="' + (record.organization || '') + '" ' +
                        'data-supervisor-name="' + (record.supervisor_name || '') + '" ' +
                        'data-rejection-reason="' + rejectionReason + '" ' +
                        'data-action-date="' + actionDateStr + '" ' +
                        'class="hover cursor-pointer" onclick="openDetailsModal(this)">' +
                        '<td class="text-center" style="min-width: 90px; width: 90px; white-space: nowrap;">' + (record.student_id || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 160px; width: 160px; white-space: nowrap;">' + (record.student_name || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 130px; width: 130px; white-space: nowrap;">' + (record.event_name || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 160px; width: 160px;">' + 
                            '<div class="flex flex-col items-center">' +
                                '<span class="font-medium">' + (record.organization || 'â€”') + '</span>' +
                                '<span class="text-xs text-gray-500">' + (record.supervisor_name || 'â€”') + '</span>' +
                            '</div>' +
                        '</td>' +
                        '<td class="text-center" style="min-width: 70px; width: 70px; white-space: nowrap;">' + (record.hours_rendered || 0) + ' hours</td>' +
                        '<td class="text-center" style="min-width: 100px; width: 100px; white-space: nowrap;">' + dateStr + '</td>';                // Action column logic: 
                // - Pending tab: show Verify/Reject buttons for Pending records
                // - For Approval tab: show Approve/Reject buttons for Verified records  
                // - Archived tab: show status badge for Approved/Rejected/Verified records
                html += '<td class="text-center" style="min-width: 200px; width: 200px;">';
                
                if (isPending) {
                    // Pending records always show action buttons
                    html += '<div class="flex justify-center items-center space-x-2">' +
                            '<button class="btn btn-action btn-action-verify" onclick="openVerifyModal(this,event)">Verify</button>' +
                            '<button class="btn btn-action btn-action-reject" onclick="openRejectModal(this,event)">Reject</button>' +
                            '</div>';
                } else if (isVerified) {
                    // Verified records: Show both action buttons AND status badge (will be filtered by tab)
                    // Action buttons (hidden in Archived tab via CSS class)
                    html += '<div class="flex justify-center items-center space-x-2 for-approval-actions">' +
                            '<button class="btn btn-action btn-action-approve" onclick="openApproveModal(this,event)">Approve</button>' +
                            '<button class="btn btn-action btn-action-reject" onclick="openRejectModal(this,event)">Reject</button>' +
                            '</div>';
                    // Status badge (hidden in For Approval tab via CSS class)
                    html += '<div class="archived-status">';
                    html += '<span class="scms-badge scms-badge--verified">Verified</span>';
                    if (actionDateStr) {
                        html += '<div class="text-xs text-gray-500 mt-1">' + actionDateStr + '</div>';
                    }
                    html += '</div>';
                } else if (isApproved) {
                    // Approved records only show status badge
                    html += '<span class="scms-badge scms-badge--approved">Approved</span>';
                    if (actionDateStr) {
                        html += '<div class="text-xs text-gray-500 mt-1">' + actionDateStr + '</div>';
                    }
                } else if (isRejected) {
                    // Rejected records only show status badge
                    html += '<span class="scms-badge scms-badge--rejected">Rejected</span>';
                    if (actionDateStr) {
                        html += '<div class="text-xs text-gray-500 mt-1">' + actionDateStr + '</div>';
                    }
                }
                
                html += '</td>';
                
                // Add delete button column for archived records only
                html += '<td class="text-center delete-action-cell" style="display:none;">';
                if (isArchived) {
                    html += '<button class="btn btn-ghost btn-sm" onclick="openDeleteModal(' + record.id + ', \'' + (record.student_id || '') + '\', \'' + (record.event_name || '').replace(/'/g, "\\'") + '\', event)" title="Delete Record">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />' +
                            '</svg>' +
                            '</button>';
                }
                html += '</td>';
                
                html += '</tr>';
            });
            
            tbody.innerHTML = html;
            
            // Apply current filter or default to pending
            var activeTab = document.querySelector('.custom-tab-active');
            if (activeTab) {
                filterSubmissions(activeTab.textContent.trim().toLowerCase(), activeTab);
            } else {
                // If no active tab, activate the first tab (Pending)
                var firstTab = document.querySelector('.custom-tab');
                if (firstTab) {
                    firstTab.classList.add('custom-tab-active');
                    filterSubmissions('pending', firstTab);
                }
            }
        }
        
        // Format date helper using Philippine timezone
        function formatDate(dateStr) {
            try {
                // Convert to Philippine timezone
                var date = getPhilippineDate(dateStr);
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                var year = date.getFullYear();
                // Return in MM-DD-YYYY format
                return month + '-' + day + '-' + year;
            } catch (e) {
                console.error('Date formatting error:', e);
                return dateStr;
            }
        }
        
        // Update dashboard statistics
        function updateDashboardStats(submissions) {
            var now = new Date();
            var startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            var endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
            
            var pending = 0, approved = 0, rejected = 0;
            
            submissions.forEach(function(record) {
                var recordDate = new Date(record.updated_at || record.created_at || record.date);
                var isThisMonth = recordDate >= startOfMonth && recordDate <= endOfMonth;
                
                // Count Pending and Verified (admin verified, awaiting super admin decision) as pending
                if (record.status === 'Pending' || record.status === 'Verified') {
                    pending++;
                }
                if (record.status === 'Approved' && isThisMonth) {
                    approved++;
                }
                if (record.status === 'Rejected' && isThisMonth) {
                    rejected++;
                }
            });
            
            document.getElementById('pending-requests-count').textContent = pending;
            document.getElementById('accepted-requests-count').textContent = approved;
            document.getElementById('rejected-requests-count').textContent = rejected;
            
            // Update the donut chart
            updatePendingRequestsChart(pending);
        }

        // Show status modal with filtered records
        window.showStatusModal = function(status) {
            var modal = document.getElementById('status_records_modal');
            var modalTitle = document.getElementById('status-modal-title');
            var modalSubtitle = document.getElementById('status-modal-subtitle');
            var modalIcon = document.getElementById('status-modal-icon');
            var tableBody = document.getElementById('status-modal-table-body');
            var emptyState = document.getElementById('status-modal-empty');
            var totalCount = document.getElementById('status-modal-total');
            var totalHours = document.getElementById('status-modal-hours');
            
            // Filter records by status
            // Verified (Pending for super admin): Show all verified (no date filter)
            // Approved/Rejected: Show only this month (resets at end of month)
            var now = new Date();
            var startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1); // First day of current month
            var endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59); // Last day of current month
            
            var filteredRecords = allSubmissions.filter(function(r) {
                var recordDate = new Date(r.updated_at || r.created_at);
                var isThisMonth = recordDate >= startOfMonth && recordDate <= endOfMonth;
                
                if (status === 'Verified') {
                    // Show ALL verified requests (no time restriction)
                    return r.status === 'Verified';
                } else if (status === 'Approved') {
                    // Show only approved requests from this month
                    return r.status === 'Approved' && isThisMonth;
                } else if (status === 'Rejected') {
                    // Show only rejected requests from this month
                    return r.status === 'Rejected' && isThisMonth;
                }
                return false;
            });
            
            // Update modal header based on status
            var iconSvg = '';
            var bgColorClass = '';
            
            if (status === 'Verified') {
                iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                bgColorClass = 'bg-gradient-pending';
                modalTitle.textContent = filteredRecords.length + ' Verified Requests';
                modalSubtitle.textContent = 'All verified requests awaiting super admin approval';
            } else if (status === 'Approved') {
                iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
                bgColorClass = 'bg-gradient-accepted';
                modalTitle.textContent = filteredRecords.length + ' Approved Requests';
                modalSubtitle.textContent = 'All approved requests this month';
            } else if (status === 'Rejected') {
                iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
                bgColorClass = 'bg-gradient-rejected';
                modalTitle.textContent = filteredRecords.length + ' Rejected Requests';
                modalSubtitle.textContent = 'All rejected requests this month';
            }
            
            modalIcon.innerHTML = iconSvg;
            modalIcon.className = bgColorClass + ' p-3 rounded-full flex items-center justify-center';
            
            // Clear table body
            tableBody.innerHTML = '';
            
            // Calculate total hours
            var hours = filteredRecords.reduce(function(sum, r) {
                return sum + (parseInt(r.hours_rendered) || 0);
            }, 0);
            
            if (filteredRecords.length === 0) {
                // Show empty state with appropriate message
                var emptyText = document.getElementById('status-modal-empty-text');
                if (status === 'Verified') {
                    emptyText.textContent = 'There are no verified requests awaiting approval.';
                } else if (status === 'Approved') {
                    emptyText.textContent = 'There are no approved requests this month.';
                } else if (status === 'Rejected') {
                    emptyText.textContent = 'There are no rejected requests this month.';
                }
                emptyState.classList.remove('hidden');
                tableBody.closest('.overflow-x-auto').classList.add('hidden');
            } else {
                // Hide empty state and show table
                emptyState.classList.add('hidden');
                tableBody.closest('.overflow-x-auto').classList.remove('hidden');
                
                // Populate table
                filteredRecords.forEach(function(rec) {
                    var row = document.createElement('tr');
                    var statusBadge = '';
                    
                    if (rec.status === 'Verified') {
                        statusBadge = '<span class="scms-badge scms-badge--verified">Verified</span>';
                    } else if (rec.status === 'Approved') {
                        statusBadge = '<span class="badge badge-success text-white">Approved</span>';
                    } else if (rec.status === 'Rejected') {
                        statusBadge = '<span class="badge badge-error text-white">Rejected</span>';
                    } else if (rec.status === 'Pending') {
                        statusBadge = '<span class="badge badge-warning text-white">Pending</span>';
                    }
                    
                    row.innerHTML = 
                        '<td class="text-center">' + (rec.student_id || '-') + '</td>' +
                        '<td class="text-center">' + (rec.student_name || '-') + '</td>' +
                        '<td class="text-center">' + formatDate(rec.date) + '</td>' +
                        '<td class="text-center">' + (rec.event_name || '-') + '</td>' +
                        '<td class="text-center">' + (rec.venue || '-') + '</td>' +
                        '<td class="text-center">' + (rec.hours_rendered || 0) + ' hours</td>' +
                        '<td class="text-center">' + statusBadge + '</td>';
                    tableBody.appendChild(row);
                });
            }
            
            // Update summary
            totalCount.textContent = filteredRecords.length;
            totalHours.textContent = hours + ' hours';
            
            // Show modal
            modal.showModal();
        };

        // Open verify modal
        function openVerifyModal(b, e) {
            e.stopPropagation();
            activeRow = b.closest('tr');
            document.getElementById('verify_modal').showModal();
        }

        // Open approve modal
        function openApproveModal(b, e) {
            e.stopPropagation();
            activeRow = b.closest('tr');
            document.getElementById('approve_modal').showModal();
        }

        // Open reject modal
        function openRejectModal(b, e) {
            e.stopPropagation();
            activeRow = b.closest('tr');
            // Reset the reject modal form
            resetRejectModal();
            document.getElementById('reject_modal').showModal();
        }

        // Variables for delete functionality
        var recordToDelete = null;

        // Open delete modal (first confirmation)
        function openDeleteModal(recordId, studentId, eventName, event) {
            if (event) event.stopPropagation();
            
            // Store the record ID for later use
            recordToDelete = recordId;
            
            // Update modal content with record details
            document.getElementById('delete-modal-1-student-id').textContent = studentId;
            document.getElementById('delete-modal-1-event-name').textContent = eventName;
            
            // Show first modal
            document.getElementById('delete_record_modal_1').showModal();
        }

        // Show second delete modal (final warning)
        function showSecondDeleteModal() {
            // Close first modal
            document.getElementById('delete_record_modal_1').close();
            
            // Small delay before opening second modal for better UX
            setTimeout(function() {
                document.getElementById('delete_record_modal_2').showModal();
            }, 150);
        }

        // Confirm and execute record deletion
        async function confirmDeleteRecord() {
            if (!recordToDelete) {
                showToast('No record selected for deletion', 'error');
                return;
            }

            // Close the modal
            document.getElementById('delete_record_modal_2').close();

            // Show loading state
            showToast('Deleting record...', 'info');

            try {
                // Ensure CSRF cookie exists
                await ensureCsrfCookie();

                // Make DELETE request to API
                const response = await fetch(`${BASE_PATH}/super-admin/api/submissions/${recordToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showToast('Record deleted successfully', 'success');
                    
                    // Refresh the submissions table
                    await loadSubmissions();
                    
                    // Reset the recordToDelete
                    recordToDelete = null;
                } else {
                    showToast(data.message || 'Failed to delete record', 'error');
                }
            } catch (error) {
                console.error('Error deleting record:', error);
                showToast('An error occurred while deleting the record', 'error');
            }
        }

        // Open details modal
        function openDetailsModal(r) {
            activeRow = r;
            var s = r.dataset.status;
            var v = r.dataset.venue;
            var org = r.dataset.organization;
            var supervisorName = r.dataset.supervisorName || '-';
            var en = r.cells[2].textContent;
            var dt = r.cells[5].textContent;
            var hr = r.cells[4].textContent;
            var rejectionReason = r.dataset.rejectionReason || '';
            var actionDate = r.dataset.actionDate || '';
            
            document.getElementById('details-event-name').value = en;
            document.getElementById('details-supervisor-name').value = supervisorName;
            document.getElementById('details-venue').value = v;
            document.getElementById('details-date').value = dt;
            document.getElementById('details-hours-rendered').value = hr;
            document.getElementById('details-organizing-committee').value = org;
            
            var ss = document.getElementById('details-status-section');
            var ab = document.getElementById('details-action-buttons');
            var sb = document.getElementById('details-status-badge');
            var ad = document.getElementById('details-action-date');
            var reasonContainer = document.getElementById('details-reason-container');
            var reasonText = document.getElementById('details-reason-text');
            
            sb.innerHTML = '';
            ab.innerHTML = '';
            ad.textContent = ''; // Clear action date
            reasonContainer.classList.add('hidden'); // Hide reason by default
            
            if (s === 'Pending' || s === 'pending') {
                ss.classList.add('hidden');
                ab.classList.remove('hidden');
                ab.innerHTML = '<button class="btn btn-action btn-action-verify flex-1" onclick="handleDetailsVerify()">Verify</button>' +
                               '<button class="btn btn-action btn-action-reject flex-1" onclick="handleDetailsReject()">Reject</button>';
            } else if (s.toLowerCase().includes('for approval')) {
                // Records verified by admin - awaiting super admin approval/rejection
                ss.classList.remove('hidden');
                ab.classList.remove('hidden');
                
                // Show Verified status with action date
                sb.textContent = 'Verified';
                sb.className = 'scms-badge scms-badge--verified';
                if (actionDate) {
                    ad.textContent = actionDate;
                }
                
                ab.innerHTML = '<button class="btn btn-action btn-action-approve flex-1" onclick="handleDetailsApprove()">Approve</button>' +
                               '<button class="btn btn-action btn-action-reject flex-1" onclick="handleDetailsReject()">Reject</button>';
            } else {
                // Archived records - show final status (Verified, Approved or Rejected by super admin)
                ss.classList.remove('hidden');
                ab.classList.add('hidden');
                var as = r.dataset.archiveStatus;
                
                if (as === 'Approved') {
                    sb.textContent = 'Approved';
                    sb.className = 'scms-badge scms-badge--approved';
                    if (actionDate) {
                        ad.textContent = actionDate;
                    }
                } else if (as === 'Rejected') {
                    sb.textContent = 'Rejected';
                    sb.className = 'scms-badge scms-badge--rejected';
                    if (actionDate) {
                        ad.textContent = actionDate;
                    }
                    
                    // Show rejection reason if it exists
                    if (rejectionReason) {
                        // Decode HTML entities
                        var decodedReason = rejectionReason
                            .replace(/&quot;/g, '"')
                            .replace(/&#39;/g, "'")
                            .replace(/&amp;/g, '&')
                            .replace(/&lt;/g, '<')
                            .replace(/&gt;/g, '>');
                        
                        reasonText.textContent = decodedReason;
                        reasonContainer.classList.remove('hidden');
                    }
                } else if (as === 'Verified') {
                    sb.textContent = 'Verified';
                    sb.className = 'scms-badge scms-badge--verified';
                    if (actionDate) {
                        ad.textContent = actionDate;
                    }
                }
            }
            
            document.getElementById('submission_details_modal').showModal();
        }

        // Handle verify from details modal
        function handleDetailsVerify() {
            document.getElementById('submission_details_modal').close();
            document.getElementById('verify_modal').showModal();
        }

        // Handle approve from details modal
        function handleDetailsApprove() {
            document.getElementById('submission_details_modal').close();
            document.getElementById('approve_modal').showModal();
        }

        // Handle reject from details modal
        function handleDetailsReject() {
            document.getElementById('submission_details_modal').close();
            document.getElementById('reject_modal').showModal();
        }

        // Initialize theme toggle
        function initThemeToggle() {
            try {
                var tg = document.getElementById('theme-toggle');
                var lb = document.getElementById('theme-label');
                var ap = function(m) {
                    console.log('Super Admin - Applying theme:', m);
                    document.documentElement.setAttribute('data-theme', m);
                    try {
                        localStorage.setItem('scms_superadmin_theme', m);
                        console.log('Super Admin - Saved theme to localStorage:', m);
                        // Verify it was saved
                        var verify = localStorage.getItem('scms_superadmin_theme');
                        console.log('Super Admin - Verified localStorage value:', verify);
                    } catch(e) {
                        console.error('Super Admin - Error saving theme:', e);
                    }
                    if (lb) lb.textContent = (m === 'dark') ? 'Dark theme' : 'Light theme';
                    if (tg) tg.checked = (m === 'dark');
                };
                
                var sv = 'light';
                try {
                    sv = (localStorage.getItem('scms_superadmin_theme') === 'dark') ? 'dark' : 'light';
                    console.log('Super Admin - Initial theme from localStorage:', sv);
                } catch(e) {
                    console.error('Super Admin - Error reading theme:', e);
                }
                
                ap(sv);
                
                if (tg) {
                    tg.addEventListener('change', function() {
                        ap(tg.checked ? 'dark' : 'light');
                        // Reinitialize charts with new theme colors
                        if (typeof initYearlyCharts === 'function') {
                            initYearlyCharts();
                            // Re-update with current data if available
                            if (typeof submissions !== 'undefined' && submissions) {
                                updateYearlyCharts(submissions);
                            }
                        }
                        // Regenerate activity calendar with new theme colors
                        if (typeof generateActivityCalendar === 'function') {
                            generateActivityCalendar();
                        }
                    });
                }
            } catch(_) {}
        }

        // Initialize Pending Requests Donut Chart
        var pendingRequestsChartInstance = null;
        function initPendingRequestsChart(pendingCount) {
            var canvas = document.getElementById('pendingRequestsChart');
            if (!canvas) return;
            
            var ctx = canvas.getContext('2d');
            
            // Destroy existing chart if any
            if (pendingRequestsChartInstance) {
                pendingRequestsChartInstance.destroy();
            }
            
            // Default to 0 if not provided
            if (typeof pendingCount === 'undefined') {
                pendingCount = 0;
            }
            
            var totalCapacity = 50; // Example total capacity
            
            pendingRequestsChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [pendingCount, Math.max(0, totalCapacity - pendingCount)],
                        backgroundColor: ['#FFFFFF', 'rgba(255, 255, 255, 0.2)'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
            
            // Update label
            document.getElementById('pending-requests-label').textContent = pendingCount;
        }

        // Update chart with new data
        function updatePendingRequestsChart(pendingCount) {
            initPendingRequestsChart(pendingCount);
        }

        // Activity Calendar Variables using Philippine timezone
        var currentCalendarYear = getPhilippineDate().getFullYear();
        var activityDataCache = {};

        // Generate Activity Calendar (January to December for selected year)
        function generateActivityCalendar() {
            var container = document.getElementById('activity-calendar');
            if (!container) return;
            
            // Update year display
            document.getElementById('calendar-year').textContent = currentCalendarYear;
            
            // Disable next button if viewing current year
            var nextBtn = document.getElementById('next-year-btn');
            var currentYear = getPhilippineDate().getFullYear();
            if (nextBtn) {
                nextBtn.disabled = currentCalendarYear >= currentYear;
                if (currentCalendarYear >= currentYear) {
                    nextBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
            
            // Create date range for the selected year (January 1 to December 31)
            var startDate = new Date(currentCalendarYear, 0, 1); // January 1
            var endDate = new Date(currentCalendarYear, 11, 31); // December 31
            var today = new Date();
            
            // Load activity data from API
            loadActivityDataForYear(currentCalendarYear, function(activityData) {
                renderCalendar(startDate, endDate, today, activityData);
            });
        }
        
        // Load activity data from API for a specific year
        function loadActivityDataForYear(year, callback) {
            // Check cache first
            if (activityDataCache[year]) {
                callback(activityDataCache[year]);
                return;
            }
            
            fetch(`${BASE_PATH}/super-admin/api/activity-calendar?year=` + year, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch activity data');
                return response.json();
            })
            .then(result => {
                if (result.success && result.data) {
                    activityDataCache[year] = result.data;
                    callback(result.data);
                } else {
                    console.warn('Invalid activity data format');
                    callback({});
                }
            })
            .catch(error => {
                console.error('Failed to load activity data:', error);
                callback({});
            });
        }
        
        // Render the calendar
        function renderCalendar(startDate, endDate, today, activityData) {
            var container = document.getElementById('activity-calendar');
            if (!container) return;
            
            // Use Philippines timezone (UTC+8)
            var philippinesOffset = 8 * 60; // 8 hours in minutes
            var localOffset = today.getTimezoneOffset();
            var offsetDiff = philippinesOffset + localOffset;
            var philippinesToday = new Date(today.getTime() + (offsetDiff * 60 * 1000));
            
            function getColor(level) {
                // Detect dark theme
                const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
                
                if (isDarkTheme) {
                    // Dark theme - purple palette (5 levels: 0-4)
                    if (level === 0) return '#2c354aff'; // No activity
                    if (level === 1) return '#411b7a'; // 1-2 updates
                    if (level === 2) return '#5A3590'; // 3-4 updates
                    if (level === 3) return '#804ED6'; // 5-6 updates
                    return '#A770FF'; // 7+ updates (level 4)
                } else {
                    // Light theme - original colors (5 levels: 0-4)
                    if (level === 0) return '#E5E7EB'; // No activity
                    if (level === 1) return '#E5D4FF'; // 1-2 updates
                    if (level === 2) return '#C9A9FF'; // 3-4 updates
                    if (level === 3) return '#A475FF'; // 5-6 updates
                    return '#6D28D9'; // 7+ updates (level 4)
                }
            }
            
            var html = '<div class="flex gap-2">';
            
            // Day labels column - use rem values to match Tailwind exactly
            // h-3 = 0.75rem (12px), mb-1 = 0.25rem (4px)
            html += '<div class="flex flex-col text-xs text-text-muted pr-2" style="padding-top: 18px;">';
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem;"></div>'; // Sunday - Row 0
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem; line-height: 0.75rem;">Mon</div>'; // Monday - Row 1
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem;"></div>'; // Tuesday - Row 2
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem; line-height: 0.75rem;">Wed</div>'; // Wednesday - Row 3
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem;"></div>'; // Thursday - Row 4
            html += '<div style="height: 0.75rem; margin-bottom: 0.25rem; line-height: 0.75rem;">Fri</div>'; // Friday - Row 5
            html += '<div style="height: 0.75rem;"></div>'; // Saturday - Row 6
            html += '</div>';
            
            // Calendar grid container
            html += '<div class="flex-1 overflow-x-auto">';
            html += '<div class="flex gap-4">'; // Gap between months
            
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Loop through each month of the year
            for (var monthIndex = 0; monthIndex < 12; monthIndex++) {
                // Skip future months using Philippines time
                var currentMonth = philippinesToday.getMonth();
                if (monthIndex > currentMonth) {
                    continue; // Skip this month
                }
                
                // Build calendar dates
                var monthStart = new Date(currentCalendarYear, monthIndex, 1);
                var monthEnd = new Date(currentCalendarYear, monthIndex + 1, 0); // Last day of month
                
                // Find the Sunday before or on the first day of the month
                var firstSunday = new Date(monthStart);
                firstSunday.setDate(firstSunday.getDate() - firstSunday.getDay());
                
                // Build weeks for this month
                var monthWeeks = [];
                var currentWeekStart = new Date(firstSunday);
                
                // Continue until we've covered the entire month
                while (currentWeekStart <= monthEnd) {
                    var week = [];
                    for (var dayOffset = 0; dayOffset < 7; dayOffset++) {
                        var dayDate = new Date(currentWeekStart.getFullYear(), currentWeekStart.getMonth(), currentWeekStart.getDate() + dayOffset);
                        week.push(dayDate);
                    }
                    monthWeeks.push(week);
                    currentWeekStart.setDate(currentWeekStart.getDate() + 7);
                    
                    // Stop if the week is entirely in the next month
                    if (week[0].getMonth() > monthIndex && week[6].getMonth() > monthIndex) {
                        break;
                    }
                }
                
                // Render this month
                html += '<div class="inline-flex flex-col">';
                
                // Month label
                html += '<div class="text-xs font-semibold text-text-muted text-center mb-2 h-4">' + months[monthIndex] + '</div>';
                
                // Grid rows for this month
                for (var dayIndex = 0; dayIndex < 7; dayIndex++) {
                    // Remove mb-1 from last row (Saturday) to match day labels
                    var rowClass = dayIndex === 6 ? 'flex gap-1' : 'flex gap-1 mb-1';
                    html += '<div class="' + rowClass + '">';
                    
                    for (var w = 0; w < monthWeeks.length; w++) {
                        var date = monthWeeks[w][dayIndex];
                        
                        // Create date string for data lookup
                        var year = date.getFullYear();
                        var month = String(date.getMonth() + 1).padStart(2, '0');
                        var day = String(date.getDate()).padStart(2, '0');
                        var dateStr = year + '-' + month + '-' + day;
                        
                        var level = activityData[dateStr] || 0;
                        
                        // Check if this date is today in Philippines timezone
                        var isToday = date.getFullYear() === philippinesToday.getFullYear() && 
                                     date.getMonth() === philippinesToday.getMonth() && 
                                     date.getDate() === philippinesToday.getDate();
                        var isFuture = date > philippinesToday;
                        var isInYear = date.getFullYear() === currentCalendarYear;
                        var isInThisMonth = date.getMonth() === monthIndex;
                        
                        // Set color - use gray for future dates or dates not in this month, otherwise use activity color
                        var color;
                        if (isFuture || !isInYear || !isInThisMonth) {
                            // Use theme-aware gray color for future/out of range dates
                            const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
                            color = isDarkTheme ? '#303d54ff' : '#E5E7EB';
                        } else {
                            color = getColor(level);
                        }
                        
                        var title = dateStr + ': ' + level + ' update' + (level !== 1 ? 's' : '');
                        
                        var borderClass = isToday ? 'ring-2 ring-primary-purple ring-offset-1' : '';
                        var opacity = (isFuture || !isInYear || !isInThisMonth) ? 'opacity-30' : '';
                        var cursor = (!isFuture && isInYear && isInThisMonth && level > 0) ? 'cursor-pointer' : 'cursor-default';
                        
                        html += '<div class="w-3 h-3 rounded-sm transition-all hover:ring-2 hover:ring-primary-purple hover:ring-offset-1 ' + 
                                cursor + ' ' + borderClass + ' ' + opacity + '" ' +
                                'style="background-color: ' + color + ';" ' +
                                'title="' + title + '" ' +
                                'data-date="' + dateStr + '" ' +
                                'data-count="' + level + '" ' +
                                ((!isFuture && isInYear && isInThisMonth && level > 0) ? 'onclick="showActivityDetails(\'' + dateStr + '\')"' : '') +
                                '></div>';
                    }
                    
                    html += '</div>';
                }
                
                html += '</div>'; // End month column
            }
            
            html += '</div>'; // End months flex container
            html += '</div>'; // End overflow container
            html += '</div>'; // End main flex container
            
            container.innerHTML = html;
            
            // Scroll to the right after rendering
            scrollCalendarToRight();
        }
        
        // Scroll calendar to the rightmost position
        function scrollCalendarToRight() {
            var container = document.getElementById('activity-calendar');
            if (container) {
                // Find the inner overflow container
                var innerContainer = container.querySelector('.overflow-x-auto');
                var targetContainer = innerContainer || container;
                
                // Use setTimeout to ensure DOM is fully rendered
                setTimeout(function() {
                    targetContainer.scrollLeft = targetContainer.scrollWidth;
                }, 100);
            }
        }
        
        // Change calendar year
        function changeCalendarYear(delta) {
            var currentYear = new Date().getFullYear();
            var newYear = currentCalendarYear + delta;
            
            // Don't allow future years
            if (newYear > currentYear) return;
            
            currentCalendarYear = newYear;
            generateActivityCalendar();
        }
        
        // Make function globally accessible
        window.changeCalendarYear = changeCalendarYear;
        
        // Show activity details for a specific date
        function showActivityDetails(dateStr) {
            var modal = document.getElementById('activity_details_modal');
            var dateDisplay = document.getElementById('activity-date');
            var content = document.getElementById('activity-details-content');
            var loading = document.getElementById('activity-loading');
            var noData = document.getElementById('activity-no-data');
            
            if (!modal || !dateDisplay || !content) return;
            
            // Format date for display
            var date = new Date(dateStr + 'T00:00:00');
            dateDisplay.textContent = date.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
            
            // Show modal and loading state
            modal.showModal();
            content.innerHTML = '';
            content.classList.add('hidden');
            loading.classList.remove('hidden');
            noData.classList.add('hidden');
            
            // Fetch activity details for this date
            fetch(`${BASE_PATH}/super-admin/api/activity-details?date=` + dateStr, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch activity details');
                return response.json();
            })
            .then(result => {
                loading.classList.add('hidden');
                
                console.log('Activity details result:', result);
                
                if (result.success && result.data && result.data.length > 0) {
                    content.classList.remove('hidden');
                    var html = '';
                    
                    result.data.forEach(function(activity) {
                        console.log('Processing activity:', activity);
                        
                        // Parse the timestamp without timezone conversion
                        var timestamp = activity.created_at;
                        var time = '';
                        
                        // If timestamp includes timezone info, parse it carefully
                        if (timestamp) {
                            try {
                                // Extract just the time portion (HH:MM:SS) from the timestamp
                                var timeParts = timestamp.match(/(\d{2}):(\d{2}):(\d{2})/);
                                if (timeParts) {
                                    var hour = parseInt(timeParts[1]);
                                    var minute = timeParts[2];
                                    var ampm = hour >= 12 ? 'PM' : 'AM';
                                    hour = hour % 12 || 12; // Convert to 12-hour format
                                    time = hour + ':' + minute + ' ' + ampm;
                                } else {
                                    time = new Date(timestamp).toLocaleTimeString('en-US', { 
                                        hour: '2-digit', 
                                        minute: '2-digit' 
                                    });
                                }
                            } catch (e) {
                                time = '00:00 AM';
                            }
                        }
                        
                        var actionBadge = '';
                        var actionText = '';
                        if (activity.action === 'verified' || activity.action === 'verified_submission') {
                            actionBadge = '<span class="badge badge-sm badge-info text-white">Verified</span>';
                            actionText = 'Verified';
                        } else if (activity.action === 'approved' || activity.action === 'approved_submission') {
                            actionBadge = '<span class="badge badge-sm badge-success text-white">Approved</span>';
                            actionText = 'Approved';
                        } else if (activity.action === 'rejected' || activity.action === 'rejected_submission') {
                            actionBadge = '<span class="badge badge-sm badge-error text-white">Rejected</span>';
                            actionText = 'Rejected';
                        }
                        
                        html += '<div class="bg-base-100 rounded-lg p-4 border border-base-300 hover:shadow-md transition-shadow">';
                        html += '<div class="flex items-center justify-between mb-2">';
                        html += '<span class="text-xs font-medium text-text-muted">' + time + '</span>';
                        html += '<div class="flex flex-col items-end gap-1">';
                        html += actionBadge;
                        if (activity.admin_name) {
                            html += '<span class="text-xs text-text-muted">' + actionText + ' by Admin ' + activity.admin_name + '</span>';
                        }
                        html += '</div>';
                        html += '</div>';
                        
                        html += '<p class="text-sm font-semibold text-text-header mb-2">' + (activity.description || 'Activity recorded') + '</p>';
                        
                        if (activity.student_id || activity.student_name) {
                            html += '<div class="flex items-center gap-2 text-sm text-text-muted">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
                            html += '</svg>';
                            html += '<span>';
                            if (activity.student_id) {
                                html += '<span class="font-medium">' + activity.student_id + '</span>';
                            }
                            if (activity.student_name) {
                                html += (activity.student_id ? ' - ' : '') + activity.student_name;
                            }
                            html += '</span>';
                            html += '</div>';
                        }
                        
                        if (activity.venue) {
                            html += '<div class="flex items-center gap-2 text-sm text-text-muted mt-1">';
                            html += '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />';
                            html += '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
                            html += '</svg>';
                            html += '<span>' + activity.venue + '</span>';
                            html += '</div>';
                        }
                        
                        html += '</div>';
                    });
                    
                    console.log('Generated HTML:', html);
                    content.innerHTML = html;
                } else {
                    noData.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Failed to load activity details:', error);
                loading.classList.add('hidden');
                noData.classList.remove('hidden');
            });
        }
        
        // Make function globally accessible
        window.showActivityDetails = showActivityDetails;
        
        // Remove old sample data function
        // function generateSampleActivityData() - REMOVED

        // ========== STUDENTS MANAGEMENT ==========
        var allStudents = [];
        var lastStudentsData = null;
        var isLoadingStudents = false;
        var studentsSortColumn = null;
        var studentsSortDirection = 'asc';
        
        // Student sorting function
        function sortStudents(column) {
            // Toggle direction if same column, otherwise default to ascending
            if (studentsSortColumn === column) {
                studentsSortDirection = studentsSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                studentsSortColumn = column;
                studentsSortDirection = 'asc';
            }
            
            // Reset all sort icons
            document.querySelectorAll('[id$="-sort-icon"]').forEach(icon => {
                if (icon.id.includes('student-id-sort') || 
                    icon.id.includes('full-name-sort') || 
                    icon.id.includes('email-sort') || 
                    icon.id.includes('email-verified-sort') || 
                    icon.id.includes('approved-hours-sort') || 
                    icon.id.includes('status-sort')) {
                    icon.textContent = '⇅';
                }
            });
            
            // Update current column icon
            var iconId = column + '-sort-icon';
            var icon = document.getElementById(iconId);
            if (icon) {
                icon.textContent = studentsSortDirection === 'asc' ? '↑' : '↓';
            }
            
            // Sort the students array
            var sortedStudents = [...allStudents].sort((a, b) => {
                let aVal, bVal;
                
                switch(column) {
                    case 'student-id':
                        aVal = parseInt(a.id) || 0;
                        bVal = parseInt(b.id) || 0;
                        break;
                    case 'full-name':
                        aVal = (a.name || '').toLowerCase();
                        bVal = (b.name || '').toLowerCase();
                        break;
                    case 'email':
                        aVal = (a.email || '').toLowerCase();
                        bVal = (b.email || '').toLowerCase();
                        break;
                    case 'email-verified':
                        aVal = a.email_verified_at ? 1 : 0;
                        bVal = b.email_verified_at ? 1 : 0;
                        break;
                    case 'approved-hours':
                        aVal = parseInt(a.approved_hours) || 0;
                        bVal = parseInt(b.approved_hours) || 0;
                        break;
                    case 'status':
                        aVal = (a.status || 'active').toLowerCase();
                        bVal = (b.status || 'active').toLowerCase();
                        break;
                    default:
                        return 0;
                }
                
                if (studentsSortDirection === 'asc') {
                    return aVal > bVal ? 1 : aVal < bVal ? -1 : 0;
                } else {
                    return aVal < bVal ? 1 : aVal > bVal ? -1 : 0;
                }
            });
            
            renderStudents(sortedStudents);
        }
        
        function loadStudents(showLoading = true) {
            if (isLoadingStudents) {
                console.log('Already loading students, skipping...');
                return Promise.resolve();
            }
            
            var tbody = document.getElementById('students-table-body');
            if (showLoading && !lastStudentsData) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8"><span class="loading loading-spinner loading-lg text-primary-purple"></span><p class="mt-2 text-text-muted">Loading students...</p></td></tr>';
            }
            
            isLoadingStudents = true;
            var timestamp = new Date().getTime();
            
            console.log('Fetching students from API...', `${BASE_PATH}/super-admin/api/students`);
            
            return fetch(`${BASE_PATH}/super-admin/api/students?_=${timestamp}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                },
                credentials: 'include'
            })
            .then(async (r) => {
                console.log('Received response:', r.status, r.statusText);
                const ct = r.headers.get('content-type') || '';
                console.log('Content-Type:', ct);
                
                if (!r.ok) {
                    console.error('Response not OK:', r.status);
                    throw new Error(`HTTP ${r.status}: ${r.statusText}`);
                }
                
                if (!ct.includes('application/json')) {
                    console.warn('loadStudents: non-JSON response, session may have expired');
                    const text = await r.text();
                    console.log('Response body:', text.substring(0, 500));
                    return Promise.reject(new Error('Non-JSON response'));
                }
                
                return r.json();
            })
            .then((data) => {
                isLoadingStudents = false;
                console.log('Received data:', data);
                
                // Check for authentication error
                if (data.unauthenticated) {
                    console.warn('Session expired while loading students, silently ignoring');
                    return;
                }
                
                if (data.success !== false && data.students) {
                    console.log('Processing', data.students.length, 'students');
                    lastStudentsData = data.students;
                    allStudents = data.students;
                    renderStudents(allStudents);
                } else {
                    throw new Error(data.message || 'Failed to load students');
                }
            })
            .catch((err) => {
                isLoadingStudents = false;
                console.error('Failed to load students', err);
                
                // Only show error if we have no cached data AND it's initial load
                if (!lastStudentsData && showLoading) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">Failed to load students. Please refresh.</td></tr>';
                }
                // If we have cached data, silently keep showing it
            });
        }
        
        function renderStudents(students) {
            var tbody = document.getElementById('students-table-body');
            
            if (!students || students.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-text-muted">No students found.</td></tr>';
                return;
            }

            // Update verified / unverified counts for quick-stats (if present)
            try {
                var verifiedCount = students.filter(function(s) {
                    var st = (s.status || '').toString().toLowerCase();
                    return !!(s.email_verified_at || s.is_verified || s.verified || st === 'verified');
                }).length;
                var totalCount = students.length;
                var unverifiedCount = totalCount - verifiedCount;
                var verifiedEl = document.getElementById('students-verified-count');
                var unverifiedEl = document.getElementById('students-unverified-count');
                if (verifiedEl) verifiedEl.textContent = verifiedCount;
                if (unverifiedEl) unverifiedEl.textContent = unverifiedCount;
                // Active / Inactive counts
                var activeCount = students.filter(function(s) { return (s.status || '').toString().toLowerCase() === 'active'; }).length;
                var inactiveCount = totalCount - activeCount;
                var activeEl = document.getElementById('students-active-count');
                var inactiveEl = document.getElementById('students-inactive-count');
                if (activeEl) activeEl.textContent = activeCount;
                if (inactiveEl) inactiveEl.textContent = inactiveCount;
            } catch (e) {
                console.error('Error updating student counts', e);
            }
            
            var html = '';
            students.forEach(function(student) {
                var status = (student.status || 'active').toString().toLowerCase();
                var statusBadge = '';
                
                if (status === 'active') {
                    statusBadge = '<span class="badge badge-success text-white">Active</span>';
                } else {
                    // Calculate days remaining for inactive accounts
                    var daysRemaining = '';
                    if (student.inactive_at) {
                        var inactiveDate = new Date(student.inactive_at);
                        var deletionDate = new Date(inactiveDate);
                        deletionDate.setDate(deletionDate.getDate() + 7);
                        
                        var now = new Date();
                        var timeDiff = deletionDate - now;
                        var daysLeft = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                        var hoursLeft = Math.ceil(timeDiff / (1000 * 60 * 60));
                        
                        if (daysLeft > 0) {
                            daysRemaining = '<div class="text-xs text-red-600 font-semibold mt-1">' + 
                                          daysLeft + ' day' + (daysLeft !== 1 ? 's' : '') + ' remaining</div>';
                        } else if (hoursLeft > 0) {
                            daysRemaining = '<div class="text-xs text-red-600 font-semibold mt-1">' + 
                                          hoursLeft + ' hour' + (hoursLeft !== 1 ? 's' : '') + ' remaining</div>';
                        } else {
                            daysRemaining = '<div class="text-xs text-red-700 font-bold mt-1">Deletion pending</div>';
                        }
                    }
                    
                    statusBadge = '<div class="flex flex-col items-center">' +
                                '<span class="badge badge-error text-white">Inactive</span>' +
                                daysRemaining +
                                '</div>';
                }
                
                var emailVerified = student.email_verified_at;
                var verifiedBadge = emailVerified 
                    ? '<span class="badge badge-success text-white">✓ Verified</span>' 
                    : '<span class="badge badge-warning text-white">X Not Verified</span>';
                
                var approvedHours = student.approved_hours || 0;
                var hoursDisplay = '<span class="font-semibold text-primary-purple">' + approvedHours + ' hours</span>';
                
                html += '<tr class="hover cursor-pointer" onclick="openStudentViewModal(' + student.id + ')">' +
                        '<td class="text-center" style="min-width: 90px; width: 90px; white-space: nowrap;">' + (student.student_id || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 160px; width: 160px; white-space: nowrap;">' + (student.name || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 200px; width: 200px; white-space: nowrap;">' + (student.email || 'â€”') + '</td>' +
                        '<td class="text-center" style="min-width: 110px; width: 110px; white-space: nowrap;">' + verifiedBadge + '</td>' +
                        '<td class="text-center" style="min-width: 120px; width: 120px; white-space: nowrap;">' + hoursDisplay + '</td>' +
                        '<td class="text-center" style="min-width: 80px; width: 80px;">' + statusBadge + '</td>' +
                        '<td class="text-center" style="min-width: 100px; width: 100px;">' +
                        '<button class="btn btn-sm bg-primary-purple hover:bg-primary-purple-hover text-white" onclick="event.stopPropagation(); openStudentEditModal(' + student.id + ')">Edit</button>' +
                        '</td></tr>';
            });
            
            tbody.innerHTML = html;
        }
        
        async function refreshStudents() {
            var refreshBtn = document.getElementById('refresh-students-btn');
            var refreshIcon = document.getElementById('refresh-students-icon');
            
            if (refreshIcon) {
                refreshIcon.classList.add('animate-spin');
            }
            
            if (refreshBtn) {
                refreshBtn.disabled = true;
            }
            
            // Force fresh load by clearing cached data and loading with timestamp
            console.log('Refreshing students list...');
            
            // Clear the loading flag to ensure fetch happens
            isLoadingStudents = false;
            
            try {
                // Load students with fresh data and wait for it to complete
                await loadStudents(true);
                showToast('Students list refreshed successfully', 'success');
            } catch (error) {
                console.error('Error refreshing students:', error);
                showToast('Failed to refresh students', 'error');
            } finally {
                if (refreshIcon) {
                    refreshIcon.classList.remove('animate-spin');
                }
                if (refreshBtn) {
                    refreshBtn.disabled = false;
                }
                console.log('Students list refresh complete');
            }
        }
        
        function openStudentEditModal(userId) {
            var student = allStudents.find(s => s.id === userId);
            if (!student) {
                showToast('Student not found', 'error');
                return;
            }
            
            document.getElementById('edit-student-user-id').value = student.id;
            document.getElementById('edit-student-name').value = student.name || '';
            document.getElementById('edit-student-id').value = student.student_id || '';
            document.getElementById('edit-student-email').value = student.email || '';
            document.getElementById('edit-student-approved-hours').value = (student.approved_hours || 0) + ' hours';
            document.getElementById('edit-student-status').value = student.status || 'active';
            
            // Update inactive warning box
            updateInactiveWarning(student);
            
            document.getElementById('student_edit_modal').showModal();
        }

        // Open Student View Modal (Read-Only)
        function openStudentViewModal(userId) {
            var student = allStudents.find(s => s.id === userId);
            if (!student) {
                showToast('Student not found', 'error');
                return;
            }
            
            // Store current student ID for edit transition
            window.currentViewingStudentId = userId;
            
            // Populate view modal with student data
            document.getElementById('view-student-name').textContent = student.name || 'â€”';
            document.getElementById('view-student-id').textContent = student.student_id || 'â€”';
            document.getElementById('view-student-email').textContent = student.email || 'â€”';
            document.getElementById('view-student-approved-hours').textContent = (student.approved_hours || 0) + ' hours';
            
            // Email verified badge
            var emailVerified = student.email_verified_at;
            var verifiedBadge = emailVerified 
                ? '<span class="badge badge-success text-white">✓ Verified</span>' 
                : '<span class="badge badge-warning text-white">X Not Verified</span>';
            document.getElementById('view-student-email-verified').innerHTML = verifiedBadge;
            
            // Status badge with countdown if inactive
            var status = student.status || 'active';
            var statusHtml = '';
            
            if (status === 'active') {
                statusHtml = '<span class="badge badge-success text-white">Active</span>';
            } else {
                var daysRemaining = '';
                if (student.inactive_at) {
                    var inactiveDate = new Date(student.inactive_at);
                    var deletionDate = new Date(inactiveDate);
                    deletionDate.setDate(deletionDate.getDate() + 7);
                    
                    var now = new Date();
                    var timeDiff = deletionDate - now;
                    var daysLeft = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                    var hoursLeft = Math.ceil(timeDiff / (1000 * 60 * 60));
                    
                    if (daysLeft > 0) {
                        daysRemaining = '<div class="text-xs text-red-600 font-semibold mt-1">' + 
                                      daysLeft + ' day' + (daysLeft !== 1 ? 's' : '') + ' remaining</div>';
                    } else if (hoursLeft > 0) {
                        daysRemaining = '<div class="text-xs text-red-600 font-semibold mt-1">' + 
                                      hoursLeft + ' hour' + (hoursLeft !== 1 ? 's' : '') + ' remaining</div>';
                    } else {
                        daysRemaining = '<div class="text-xs text-red-700 font-bold mt-1">Deletion pending</div>';
                    }
                }
                
                statusHtml = '<div class="flex flex-col items-start">' +
                            '<span class="badge badge-error text-white">Inactive</span>' +
                            daysRemaining +
                            '</div>';
            }
            document.getElementById('view-student-status').innerHTML = statusHtml;
            
            // Show modal
            document.getElementById('student_view_modal').showModal();
        }
        
        // Transition from View Modal to Edit Modal
        function openEditFromView() {
            // Close view modal
            document.getElementById('student_view_modal').close();
            
            // Open edit modal with the same student
            if (window.currentViewingStudentId) {
                openStudentEditModal(window.currentViewingStudentId);
            }
        }
        
        // Update inactive account warning in edit modal
        function updateInactiveWarning(student) {
            var warningBox = document.getElementById('inactive-warning-box');
            var countdownText = document.getElementById('inactive-countdown-text');
            var statusSelect = document.getElementById('edit-student-status');
            
            function showWarning() {
                if (student.status === 'inactive' && student.inactive_at) {
                    var inactiveDate = new Date(student.inactive_at);
                    var deletionDate = new Date(inactiveDate);
                    deletionDate.setDate(deletionDate.getDate() + 7);
                    
                    var now = new Date();
                    var timeDiff = deletionDate - now;
                    var daysLeft = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
                    var hoursLeft = Math.ceil(timeDiff / (1000 * 60 * 60));
                    
                    var countdownMessage = '';
                    if (daysLeft > 0) {
                        countdownMessage = 'This account will be permanently deleted in <strong>' + 
                                         daysLeft + ' day' + (daysLeft !== 1 ? 's' : '') + '</strong> on ' +
                                         deletionDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    } else if (hoursLeft > 0) {
                        countdownMessage = 'This account will be permanently deleted in <strong>' + 
                                         hoursLeft + ' hour' + (hoursLeft !== 1 ? 's' : '') + '</strong>';
                    } else {
                        countdownMessage = 'This account is <strong>pending deletion</strong>';
                    }
                    
                    countdownText.innerHTML = countdownMessage;
                    warningBox.classList.remove('hidden');
                } else {
                    warningBox.classList.add('hidden');
                }
            }
            
            // Show/hide warning initially
            showWarning();
            
            // Update warning when status changes
            statusSelect.removeEventListener('change', statusSelect._warningHandler);
            statusSelect._warningHandler = function() {
                student.status = statusSelect.value;
                showWarning();
            };
            statusSelect.addEventListener('change', statusSelect._warningHandler);
        }
        
        // Open delete student confirmation modal
        function openDeleteStudentModal() {
            var userId = document.getElementById('edit-student-user-id').value;
            var student = allStudents.find(s => s.id === parseInt(userId));
            
            if (!student) {
                showToast('Student not found', 'error');
                return;
            }
            
            // Populate delete confirmation modal with student details
            document.getElementById('delete-student-name-display').textContent = student.name || 'â€”';
            document.getElementById('delete-student-id-display').textContent = student.student_id || 'â€”';
            document.getElementById('delete-student-email-display').textContent = student.email || 'â€”';
            
            // Close edit modal and open delete confirmation modal
            document.getElementById('student_edit_modal').close();
            document.getElementById('delete_student_modal').showModal();
        }
        
        // Handle delete student confirmation
        document.getElementById('confirm-delete-student-btn').addEventListener('click', async function() {
            var userId = document.getElementById('edit-student-user-id').value;
            
            if (!userId) {
                showToast('Student ID not found', 'error');
                return;
            }
            
            // Disable button to prevent double submission
            this.disabled = true;
            this.textContent = 'Deleting...';
            
            try {
                const response = await fetch(`${BASE_PATH}/super-admin/api/students/${userId}`, {
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
                
                if (!response.ok) {
                    throw new Error(data.message || 'Failed to delete student');
                }
                
                showToast(data.message || 'Student account deleted successfully', 'success');
                document.getElementById('delete_student_modal').close();
                loadStudents(false);
            } catch (error) {
                console.error('Error deleting student:', error);
                showToast(error.message || 'Failed to delete student account', 'error');
            } finally {
                // Re-enable button
                this.disabled = false;
                this.textContent = 'Yes, Delete Account';
            }
        });
        
        // Handle student search
        var studentsSearchInput = document.getElementById('students-search');
        if (studentsSearchInput) {
            studentsSearchInput.addEventListener('input', function() {
                var query = this.value.toLowerCase().trim();
                
                if (!query) {
                    renderStudents(allStudents);
                    return;
                }
                
                var filtered = allStudents.filter(function(student) {
                    return (student.name || '').toLowerCase().includes(query) ||
                           (student.student_id || '').toLowerCase().includes(query) ||
                           (student.email || '').toLowerCase().includes(query);
                });
                
                renderStudents(filtered);
            });
        }
        
        // Handle student edit form submission
        var studentEditForm = document.getElementById('student-edit-form');
        if (studentEditForm) {
            studentEditForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                var userId = document.getElementById('edit-student-user-id').value;
                var name = document.getElementById('edit-student-name').value.trim();
                var studentId = document.getElementById('edit-student-id').value.trim();
                var email = document.getElementById('edit-student-email').value.trim();
                var status = document.getElementById('edit-student-status').value;
                
                // Validation
                if (!name || name.length < 3) {
                    showToast('Name must be at least 3 characters', 'error');
                    return;
                }
                
                if (!studentId || studentId.length < 3) {
                    showToast('Student ID is required', 'error');
                    return;
                }
                
                if (!email || !email.includes('@')) {
                    showToast('Please enter a valid email', 'error');
                    return;
                }
                
                var requestBody = {
                    name: name,
                    student_id: studentId,
                    email: email,
                    status: status
                };
                
                try {
                    const response = await fetch(`${BASE_PATH}/super-admin/api/students/${userId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(requestBody)
                    });
                    
                    // Check if response is HTML (redirect to login) instead of JSON
                    const contentType = response.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        console.warn('Non-JSON response received, session may have expired');
                        // Session expired, silently ignore - user stays on page
                        return;
                    }
                    
                    const data = await response.json();
                    
                    // Check for authentication error
                    if (data.unauthenticated || response.status === 401 || response.status === 419) {
                        console.warn('Session expired, silently ignoring update');
                        // Don't show error - just stay on page, session keeper will maintain session
                        return;
                    }
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to update student');
                    }
                    
                    showToast(data.message || 'Student updated successfully', 'success');
                    document.getElementById('student_edit_modal').close();
                    loadStudents(false);
                } catch (error) {
                    console.error('Error updating student:', error);
                    // Only show error if it's not an authentication/parsing issue
                    if (!error.message.includes('Unexpected token') && 
                        !error.message.includes('JSON') && 
                        !error.message.includes('Session')) {
                        showToast(error.message || 'Failed to update student', 'error');
                    }
                }
            });
        }
        
        window.loadStudents = loadStudents;
        window.refreshStudents = refreshStudents;
        window.openStudentEditModal = openStudentEditModal;
        window.openDeleteStudentModal = openDeleteStudentModal;
        // ========== END STUDENTS MANAGEMENT ==========

        // DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Restore saved page for super admin, default to dashboard
            var savedPage = 'dashboard';
            try {
                savedPage = localStorage.getItem('scms_superadmin_current_page') || 'dashboard';
            } catch(_) {}
            
            showPage(savedPage);
            initThemeToggle();
            initYearlyCharts();
            loadDashboardStats();  // Load dashboard statistics
            loadSubmissions();
            initPendingRequestsChart();
            generateActivityCalendar();
            
            // Add resize listener to scroll calendar to right
            window.addEventListener('resize', function() {
                scrollCalendarToRight();
            });
            
            // Fix dropdown positioning for status filter
            setTimeout(function() {
                var dropdownBtn = document.querySelector('#status-filter-dropdown [role="button"]');
                if (dropdownBtn) {
                    dropdownBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
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
            }, 500);
            
            // Helper function to reset all indicators (double arrow for inactive)
            function resetAllSortIndicators() {
                document.getElementById('hours-sort-indicator').textContent = '⇅';
                document.getElementById('studentid-sort-indicator').textContent = '⇅';
                document.getElementById('date-sort-indicator').textContent = '⇅';
                document.getElementById('studentname-sort-indicator').textContent = '⇅';
                document.getElementById('eventname-sort-indicator').textContent = '⇅';
                document.getElementById('organization-sort-indicator').textContent = '⇅';
            }
            
            // Hours sort toggle event listener
            var hoursSortToggle = document.getElementById('hours-sort-toggle');
            var hoursSortIndicator = document.getElementById('hours-sort-indicator');
            if (hoursSortToggle && hoursSortIndicator) {
                hoursSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'hours';
                    hoursSortDirection = hoursSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    hoursSortIndicator.textContent = hoursSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'hours');
                });
            }
            
            // Student ID sort toggle event listener
            var studentIdSortToggle = document.getElementById('studentid-sort-toggle');
            var studentIdSortIndicator = document.getElementById('studentid-sort-indicator');
            if (studentIdSortToggle && studentIdSortIndicator) {
                studentIdSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'studentid';
                    studentIdSortDirection = studentIdSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    studentIdSortIndicator.textContent = studentIdSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'studentid');
                });
            }
            
            // Date sort toggle event listener
            var dateSortToggle = document.getElementById('date-sort-toggle');
            var dateSortIndicator = document.getElementById('date-sort-indicator');
            if (dateSortToggle && dateSortIndicator) {
                dateSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'date';
                    dateSortDirection = dateSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    dateSortIndicator.textContent = dateSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'date');
                });
            }
            
            // Student Name sort toggle event listener
            var studentNameSortToggle = document.getElementById('studentname-sort-toggle');
            var studentNameSortIndicator = document.getElementById('studentname-sort-indicator');
            if (studentNameSortToggle && studentNameSortIndicator) {
                studentNameSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'studentname';
                    studentNameSortDirection = studentNameSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    studentNameSortIndicator.textContent = studentNameSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'studentname');
                });
            }
            
            // Event Name sort toggle event listener
            var eventNameSortToggle = document.getElementById('eventname-sort-toggle');
            var eventNameSortIndicator = document.getElementById('eventname-sort-indicator');
            if (eventNameSortToggle && eventNameSortIndicator) {
                eventNameSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'eventname';
                    eventNameSortDirection = eventNameSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    eventNameSortIndicator.textContent = eventNameSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'eventname');
                });
            }
            
            // Organization sort toggle event listener
            var organizationSortToggle = document.getElementById('organization-sort-toggle');
            var organizationSortIndicator = document.getElementById('organization-sort-indicator');
            if (organizationSortToggle && organizationSortIndicator) {
                organizationSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'organization';
                    organizationSortDirection = organizationSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    organizationSortIndicator.textContent = organizationSortDirection === 'asc' ? '↑' : '↓';
                    renderSubmissions(allSubmissions, 'organization');
                });
            }
            
            // ========== STUDENT TABLE SORTING EVENT LISTENERS ==========
            // Student ID sort
            var studentIdSort = document.getElementById('student-id-sort');
            if (studentIdSort) {
                studentIdSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudents('student-id');
                });
            }
            
            // Full Name sort
            var fullNameSort = document.getElementById('full-name-sort');
            if (fullNameSort) {
                fullNameSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudents('full-name');
                });
            }
            
            // Email sort
            var emailSort = document.getElementById('email-sort');
            if (emailSort) {
                emailSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudents('email');
                });
            }
            
            // Email Verified sort
            var emailVerifiedSort = document.getElementById('email-verified-sort');
            if (emailVerifiedSort) {
                emailVerifiedSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudents('email-verified');
                });
            }
            
            // Approved Hours sort
            var approvedHoursSort = document.getElementById('approved-hours-sort');
            if (approvedHoursSort) {
                approvedHoursSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudents('approved-hours');
                });
            }
            
            // Status sort
            var statusSort = document.getElementById('status-sort');
            if (statusSort) {
                statusSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortStudents('status');
                });
            }
            
            // ========== SUPPORT TICKETS TABLE SORTING EVENT LISTENERS ==========
            // Ticket ID sort
            var ticketIdSort = document.getElementById('ticket-id-sort');
            if (ticketIdSort) {
                ticketIdSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortTickets('ticket-id');
                });
            }
            
            // Ticket Student ID sort
            var ticketStudentIdSort = document.getElementById('ticket-student-id-sort');
            if (ticketStudentIdSort) {
                ticketStudentIdSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortTickets('ticket-student-id');
                });
            }
            
            // Ticket Student Name sort
            var ticketStudentNameSort = document.getElementById('ticket-student-name-sort');
            if (ticketStudentNameSort) {
                ticketStudentNameSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortTickets('ticket-student-name');
                });
            }
            
            // Ticket Issue Type sort
            var ticketIssueTypeSort = document.getElementById('ticket-issue-type-sort');
            if (ticketIssueTypeSort) {
                ticketIssueTypeSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortTickets('ticket-issue-type');
                });
            }
            
            // Ticket Status sort
            var ticketStatusSort = document.getElementById('ticket-status-sort');
            if (ticketStatusSort) {
                ticketStatusSort.addEventListener('click', function(e) {
                    e.preventDefault();
                    sortTickets('ticket-status');
                });
            }
            
            // Reset reject modal function
            function resetRejectModal() {
                var rejectionReasonSelect = document.getElementById('rejection-reason-select');
                var otherReasonLabel = document.getElementById('other-reason-label');
                var rejectionReasonTextarea = document.getElementById('reject-reason-textarea');
                
                rejectionReasonSelect.value = "";
                otherReasonLabel.classList.add('hidden');
                rejectionReasonTextarea.value = '';
                rejectionReasonSelect.classList.remove('border-red-500');
                rejectionReasonTextarea.classList.remove('border-red-500');
            }
            
            // Make resetRejectModal globally accessible
            window.resetRejectModal = resetRejectModal;
            
            // Rejection reason dropdown change handler
            var rejectionReasonSelect = document.getElementById('rejection-reason-select');
            var otherReasonLabel = document.getElementById('other-reason-label');
            var rejectionReasonTextarea = document.getElementById('reject-reason-textarea');
            
            if (rejectionReasonSelect) {
                rejectionReasonSelect.addEventListener('change', function() {
                    if (this.value === 'Others') {
                        otherReasonLabel.classList.remove('hidden');
                    } else {
                        otherReasonLabel.classList.add('hidden');
                        rejectionReasonTextarea.value = '';
                    }
                });
            }
            
            // Confirm verify button handler
            document.getElementById('confirm-verify-btn').addEventListener('click', async function() {
                if (activeRow) {
                    var recordId = activeRow.dataset.recordId;
                    
                    try {
                        // Make API call to verify the submission
                        const response = await fetch(`${BASE_PATH}/super-admin/api/submissions/${recordId}/verify`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });
                        
                        // Check if response is HTML (redirect to login) instead of JSON
                        const contentType = response.headers.get('content-type') || '';
                        if (!contentType.includes('application/json')) {
                            console.warn('Non-JSON response received, session may have expired');
                            // Session expired, silently ignore
                            return;
                        }
                        
                        const data = await response.json();
                        
                        // Check for authentication error
                        if (data.unauthenticated || response.status === 401 || response.status === 419) {
                            console.warn('Session expired, silently ignoring verify');
                            return;
                        }
                        
                        if (data.success) {
                            showToast('Submission has been verified successfully.', 'success');
                            document.getElementById('verify_modal').close();
                            activeRow = null;
                            
                            // Reload submissions to get fresh data from database
                            loadSubmissions();
                            loadDashboardStats();
                        } else {
                            showToast(data.message || 'Failed to verify submission.', 'error');
                        }
                    } catch (error) {
                        console.error('Error verifying submission:', error);
                        // Only show error if it's not an authentication/parsing issue
                        if (!error.message.includes('Unexpected token') && 
                            !error.message.includes('JSON') && 
                            !error.message.includes('Session')) {
                            showToast('Failed to verify submission. Please try again.', 'error');
                        }
                    }
                }
            });
            
            // Confirm approve button handler
            document.getElementById('confirm-approve-btn').addEventListener('click', async function() {
                if (activeRow) {
                    var recordId = activeRow.dataset.recordId;
                    console.log('Approving submission with ID:', recordId);
                    
                    try {
                        await ensureCsrfCookie();
                        const response = await fetch(`${BASE_PATH}/super-admin/api/submissions/${recordId}/approve`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin'
                        });
                        
                        console.log('Response status:', response.status);
                        
                        // Check if response is HTML (redirect to login) instead of JSON
                        const contentType = response.headers.get('content-type') || '';
                        if (!contentType.includes('application/json')) {
                            console.warn('Non-JSON response received, session may have expired');
                            // Session expired, silently ignore
                            return;
                        }
                        
                        const data = await response.json();
                        console.log('Response data:', data);
                        
                        // Check for authentication error
                        if (data.unauthenticated || response.status === 401 || response.status === 419) {
                            console.warn('Session expired, silently ignoring approve');
                            return;
                        }
                        
                        if (data.success) {
                            showToast('Submission has been approved.', 'success');
                            document.getElementById('approve_modal').close();
                            activeRow = null;
                            
                            // Reload to update stats and table
                            loadSubmissions();
                        } else {
                            console.error('Approval failed:', data);
                            showToast(data.message || 'Failed to approve submission.', 'error');
                        }
                    } catch (error) {
                        console.error('Error approving submission:', error);
                        // Only show error if it's not an authentication/parsing issue
                        if (!error.message.includes('Unexpected token') && 
                            !error.message.includes('JSON') && 
                            !error.message.includes('Session')) {
                            showToast('Failed to approve submission. Please try again.', 'error');
                        }
                    }
                }
            });
            
            // Confirm reject button handler
            document.getElementById('confirm-reject-btn').addEventListener('click', async function() {
                if (activeRow) {
                    var recordId = activeRow.dataset.recordId;
                    var rejectionReasonSelect = document.getElementById('rejection-reason-select');
                    var reasonTextarea = document.getElementById('reject-reason-textarea');
                    var reason = rejectionReasonSelect.value;
                    
                    // Clear previous error states
                    rejectionReasonSelect.classList.remove('border-red-500');
                    reasonTextarea.classList.remove('border-red-500');
                    
                    // Validate selection
                    if (reason === 'Others') {
                        reason = reasonTextarea.value.trim();
                        if (!reason) {
                            reasonTextarea.classList.add('border-red-500');
                            reasonTextarea.focus();
                            showToast('Please specify the reason for rejection when selecting "Others".', 'error');
                            return;
                        }
                    } else if (!reason) {
                        rejectionReasonSelect.classList.add('border-red-500');
                        showToast('Please select a rejection reason.', 'error');
                        return;
                    }
                    
                    // Decode HTML entities in reason (&#10; becomes \n)
                    reason = reason.replace(/&#10;/g, '\n');
                    
                    console.log('Rejecting submission with ID:', recordId, 'Reason:', reason);
                    
                    try {
                        // Disable button to prevent double submission
                        this.disabled = true;
                        this.textContent = 'Rejecting...';
                        
                        await ensureCsrfCookie();
                        const response = await fetch(`${BASE_PATH}/super-admin/api/submissions/${recordId}/reject`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({
                                reason: reason
                            })
                        });
                        
                        console.log('Response status:', response.status);
                        
                        // Check if response is HTML (redirect to login) instead of JSON
                        const contentType = response.headers.get('content-type') || '';
                        if (!contentType.includes('application/json')) {
                            console.warn('Non-JSON response received, session may have expired');
                            // Session expired, silently ignore
                            return;
                        }
                        
                        const data = await response.json();
                        console.log('Response data:', data);
                        
                        // Check for authentication error
                        if (data.unauthenticated || response.status === 401 || response.status === 419) {
                            console.warn('Session expired, silently ignoring reject');
                            return;
                        }
                        
                        if (data.success) {
                            showToast('Submission has been rejected.', 'success');
                            resetRejectModal(); // Clear the form
                            document.getElementById('reject_modal').close();
                            activeRow = null;
                            
                            // Reload to update stats and table
                            loadSubmissions();
                            generateActivityCalendar(); // Refresh calendar
                            loadDashboardStats(); // Refresh stats
                        } else {
                            console.error('Rejection failed:', data);
                            showToast(data.message || 'Failed to reject submission.', 'error');
                        }
                    } catch (error) {
                        console.error('Error rejecting submission:', error);
                        // Only show error if it's not an authentication/parsing issue
                        if (!error.message.includes('Unexpected token') && 
                            !error.message.includes('JSON') && 
                            !error.message.includes('Session')) {
                            showToast('Failed to reject submission. Please try again.', 'error');
                        }
                    } finally {
                        // Re-enable button and restore text
                        this.disabled = false;
                        this.textContent = 'Yes, reject';
                    }
                }
            });
            
            // Search input handler
            document.getElementById('search-input').addEventListener('keyup', function() {
                var at = document.querySelector('.custom-tab-active');
                if (at) {
                    var as = at.textContent.trim().toLowerCase();
                    filterSubmissions(as, at);
                }
            });
            
            // Name change handler
            var snb = document.getElementById('save-name-button');
            snb.addEventListener('click', async function(e) {
                e.preventDefault();
                var nameInput = document.getElementById('admin-name');
                var newName = nameInput.value.trim();
                
                if (!newName) {
                    showToast('Please enter a valid name.', 'error');
                    return;
                }
                
                try {
                    snb.disabled = true;
                    snb.textContent = 'Updating...';
                    
                    var response = await fetch(`${BASE_PATH}/super-admin/api/settings/update-name`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ name: newName })
                    });
                    
                    var data = await response.json();
                    
                    if (response.ok && data.success) {
                        showToast(data.message || 'Name updated successfully!', 'success');
                        // Update the name in the sidebar
                        location.reload();
                    } else {
                        showToast(data.message || 'Failed to update name.', 'error');
                    }
                } catch (error) {
                    console.error('Error updating name:', error);
                    showToast('Failed to update name. Please try again.', 'error');
                } finally {
                    snb.disabled = false;
                    snb.textContent = 'Update Name';
                }
            });
            
            // Password change handler with email verification
            var spb = document.getElementById('save-password-button');
            var pcf = document.getElementById('password-change-form');
            
            spb.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (!pcf.checkValidity()) {
                    pcf.reportValidity();
                    return;
                }
                
                var currentPassword = document.getElementById('current-password').value;
                var newPassword = document.getElementById('new-password').value;
                var confirmPassword = document.getElementById('confirm-password').value;
                
                if (newPassword !== confirmPassword) {
                    showToast('New password and confirm password do not match.', 'error');
                    return;
                }
                
                if (newPassword.length < 8) {
                    showToast('New password must be at least 8 characters long.', 'error');
                    return;
                }
                
                try {
                    spb.disabled = true;
                    spb.textContent = 'Sending...';
                    
                    var response = await fetch(`${BASE_PATH}/super-admin/api/settings/request-password-change`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            current_password: currentPassword,
                            new_password: newPassword,
                            new_password_confirmation: confirmPassword
                        })
                    });
                    
                    var data = await response.json();
                    
                    if (response.ok && data.success) {
                        showToast(data.message, 'success');
                        pcf.reset();
                    } else {
                        showToast(data.message || 'Failed to process password change request.', 'error');
                    }
                } catch (error) {
                    console.error('Error requesting password change:', error);
                    showToast('Failed to process password change request. Please try again.', 'error');
                } finally {
                    spb.disabled = false;
                    spb.textContent = 'Request Password Change';
                }
            });
            
            // Auto-refresh removed - use manual refresh buttons instead
            
            // Auto-hide flash messages after 5 seconds
            var flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(function() {
                    flashMessage.style.transition = 'opacity 0.5s';
                    flashMessage.style.opacity = '0';
                    setTimeout(function() {
                        flashMessage.remove();
                    }, 500);
                }, 5000);
            }

            // ========== SUPPORT TICKET EVENT LISTENERS ==========
            // Resolve ticket button
            const resolveTicketBtn = document.getElementById('resolve-ticket-btn');
            if (resolveTicketBtn) {
                resolveTicketBtn.addEventListener('click', resolveTicket);
            }

            // Ticket search functionality
            const ticketSearchInput = document.getElementById('ticket-search-input');
            if (ticketSearchInput) {
                ticketSearchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    if (!query) {
                        renderTicketsTable();
                        return;
                    }

                    const filtered = allTickets.filter(ticket =>
                        ticket.id.toString().includes(query) ||
                        (ticket.student_name && ticket.student_name.toLowerCase().includes(query)) ||
                        ticket.type.toLowerCase().includes(query) ||
                        ticket.details.toLowerCase().includes(query)
                    );
                    renderTicketsTable(filtered);
                });
            }
            // ========== END SUPPORT TICKET EVENT LISTENERS ==========
        });
    </script>

    <!-- Session Keeper: Keeps session alive and CSRF token fresh -->
    <script src="<?php echo e(asset('js/session-keeper.js')); ?>"></script>
    <script>
        // Initialize Session Keeper for Super Admin Dashboard
        if (window.SessionKeeper) {
            SessionKeeper.init({
                debug: false, // Set to true for debugging
                autoRefreshEnabled: true,
                dataRefreshInterval: 30 * 1000, // Refresh data every 30 seconds
                onDataRefresh: function() {
                    // Refresh submissions, students, and stats automatically
                    console.log('[Super Admin Dashboard] Auto-refreshing data...');
                    if (typeof fetchSubmissions === 'function') {
                        fetchSubmissions();
                    }
                    if (typeof fetchStudents === 'function') {
                        fetchStudents();
                    }
                    if (typeof fetchDashboardStats === 'function') {
                        fetchDashboardStats();
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

    <script>
        // Sidebar collapse functionality
        (function() {
            function initSidebarCollapse() {
                const sidebar = document.getElementById('sidebar');
                const collapseBtn = document.getElementById('collapse-btn');
                const collapseText = document.getElementById('collapse-text');
                const mobileMenuBtn = document.getElementById('mobile-menu-btn');
                const mobileCloseBtn = document.getElementById('mobile-close-btn');
                const sidebarBackdrop = document.getElementById('sidebar-backdrop');
                
                // Safety check - ensure elements exist
                if (!sidebar || !collapseBtn || !collapseText) {
                    console.warn('Sidebar collapse elements not found');
                    return;
                }
                
                // Check if mobile view
                function isMobile() {
                    return window.innerWidth <= 768;
                }
                
                // Load saved state from localStorage (only for desktop)
                const savedState = localStorage.getItem('scms_superadmin_sidebar_collapsed');
                if (savedState === 'true' && !isMobile()) {
                    sidebar.classList.add('collapsed');
                    collapseText.textContent = 'Show';
                }
                
                // Toggle collapse on button click (desktop)
                collapseBtn.addEventListener('click', function() {
                    if (isMobile()) return; // Don't collapse on mobile
                    
                    sidebar.classList.toggle('collapsed');
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    collapseText.textContent = isCollapsed ? 'Show' : 'Hide';
                    
                    // Save state to localStorage
                    localStorage.setItem('scms_superadmin_sidebar_collapsed', isCollapsed);
                });
                
                // Mobile menu toggle
                function openMobileSidebar() {
                    sidebar.classList.add('mobile-open');
                    sidebarBackdrop.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    // Hide the mobile header when sidebar opens
                    var mobileHeader = document.getElementById('mobile-header');
                    if (mobileHeader) mobileHeader.classList.add('hidden-when-open');
                }
                
                function closeMobileSidebar() {
                    sidebar.classList.remove('mobile-open');
                    sidebarBackdrop.classList.remove('active');
                    document.body.style.overflow = '';
                    // Show the mobile header when sidebar closes
                    var mobileHeader = document.getElementById('mobile-header');
                    if (mobileHeader) mobileHeader.classList.remove('hidden-when-open');
                }
                
                // Mobile menu button click
                if (mobileMenuBtn) {
                    mobileMenuBtn.addEventListener('click', function() {
                        if (sidebar.classList.contains('mobile-open')) {
                            closeMobileSidebar();
                        } else {
                            openMobileSidebar();
                        }
                    });
                }
                
                // Mobile close button click
                if (mobileCloseBtn) {
                    mobileCloseBtn.addEventListener('click', function() {
                        closeMobileSidebar();
                    });
                }
                
                // Close sidebar when clicking backdrop
                if (sidebarBackdrop) {
                    sidebarBackdrop.addEventListener('click', function() {
                        closeMobileSidebar();
                    });
                }
                
                // Close sidebar when clicking a nav link on mobile
                const navLinks = sidebar.querySelectorAll('#menu-list a, ul.menu a, ul.menu button');
                navLinks.forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (isMobile()) {
                            closeMobileSidebar();
                        }
                    });
                });
                
                // Handle window resize
                window.addEventListener('resize', function() {
                    if (!isMobile()) {
                        // Switching to desktop: close mobile sidebar and restore collapse state
                        closeMobileSidebar();
                        const savedState = localStorage.getItem('scms_superadmin_sidebar_collapsed');
                        if (savedState === 'true') {
                            sidebar.classList.add('collapsed');
                            collapseText.textContent = 'Show';
                        }
                    }
                });
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSidebarCollapse, { once: true });
            } else {
                initSidebarCollapse();
            }
        })();
    </script>

    <!-- Theme Toggle Script -->
    <script>
        // Theme toggle functionality
        (function() {
            try {
                var tg = document.getElementById('theme-toggle');
                var lb = document.getElementById('theme-label');
                var sunIcon = document.getElementById('theme-icon-sun');
                var moonIcon = document.getElementById('theme-icon-moon');
                var sidebarLogoLight = document.getElementById('sidebar-logo-light');
                var sidebarLogoDark = document.getElementById('sidebar-logo-dark');
                
                var applyTheme = function(mode) {
                    document.documentElement.setAttribute('data-theme', mode);
                    try {
                        localStorage.setItem('scms_superadmin_theme', mode);
                    } catch(_) {}
                    
                    // Update label text
                    if (lb) lb.textContent = (mode === 'dark') ? 'Dark Mode' : 'Light Mode';
                    
                    // Update toggle state
                    if (tg) tg.checked = (mode === 'dark');
                    
                    // Update icons - show sun in dark mode (to switch to light), moon in light mode (to switch to dark)
                    if (sunIcon && moonIcon) {
                        if (mode === 'dark') {
                            sunIcon.classList.remove('hidden');
                            moonIcon.classList.add('hidden');
                        } else {
                            sunIcon.classList.add('hidden');
                            moonIcon.classList.remove('hidden');
                        }
                    }
                    
                    // Update sidebar logo
                    if (sidebarLogoLight && sidebarLogoDark) {
                        if (mode === 'dark') {
                            sidebarLogoLight.classList.add('hidden');
                            sidebarLogoDark.classList.remove('hidden');
                        } else {
                            sidebarLogoLight.classList.remove('hidden');
                            sidebarLogoDark.classList.add('hidden');
                        }
                    }
                };
                
                // Load saved theme or default to light
                var savedTheme = 'light';
                try {
                    savedTheme = localStorage.getItem('scms_superadmin_theme');
                    if (savedTheme !== 'dark' && savedTheme !== 'light') {
                        savedTheme = 'light';
                    }
                } catch(_) {}
                
                applyTheme(savedTheme);
                
                // Handle toggle change
                if (tg) {
                    tg.addEventListener('change', function() {
                        applyTheme(tg.checked ? 'dark' : 'light');
                    });
                }
            } catch(e) {
                console.error('Theme toggle error:', e);
            }
        })();

        // Auto-logout super admin when page is closed, browser back, or tab closed
        window.addEventListener('beforeunload', function() {
            if (navigator.sendBeacon) {
                navigator.sendBeacon('<?php echo e(route("superadmin.logout")); ?>', new FormData());
            }
        });

        window.addEventListener('pagehide', function() {
            if (navigator.sendBeacon) {
                navigator.sendBeacon('<?php echo e(route("superadmin.logout")); ?>', new FormData());
            }
        });
    </script>
    <?php include resource_path('views/partials/footer_partial.php'); ?>
</body>
</html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\dashboards\super_admin.blade.php ENDPATH**/ ?>