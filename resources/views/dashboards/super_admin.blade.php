<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<title>Super Admin - Student Contract Management</title>
<script>
(function(){try{var s=localStorage.getItem('scms_superadmin_theme');if(s==='dark'||s==='light'){document.documentElement.setAttribute('data-theme',s);}}catch(_){}})();
</script>
<script>
tailwind=typeof tailwind==='object'?tailwind:{};tailwind.config={theme:{extend:{colors:{'background-light':'#EDF1FA','primary-purple':'#6D28D9','primary-purple-hover':'#5B21B6','text-header':'#2B3674','text-muted':'#707EAE','badge-pending-text':'#E29C44','badge-pending-bg':'#FAEAD0','badge-verified-text':'#399552','badge-verified-bg':'#CCEED6','badge-rejected-text':'#CC525D','badge-rejected-bg':'#FFD7DB','success-green':'#4CAF50','success-green-hover':'#45a049','danger-red':'#CC525D','danger-red-hover':'#b33e46'},fontFamily:{sans:['Inter','sans-serif']}}}};
</script>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css">
@php
    $iconCandidates = ['vits_white.png', 'storage/vits_whites.png', 'vits_whites.png', 'vitswhite.png', 'vitslogo.png', 'public/storage/vits_white.png', 'storage/vits_header.png'];
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
body{font-family:'Inter',sans-serif}
.active-nav{background-color:#6D28D9;color:#fff;border-radius:0.5rem}
.flex-1-dynamic{flex:1 1 auto;min-height:0}
.content-area-auto{height:auto;max-height:100%}
.btn-primary-purple{background-color:#6D28D9!important;color:#fff!important;border-color:transparent!important}
.btn-primary-purple:hover{background-color:#5B21B6!important;color:#fff!important}
.btn-primary-purple:focus{outline:none!important;box-shadow:0 0 0 2px rgba(109,40,217,0.35)!important}
.btn-primary-purple:active{background-color:#4C1D95!important;color:#fff!important}
.btn-primary-purple svg{stroke:#fff!important}
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
.bg-custom{background-color:#EDF1FA;background-image:url('{{ asset("vits_bg_white.png") }}');background-repeat:no-repeat;background-size:cover;background-position:center;background-attachment:fixed}
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
[data-theme="dark"] .bg-custom{background-color:#0b0f19;background-image:url('{{ asset("storage/vits_bg_black.png") }}')}
[data-theme="dark"] .table thead,[data-theme="dark"] .table thead tr,[data-theme="dark"] .table thead th{background-color:#374151!important}
[data-theme="dark"] .table th,[data-theme="dark"] .table td{border-color:#374151!important}
[data-theme="dark"] .bg-white{background-color:#1f2937!important}
[data-theme="dark"] .bg-gray-100{background-color:#374151!important}
[data-theme="dark"] .border-gray-200{border-color:#374151!important}
[data-theme="dark"] .bg-base-100{background-color:#111827!important}
[data-theme="dark"] .scms-toast{border-color:rgba(255,255,255,0.14);box-shadow:0 10px 24px rgba(0,0,0,0.35),0 2px 6px rgba(0,0,0,0.2)}
[data-theme="dark"] .custom-tab-wrapper{background-color:#1f2937}
[data-theme="dark"] .details-input{background-color:#374151;border-color:#4b5563;color:#fff}
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
#submission-page .overflow-x-auto{overflow:visible!important}
</style>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-custom">
@php
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
@endphp

    <div class="flex p-4 gap-4 min-h-screen">
        <!-- Sidebar -->
        <aside class="flex flex-col w-64 bg-white rounded-2xl p-4 shadow-sm sticky top-4 self-start h-[calc(100vh-2rem)] overflow-hidden">
            <!-- Profile Section -->
            <div class="flex flex-col items-center text-center p-4 border-b border-gray-200">
                <div class="avatar placeholder mb-3">
                    <div class="w-24 h-24 rounded-full ring ring-[#6D28D9] ring-offset-2 ring-offset-base-100 bg-[#6D28D9] text-white flex items-center justify-center select-none" 
                         title="{{ $fullName }}" 
                         aria-label="{{ $fullName }}">
                        <span class="text-3xl font-bold leading-none">{{ $initials }}</span>
                    </div>
                </div>
                <h2 class="font-bold text-lg">{{ $fullName }}</h2>
                <p class="text-sm text-gray-500">Super Administrator</p>
            </div>

            <!-- Main Navigation -->
            <ul class="menu p-0 my-4 flex-grow">
                <li>
                    <a class="py-3" id="nav-dashboard" onclick="showPage('dashboard')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="py-3" id="nav-submission" onclick="showPage('submission')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Submission
                    </a>
                </li>
                <li>
                    <a class="py-3" id="nav-students" onclick="showPage('students')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Students Information
                    </a>
                </li>
            </ul>

            <!-- Bottom Navigation -->
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
                    <a class="py-3 pl-2 pr-0 w-full text-left flex items-center gap-2 min-h-0" 
                       id="nav-settings" 
                       onclick="showPage('settings')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.096 2.572-1.065z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        Settings
                    </a>
                </li>
                <li>
                    <a class="py-3 pl-2" onclick="document.getElementById('logout_modal').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Log Out
                    </a>
                </li>
            </ul>
        </aside>

        <main class="flex-1 flex flex-col gap-6" id="page-container">
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success shadow-lg mx-4" id="flash-message">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error shadow-lg mx-4" id="flash-message">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <!-- Dashboard Overview Page -->
            <div id="dashboard-page" class="page-content flex-col flex-1-dynamic">
                <h1 class="text-4xl font-bold text-primary-purple px-4 mb-6">Super Admin Dashboard</h1>
                
                <!-- Welcome Greeting Card -->
                <div class="relative rounded-2xl bg-transparent p-2 mb-6 h-[190px]">
                    <div class="bg-gradient-primary-purple flex items-center rounded-2xl h-[190px] w-full shadow-lg relative overflow-hidden">
                        <!-- Purple curved accent -->
                        <div class="absolute top-0 left-0 w-[120px] h-[120px] bg-gradient-to-r from-primary-purple to-transparent rounded-br-full opacity-70"></div>
                        
                        <!-- Left text content -->
                        <div class="relative z-10 ml-2 pl-10">
                            <h2 class="text-3xl font-semibold text-white">
                                Welcome, 
                                <span class="text-white font-bold">
                                    {{ Str::of(auth('superadmin')->user()->name)->explode(' ')->first() }}
                                </span>
                            </h2>
                            <br>
                            <p class="text-white text-base mt-1">
                                Manage admin submissions and <br>
                                oversee social contract compliance.
                            </p>
                            <p class="text-white font-bold text-base mt-1">
                                Empowering ka-VITS through efficient super administration!
                            </p>
                        </div>
                        
                        <!-- Pending Requests Donut -->
                        <div class="flex flex-col items-center ml-auto mr-8">
                            <h2 class="text-xl font-bold text-white mb-4">Pending Requests</h2>
                            <div class="relative w-40 h-40">
                                <canvas id="pendingRequestsChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-3xl font-bold text-white" id="pending-requests-label">0</span>
                                    <p class="text-sm text-white">Requests</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="bg-white rounded-2xl p-6 shadow-sm mb-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-text-header mb-1">Monthly Summary</h2>
                            <p class="text-sm text-text-muted">Contract requests overview for this month</p>
                        </div>
                        <button onclick="loadDashboardStats(); generateActivityCalendar();" class="btn btn-ghost btn-sm gap-2" title="Refresh dashboard stats">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span class="hidden md:inline">Refresh</span>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Pending Requests (Verified awaiting Super Admin approval) -->
                        <div class="bg-gradient-pending p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Verified')">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="pending-requests-count">0</span> Requests</h3>
                                <p class="text-yellow-800 font-semibold">Pending</p>
                                <p class="text-xs text-text-muted mt-1">Awaiting review</p>
                            </div>
                        </div>
                        
                        <!-- Approved Requests -->
                        <div class="bg-gradient-accepted p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Approved')">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="accepted-requests-count">0</span> Requests</h3>
                                <p class="text-green-800 font-semibold">Approved This Month</p>
                                <p class="text-xs text-text-muted mt-1">Successfully verified</p>
                            </div>
                        </div>
                        
                        <!-- Rejected Requests -->
                        <div class="bg-gradient-rejected p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Rejected')">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="rejected-requests-count">0</span> Requests</h3>
                                <p class="text-red-800 font-semibold">Rejected This Month</p>
                                <p class="text-xs text-text-muted mt-1">Requires corrections</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Calendar -->
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-text-header">Contract Update Activity</h2>
                            <p class="text-sm text-text-muted">Days when contracts were reviewed and updated (updates tracked in real-time)</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3 text-xs">
                                <span class="text-text-muted">Less</span>
                                <div class="flex gap-1">
                                    <div class="w-3 h-3 rounded-sm bg-gray-200" title="No activity"></div>
                                    <div class="w-3 h-3 rounded-sm bg-[#E5D4FF]" title="1-2 updates"></div>
                                    <div class="w-3 h-3 rounded-sm bg-[#C9A9FF]" title="3-4 updates"></div>
                                    <div class="w-3 h-3 rounded-sm bg-[#A475FF]" title="5-6 updates"></div>
                                    <div class="w-3 h-3 rounded-sm bg-[#6D28D9]" title="7+ updates"></div>
                                </div>
                                <span class="text-text-muted">More</span>
                            </div>
                            <!-- Year Navigation -->
                            <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                                <button id="prev-year-btn" class="btn btn-ghost btn-xs" onclick="changeCalendarYear(-1)" title="Previous year">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <span id="calendar-year" class="text-sm font-bold text-text-header min-w-[60px] text-center">2025</span>
                                <button id="next-year-btn" class="btn btn-ghost btn-xs" onclick="changeCalendarYear(1)" title="Next year">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="activity-calendar" class="overflow-x-auto pb-2">
                        <!-- Calendar will be dynamically generated by JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Submission Page -->
            <div id="submission-page" class="page-content hidden flex flex-col h-full">
                <div class="flex-shrink-0">
                    <h1 class="text-4xl font-bold text-primary-purple px-4">Submission Management</h1>
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 px-4 gap-4 md:gap-0">
                        <!-- Tabs -->
                        <!-- 
                            Workflow:
                            1. Pending: Records submitted by students (not yet reviewed by anyone)
                            2. For Approval: Records with "Verified" status (admin verified from their archived section) - awaiting super admin's final decision
                            3. Archived: Records with super admin's final decision (Approved or Rejected)
                            
                            Status mapping: 
                            - DB Status "Pending" → Shows in "Pending" tab
                            - DB Status "Verified" → Shows in "For Approval" tab (these are admin's archived verified records)
                            - DB Status "Approved"/"Rejected" → Shows in "Archived" tab (super admin's final decisions)
                        -->
                        <div class="flex space-x-2 custom-tab-wrapper">
                            <a role="tab" class="custom-tab custom-tab-active" onclick="filterSubmissions('pending',this)">Pending</a>
                            <a role="tab" class="custom-tab" onclick="filterSubmissions('for-approval',this)">For Approval</a>
                            <a role="tab" class="custom-tab" onclick="filterSubmissions('archived',this)">Archived</a>
                        </div>
                        
                        <!-- Search -->
                        <label class="input input-bordered flex items-center gap-2 rounded-lg bg-white h-10 w-full md:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                                <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd"/>
                            </svg>
                            <input type="text" class="grow bg-transparent" placeholder="Search" id="search-input">
                        </label>
                        
                        <!-- Refresh Button -->
                        <button id="refresh-submissions-btn" onclick="refreshSubmissions()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh submissions">
                            <svg id="refresh-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span class="hidden md:inline">Refresh</span>
                        </button>
                    </div>
                </div>

                <!-- Submission Table -->
                <div class="bg-white rounded-2xl p-6 shadow-sm flex-1 flex flex-col min-h-0">
                    <div class="overflow-x-auto overflow-y-auto flex-1">
                        <!-- Updated table with Supervisor column - matching admin structure -->
                        <table class="table w-full" style="min-width: 1200px;">
                            <thead class="bg-gray-50 text-gray-600 sticky top-0 z-10">
                                <tr id="table-header-row">
                                    <th class="text-center" style="width: 100px; min-width: 100px;">
                                        <button id="studentid-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Student ID">
                                            Student ID
                                            <span id="studentid-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="width: 140px; min-width: 140px;">
                                        <button id="studentname-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Student Name">
                                            Student Name
                                            <span id="studentname-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="width: 160px; min-width: 160px;">
                                        <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Event Name">
                                            Event Name
                                            <span id="eventname-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="width: 130px; min-width: 130px;">
                                        <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Organization">
                                            Organization
                                            <span id="organization-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="width: 130px; min-width: 130px;">
                                        <button id="supervisor-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Supervisor">
                                            Supervisor
                                            <span id="supervisor-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="width: 140px; min-width: 140px;">
                                        <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Hours Rendered">
                                            Hours Rendered
                                            <span id="hours-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="width: 110px; min-width: 110px;">
                                        <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Date">
                                            Date
                                            <span id="date-sort-indicator">▼</span>
                                        </button>
                                    </th>
                                    <th class="text-center" style="width: 290px; min-width: 290px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="submission-table-body">
                                <!-- Data will be loaded dynamically from database -->
                                <tr id="loading-row">
                                    <td colspan="8" class="text-center py-8">
                                        <span class="loading loading-spinner loading-lg text-primary-purple"></span>
                                        <p class="mt-2 text-text-muted">Loading submissions...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Students Information Page -->
            <div id="students-page" class="page-content hidden flex-col flex-1-dynamic">
                <h1 class="text-4xl font-bold text-primary-purple px-4">Students Information</h1>

                <!-- Search and Actions Bar -->
                <div class="flex items-center justify-between px-4 py-3 gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <input 
                            type="text" 
                            id="students-search" 
                            placeholder="Search by name, student ID, or email..." 
                            class="input input-bordered w-full rounded-lg pr-10"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    
                    <!-- Refresh Button -->
                    <button id="refresh-students-btn" onclick="refreshStudents()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh students list">
                        <svg id="refresh-students-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>

                <!-- Students Table -->
                <div class="bg-white rounded-2xl p-6 shadow-sm content-area-auto">
                    <div class="overflow-x-auto">
                        <table class="table table-fixed w-full">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="w-[10%] text-center">
                                        <button id="student-id-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200" title="Sort by Student ID">
                                            Student ID
                                            <span id="student-id-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="w-[18%] text-center">
                                        <button id="full-name-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200" title="Sort by Full Name">
                                            Full Name
                                            <span id="full-name-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="w-[22%] text-center">
                                        <button id="email-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200" title="Sort by Email">
                                            Email
                                            <span id="email-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="w-[12%] text-center">
                                        <button id="email-verified-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200" title="Sort by Email Verified">
                                            Email Verified
                                            <span id="email-verified-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="w-[12%] text-center">
                                        <button id="approved-hours-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200" title="Sort by Approved Hours">
                                            Approved Hours
                                            <span id="approved-hours-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="w-[10%] text-center">
                                        <button id="status-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200" title="Sort by Status">
                                            Status
                                            <span id="status-sort-icon" class="text-xs">⇅</span>
                                        </button>
                                    </th>
                                    <th class="w-[11%] text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="students-table-body">
                                <tr id="students-loading-row">
                                    <td colspan="7" class="text-center py-8">
                                        <span class="loading loading-spinner loading-lg text-primary-purple"></span>
                                        <p class="mt-2 text-text-muted">Loading students...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Settings Page -->
            <div id="settings-page" class="page-content hidden">
                <div class="flex items-center justify-between p-4">
                    <h4 class="text-4xl font-bold text-primary-purple">Settings</h4>
                    <label class="label cursor-pointer flex items-center gap-3">
                        <span id="theme-label" class="text-sm text-gray-600">Light theme</span>
                        <input id="theme-toggle" type="checkbox" class="toggle toggle-primary">
                    </label>
                </div>
                
                <div class="flex-1 bg-white rounded-2xl p-6 shadow-sm flex flex-col gap-6">
                    <!-- Change Name Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-bold text-text-header mb-4">Change Name</h2>
                        <form id="name-change-form" class="space-y-4 max-w-md">
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text font-semibold">Full Name</span>
                                </div>
                                <input id="admin-name" type="text" value="{{ auth()->guard('superadmin')->user()->name }}" placeholder="Enter your full name" class="input input-bordered w-full rounded-lg" required>
                            </label>
                            
                            <div class="pt-4 flex justify-end">
                                <button type="button" id="save-name-button" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                                    Update Name
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Section -->
                    <div>
                        <h2 class="text-xl font-bold text-text-header mb-4">Change Password</h2>
                        <p class="text-sm text-text-muted mb-4">A verification email will be sent to <strong>{{ auth()->guard('superadmin')->user()->email }}</strong> to confirm your password change.</p>
                        
                        <form id="password-change-form" class="space-y-4 max-w-md">
                        <!-- Current Password -->
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Current Password</span>
                            </div>
                            <label class="input input-bordered flex items-center gap-2 rounded-lg">
                                <input id="current-password" type="password" placeholder="" class="grow" required>
                                <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('current-password')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                    </svg>
                                </button>
                            </label>
                        </label>

                        <!-- New Password -->
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">New Password</span>
                            </div>
                            <label class="input input-bordered flex items-center gap-2 rounded-lg">
                                <input id="new-password" type="password" placeholder="" class="grow" required>
                                <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('new-password')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                    </svg>
                                </button>
                            </label>
                        </label>

                        <!-- Confirm Password -->
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text font-semibold">Confirm Password</span>
                            </div>
                            <label class="input input-bordered flex items-center gap-2 rounded-lg">
                                <input id="confirm-password" type="password" placeholder="" class="grow" required>
                                <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('confirm-password')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                    </svg>
                                </button>
                            </label>
                        </label>

                        <div class="pt-4 flex justify-end">
                            <button type="button" id="save-password-button" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                                Request Password Change
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

            <!-- Support Tickets Page -->
            <div id="support-page" class="page-content hidden">
                <div class="p-4 flex justify-between items-center">
                    <div>
                        <h4 class="text-4xl font-bold text-primary-purple">Support Tickets</h4>
                        <p class="text-sm text-gray-600 mt-2">Review and resolve student support requests</p>
                    </div>
                    <button onclick="refreshSupportTickets()" id="refresh-support-tickets-btn" class="btn btn-outline btn-primary rounded-lg" title="Refresh support tickets">
                        <svg id="refresh-support-tickets-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                </div>
                
                <div class="flex-1 bg-white rounded-2xl p-6 shadow-sm overflow-y-auto">
                    <div class="mb-4">
                        <input type="text" id="ticket-search-input" placeholder="Search tickets by ID, student name, type, or details..." class="input input-bordered w-full max-w-md rounded-lg" />
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr class="bg-base-200">
                                    <th class="font-semibold text-text-header">Ticket ID</th>
                                    <th class="font-semibold text-text-header">Student Name</th>
                                    <th class="font-semibold text-text-header">Issue Type</th>
                                    <th class="font-semibold text-text-header">Details</th>
                                    <th class="font-semibold text-text-header">Status</th>
                                </tr>
                            </thead>
                            <tbody id="ticket-table-body">
                                <tr><td colspan="5" class="text-center text-gray-500 py-4">Loading tickets...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- FAQs Page -->
            <div id="faqs-page" class="page-content hidden">
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
                            <p class="text-text-muted">No. Once submitted, you can't edit it. Wait for feedback from your adviser if revisions are needed.</p>
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

    <!-- Modals -->
    <!-- Verify Modal -->
    <dialog id="verify_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Verify Submission</h3>
            <p class="py-4">Are you sure you want to verify this submission? This will move it to "For Approval" status.</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-verify-btn" class="btn btn-action-verify text-white" style="background-color: #13AAAA;">
                        Yes, verify
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Approve Modal -->
    <dialog id="approve_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Approve Submission</h3>
            <p class="py-4">Are you sure you want to approve this submission?</p>
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-r-lg mb-4" role="alert">
                <p class="font-bold">Important Notice</p>
                <p>Once approved, this record will now appear on the Students page and this action cannot be undone.</p>
            </div>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-approve-btn" class="btn bg-success-green hover:bg-success-green-hover text-white">
                        Yes, approve
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Reject Modal with Reason -->
    <dialog id="reject_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-text-header">Reject Submission</h3>
            <p class="py-4 text-text-body">Please select or provide a reason for rejecting this submission. The student will be notified.</p>
            
            <label class="form-control w-full mb-4">
                <div class="label">
                    <span class="label-text font-semibold">Rejection Reason</span>
                </div>
                <select id="rejection-reason-select" class="select select-bordered w-full">
                    <option disabled selected value="">Select a reason</option>
                    <option value="Incorrect or Invalid Information&#10;The provided details do not match official PLV records or contain false information.">
                        Incorrect or Invalid Information
                    </option>
                    <option value="Duplicate Submission&#10;The same form or request has already been submitted and is recorded in the system.">
                        Duplicate Submission
                    </option>
                    <option value="Late Submission&#10;The form was filed beyond the official deadline or submission period.">
                        Late Submission
                    </option>
                    <option value="Others">Others</option>
                </select>
            </label>
            
            <label id="other-reason-label" class="form-control w-full hidden">
                <div class="label">
                    <span class="label-text font-semibold">Specify Reason</span>
                </div>
                <textarea 
                    id="reject-reason-textarea" 
                    class="textarea textarea-bordered h-24 resize-none focus:outline-none focus:border-primary-purple" 
                    placeholder="Please specify the reason for rejection..."
                ></textarea>
            </label>
            
            <div class="modal-action mt-6">
                <form method="dialog" class="flex gap-2">
                    <button class="btn btn-ghost" onclick="resetRejectModal()">Cancel</button>
                    <button id="confirm-reject-btn" type="button" class="btn bg-danger-red hover:bg-danger-red-hover text-white">
                        Yes, reject
                    </button>
                </form>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop" onclick="resetRejectModal()">
            <button>close</button>
        </form>
    </dialog>

    <!-- Activity Details Modal -->
    <dialog id="activity_details_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <h3 class="font-bold text-lg text-text-header mb-4">Activity on <span id="activity-date"></span></h3>
            
            <div id="activity-details-content" class="space-y-3">
                <!-- Activity details will be loaded here -->
            </div>
            
            <div id="activity-loading" class="text-center py-8">
                <span class="loading loading-spinner loading-lg text-primary-purple"></span>
                <p class="mt-2 text-text-muted">Loading activities...</p>
            </div>
            
            <div id="activity-no-data" class="text-center py-8 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="mt-4 text-text-muted">No activity recorded on this date</p>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Logout Modal -->
    <dialog id="logout_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Log Out?</h3>
            <p class="py-4">Are you sure you want to log out?</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-logout-btn" class="btn bg-danger-red hover:bg-danger-red-hover text-white">
                        Yes, log out
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Student Edit Modal -->
    <dialog id="student_edit_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl p-6 relative">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <h3 class="font-bold text-2xl text-primary-purple mb-6">Student Profile</h3>
            
            <form id="student-edit-form" class="space-y-4">
                <input type="hidden" id="edit-student-user-id" />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Full Name</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit-student-name" 
                            class="input input-bordered w-full" 
                            required 
                        />
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Student ID</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit-student-id" 
                            class="input input-bordered w-full" 
                            required 
                        />
                    </div>
                </div>
                
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Email Address</span>
                    </label>
                    <input 
                        type="email" 
                        id="edit-student-email" 
                        class="input input-bordered w-full bg-gray-100" 
                        readonly 
                    />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Approved Hours</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit-student-approved-hours" 
                            class="input input-bordered w-full bg-gray-100" 
                            readonly 
                        />
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Account Status</span>
                        </label>
                        <select id="edit-student-status" class="select select-bordered w-full">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        
                        <!-- Inactive Account Warning -->
                        <div id="inactive-warning-box" class="hidden mt-2 p-3 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                            <div class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-red-800">Account scheduled for deletion</p>
                                    <p id="inactive-countdown-text" class="text-xs text-red-700 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-action justify-between">
                    <button type="button" class="btn bg-danger-red hover:bg-danger-red-hover text-white" onclick="openDeleteStudentModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Account
                    </button>
                    <div class="flex gap-2">
                        <form method="dialog" class="inline">
                            <button type="button" class="btn" onclick="document.getElementById('student_edit_modal').close()">Cancel</button>
                        </form>
                        <button type="submit" class="btn bg-success-green hover:bg-success-green-hover text-white">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Student Confirmation Modal -->
    <dialog id="delete_student_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-danger-red">Delete Student Account</h3>
            <p class="py-4">Are you sure you want to permanently delete this student account?</p>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-4" role="alert">
                <p class="font-bold">Warning</p>
                <p>This action cannot be undone. All data associated with this student will be permanently deleted from the database.</p>
            </div>
            <div class="space-y-2 mb-4">
                <p class="text-sm"><strong>Student Name:</strong> <span id="delete-student-name-display"></span></p>
                <p class="text-sm"><strong>Student ID:</strong> <span id="delete-student-id-display"></span></p>
                <p class="text-sm"><strong>Email:</strong> <span id="delete-student-email-display"></span></p>
            </div>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-delete-student-btn" type="button" class="btn bg-danger-red hover:bg-danger-red-hover text-white">
                        Yes, Delete Account
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Submission Details Modal -->
    <dialog id="submission_details_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl p-6 relative">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <h3 class="font-bold text-lg text-text-header mb-6">Social Contract Record</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="col-span-full">
                    <label class="details-label">Event name</label>
                    <input type="text" id="details-event-name" class="details-input" readonly>
                </div>
                
                <div class="col-span-full">
                    <label class="details-label">Supervisor name</label>
                    <input type="text" id="details-supervisor-name" class="details-input" readonly>
                </div>
                
                <div class="col-span-full">
                    <label class="details-label">Venue</label>
                    <input type="text" id="details-venue" class="details-input" readonly>
                </div>
                
                <div>
                    <label class="details-label">Date</label>
                    <div class="relative">
                        <input type="text" id="details-date" class="details-input pr-10" readonly>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                
                <div>
                    <label class="details-label">Hours Rendered</label>
                    <input type="text" id="details-hours-rendered" class="details-input" readonly>
                </div>
                
                <div class="col-span-full">
                    <label class="details-label">Name of Organizing Committee</label>
                    <input type="text" id="details-organizing-committee" class="details-input" readonly>
                </div>
            </div>
            
            <div id="details-status-section" class="mt-6">
                <label class="details-label">Status</label>
                <div class="flex items-center gap-2">
                    <div id="details-status-badge" class="status-badge"></div>
                    <span id="details-action-date" class="text-sm text-gray-500"></span>
                </div>
            </div>
            
            <div id="details-reason-container" class="hidden mt-4 border-t pt-4">
                <label class="details-label">Reason for Rejection</label>
                <p class="font-medium text-badge-rejected-text whitespace-pre-line bg-gray-50 p-3 rounded-lg" id="details-reason-text"></p>
            </div>
            
            <div id="details-action-buttons" class="mt-6 flex gap-2"></div>
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
                    <p class="text-sm text-text-muted" id="status-modal-subtitle">Showing all records with this status this week</p>
                </div>
            </div>
            
            <div class="divider my-4"></div>
            
            <!-- Records Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th class="text-center">Student ID</th>
                            <th class="text-center">Student Name</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Event Name</th>
                            <th class="text-center">Venue</th>
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
                <p class="text-gray-400 text-sm mt-2">There are no records with this status this week.</p>
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

    <!-- Submit Ticket Modal -->
    <dialog id="submit_ticket_modal" class="modal">
        <!-- Removed: Super admin doesn't submit tickets -->
    </dialog>

    <!-- Ticket Details Modal for Super Admin -->
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

                <div class="pt-4 border-t" id="resolve-action-container">
                    <p class="text-sm font-semibold text-gray-600 mb-2">Actions</p>
                    <button type="button" id="resolve-ticket-btn" class="btn bg-green-600 hover:bg-green-700 text-white rounded-lg w-full">
                        Mark as Resolved
                    </button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Toast Container -->
    <div id="toast-root" class="toast toast-bottom toast-end fixed bottom-4 right-4 z-[5000] space-y-2"></div>

    <!-- Scripts -->
    <script>
        // Global variables
        var activeRow = null;
        var allSubmissions = []; // Store all submissions data
        var lastSubmissionsData = null; // Track last loaded data to prevent unnecessary updates
        var BASE_PATH = @json($BASE_PATH);
        
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
        
        // CSRF Cookie Helper Functions
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
                    }
                }
            } catch (e) {
                console.warn('Could not fetch CSRF cookie:', e);
            }
        }
        
        var hoursSortDirection = 'desc'; // 'asc' or 'desc'
        var studentIdSortDirection = 'desc'; // 'asc' or 'desc'
        var dateSortDirection = 'desc'; // 'asc' or 'desc'
        var studentNameSortDirection = 'desc'; // 'asc' or 'desc'
        var eventNameSortDirection = 'desc'; // 'asc' or 'desc'
        var organizationSortDirection = 'desc'; // 'asc' or 'desc'
        var supervisorSortDirection = 'desc'; // 'asc' or 'desc'
        var actionDateSortDirection = 'desc'; // 'asc' or 'desc'
        var statusSortDirection = 'desc'; // 'asc' or 'desc'
        var currentSortBy = null; // 'hours', 'studentid', 'date', 'studentname', 'eventname', 'organization', 'supervisor', 'actiondate', 'status'
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
                    if (!targetTab) {
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
                var indicators = ['studentid', 'studentname', 'eventname', 'organization', 'supervisor', 'hours', 'date', 'actiondate', 'status'];
                indicators.forEach(function(id) {
                    if (id !== exceptId) {
                        var indicator = document.getElementById(id + '-sort-indicator');
                        if (indicator) indicator.textContent = '▼';
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
                    studentIdSortIndicator.textContent = studentIdSortDirection === 'asc' ? '▲' : '▼';
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
                    studentNameSortIndicator.textContent = studentNameSortDirection === 'asc' ? '▲' : '▼';
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
                    eventNameSortIndicator.textContent = eventNameSortDirection === 'asc' ? '▲' : '▼';
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
                    organizationSortIndicator.textContent = organizationSortDirection === 'asc' ? '▲' : '▼';
                    renderSubmissions(allSubmissions, 'organization');
                };
            }
            
            // Supervisor sort
            var supervisorSortToggle = document.getElementById('supervisor-sort-toggle');
            var supervisorSortIndicator = document.getElementById('supervisor-sort-indicator');
            if (supervisorSortToggle && supervisorSortIndicator) {
                supervisorSortToggle.onclick = function(e) {
                    e.preventDefault();
                    currentSortBy = 'supervisor';
                    supervisorSortDirection = supervisorSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllIndicators('supervisor');
                    supervisorSortIndicator.textContent = supervisorSortDirection === 'asc' ? '▲' : '▼';
                    renderSubmissions(allSubmissions, 'supervisor');
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
                    hoursSortIndicator.textContent = hoursSortDirection === 'asc' ? '▲' : '▼';
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
                    dateSortIndicator.textContent = dateSortDirection === 'asc' ? '▲' : '▼';
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
                    actionDateSortIndicator.textContent = actionDateSortDirection === 'asc' ? '▲' : '▼';
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
                    statusSortIndicator.textContent = statusSortDirection === 'asc' ? '▲' : '▼';
                    renderSubmissions(allSubmissions, 'status');
                };
            }
        }

        // Render support tickets table
        function renderTicketsTable(tickets = allTickets) {
            const tbody = document.getElementById('ticket-table-body');
            if (!tbody) return;

            if (!tickets || tickets.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-gray-500 py-4">No tickets found.</td></tr>';
                return;
            }

            let statusBadges = {
                'Pending': '<span class="badge bg-yellow-100 text-yellow-800 border-0">Pending</span>',
                'Resolved': '<span class="badge bg-green-100 text-green-800 border-0">Resolved</span>',
                'Closed': '<span class="badge bg-gray-100 text-gray-800 border-0">Closed</span>'
            };

            tbody.innerHTML = '';
            tickets.forEach(ticket => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 cursor-pointer';
                tr.onclick = () => showTicketDetails(ticket.id);
                
                const shortDetails = (ticket.details || '').substring(0, 100) + (ticket.details && ticket.details.length > 100 ? '...' : '');
                
                tr.innerHTML = `
                    <td class="font-medium text-text-header">${ticket.id}</td>
                    <td class="text-text-header">${ticket.student_name || 'N/A'}</td>
                    <td class="text-text-header">${ticket.type}</td>
                    <td class="text-text-muted text-sm" title="${ticket.details}">${shortDetails}</td>
                    <td>
                        <div class="flex flex-col gap-1">
                            ${statusBadges[ticket.status] || ticket.status}
                            <span class="text-xs text-gray-500">${ticket.date}</span>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Update table headers based on active tab
        function updateTableHeaders(tabName) {
            var headerRow = document.getElementById('table-header-row');
            if (!headerRow) return;
            
            var normalizedTab = tabName.toLowerCase().trim();
            
            if (normalizedTab === 'archived') {
                // Archived tab: show Status with filter and Rejection Reason columns
                headerRow.innerHTML = `
                    <th class="w-[9%] text-center">
                        <button id="studentid-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Student ID">
                            Student ID
                            <span id="studentid-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[12%] text-center">
                        <button id="studentname-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Student Name">
                            Student Name
                            <span id="studentname-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[14%] text-center">
                        <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Event Name">
                            Event Name
                            <span id="eventname-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[12%] text-center">
                        <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Organization">
                            Organization
                            <span id="organization-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[11%] text-center">
                        <button id="supervisor-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Supervisor">
                            Supervisor
                            <span id="supervisor-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[9%] text-center">
                        <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Hours Rendered">
                            Hours
                            <span id="hours-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[9%] text-center">
                        <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Date">
                            Date
                            <span id="date-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[10%] text-center">
                        <div class="flex items-center justify-center gap-1">
                            <button id="status-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Status">
                                Status
                                <span id="status-sort-indicator">▼</span>
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
                `;
            } else {
                // Pending and For Approval tabs: show Action column with status filter
                headerRow.innerHTML = `
                    <th class="w-[10%] text-center">
                        <button id="studentid-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Student ID">
                            Student ID
                            <span id="studentid-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[15%] text-center">
                        <button id="studentname-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Student Name">
                            Student Name
                            <span id="studentname-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[20%] text-center">
                        <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Event Name">
                            Event Name
                            <span id="eventname-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[15%] text-center">
                        <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Organization">
                            Organization
                            <span id="organization-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[12%] text-center">
                        <button id="supervisor-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Supervisor">
                            Supervisor
                            <span id="supervisor-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[11%] text-center">
                        <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Hours Rendered">
                            Hours Rendered
                            <span id="hours-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[10%] text-center">
                        <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1" title="Sort by Date">
                            Date
                            <span id="date-sort-indicator">▼</span>
                        </button>
                    </th>
                    <th class="w-[18%] text-center">Action</th>
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
                        case 'supervisor':
                            direction = supervisorSortDirection;
                            break;
                        case 'actiondate':
                            direction = actionDateSortDirection;
                            break;
                        case 'status':
                            direction = statusSortDirection;
                            break;
                    }
                    indicator.textContent = direction === 'asc' ? '▲' : '▼';
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
                    
                    if (actionButtons && statusBadge) {
                        if (isArchivedTab) {
                            // In Archived tab: hide action buttons, show status badge
                            actionButtons.style.display = 'none';
                            statusBadge.style.display = 'block';
                        } else {
                            // In For Approval tab: show action buttons, hide status badge
                            actionButtons.style.display = 'block';
                            statusBadge.style.display = 'none';
                        }
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
            
            fetch(`${BASE_PATH}/super-admin/api/dashboard-stats`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                },
                credentials: 'same-origin'
            })
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
            
            // Add timestamp to URL to prevent caching
            var timestamp = new Date().getTime();
            
            // Fetch submissions from API
            fetch(`${BASE_PATH}/super-admin/api/submissions?_=${timestamp}`, {
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
                    var dateA = new Date(a.date || 0);
                    var dateB = new Date(b.date || 0);
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
            } else if (sortBy === 'supervisor') {
                sortedSubmissions.sort(function(a, b) {
                    var supA = (a.supervisor_name || '').toString().toLowerCase();
                    var supB = (b.supervisor_name || '').toString().toLowerCase();
                    return supervisorSortDirection === 'asc' ? supA.localeCompare(supB) : supB.localeCompare(supA);
                });
            } else if (sortBy === 'actiondate') {
                sortedSubmissions.sort(function(a, b) {
                    // Parse action_date in format mm-dd-yyyy
                    var parseActionDate = function(dateStr) {
                        if (!dateStr || dateStr === '—') return new Date(0);
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
                
                // Map status to tab: Pending → Pending tab, Verified → BOTH For Approval AND Archived, Approved/Rejected → Archived tab only
                // Use special handling for Verified: it appears in both tabs
                var dataStatus = isPending ? 'Pending' : (isVerified ? 'For Approval,Archived' : 'Archived');
                var dataArchiveStatus = (isApproved || isRejected || isVerified) ? status : '';
                
                var dateStr = record.date ? formatDate(record.date) : '—';
                
                // Escape rejection reason for HTML attribute
                var rejectionReason = (record.rejection_reason || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                
                // Get action date
                var actionDateStr = record.action_date || '';
                
                html += '<tr data-status="' + dataStatus + '" ' +
                        (dataArchiveStatus ? 'data-archive-status="' + dataArchiveStatus + '" ' : '') +
                        'data-record-id="' + record.id + '" ' +
                        'data-venue="' + (record.venue || '') + '" ' +
                        'data-organization="' + (record.organization || '') + '" ' +
                        'data-supervisor-name="' + (record.supervisor_name || '') + '" ' +
                        'data-rejection-reason="' + rejectionReason + '" ' +
                        'data-action-date="' + actionDateStr + '" ' +
                        'class="hover cursor-pointer" onclick="openDetailsModal(this)">' +
                        '<td class="text-center">' + (record.student_id || '—') + '</td>' +
                        '<td class="text-center">' + (record.student_name || '—') + '</td>' +
                        '<td class="text-center">' + (record.event_name || '—') + '</td>' +
                        '<td class="text-center">' + (record.organization || '—') + '</td>' +
                        '<td class="text-center">' + (record.supervisor_name || '—') + '</td>' +
                        '<td class="text-center">' + (record.hours_rendered || 0) + ' hours</td>' +
                        '<td class="text-center">' + dateStr + '</td>';
                
                // Action column logic: 
                // - Pending tab: show Verify/Reject buttons for Pending records
                // - For Approval tab: show Approve/Reject buttons for Verified records  
                // - Archived tab: show status badge for Approved/Rejected/Verified records
                html += '<td class="text-center">';
                
                if (isPending) {
                    // Pending records always show action buttons
                    html += '<div class="space-x-2">' +
                            '<button class="btn btn-action btn-action-verify" onclick="openVerifyModal(this,event)">Verify</button>' +
                            '<button class="btn btn-action btn-action-reject" onclick="openRejectModal(this,event)">Reject</button>' +
                            '</div>';
                } else if (isVerified) {
                    // Verified records: Show both action buttons AND status badge (will be filtered by tab)
                    // Action buttons (hidden in Archived tab via CSS class)
                    html += '<div class="space-x-2 for-approval-actions">' +
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
        
        // Format date helper
        function formatDate(dateStr) {
            try {
                var date = new Date(dateStr);
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                var year = String(date.getFullYear()).slice(-2);
                return month + '-' + day + '-' + year;
            } catch (e) {
                return dateStr;
            }
        }
        
        // Update dashboard statistics
        function updateDashboardStats(submissions) {
            var now = new Date();
            var weekAgo = new Date(now);
            weekAgo.setDate(weekAgo.getDate() - 7);
            
            var pending = 0, approved = 0, rejected = 0;
            
            submissions.forEach(function(record) {
                var recordDate = new Date(record.updated_at || record.created_at || record.date);
                var isThisWeek = recordDate >= weekAgo && recordDate <= now;
                
                // Count Pending and Verified (admin verified, awaiting super admin decision) as pending
                if (record.status === 'Pending' || record.status === 'Verified') {
                    pending++;
                }
                if (record.status === 'Approved' && isThisWeek) {
                    approved++;
                }
                if (record.status === 'Rejected' && isThisWeek) {
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
                // Show empty state
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

        // Open details modal
        function openDetailsModal(r) {
            activeRow = r;
            var s = r.dataset.status;
            var v = r.dataset.venue;
            var en = r.cells[2].textContent;
            var org = r.cells[3].textContent;
            var sup = r.cells[4].textContent;
            var dt = r.cells[6].textContent;
            var hr = r.cells[5].textContent;
            var rejectionReason = r.dataset.rejectionReason || '';
            var actionDate = r.dataset.actionDate || '';
            
            document.getElementById('details-event-name').value = en;
            document.getElementById('details-supervisor-name').value = sup;
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
                    document.documentElement.setAttribute('data-theme', m);
                    try {
                        localStorage.setItem('scms_superadmin_theme', m);
                    } catch(_) {}
                    if (lb) lb.textContent = (m === 'dark') ? 'Dark theme' : 'Light theme';
                    if (tg) tg.checked = (m === 'dark');
                };
                
                var sv = 'light';
                try {
                    sv = (localStorage.getItem('scms_superadmin_theme') === 'dark') ? 'dark' : 'light';
                } catch(_) {}
                
                ap(sv);
                
                if (tg) {
                    tg.addEventListener('change', function() {
                        ap(tg.checked ? 'dark' : 'light');
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

        // Activity Calendar Variables
        var currentCalendarYear = new Date().getFullYear();
        var activityDataCache = {};

        // Generate Activity Calendar (January to December for selected year)
        function generateActivityCalendar() {
            var container = document.getElementById('activity-calendar');
            if (!container) return;
            
            // Update year display
            document.getElementById('calendar-year').textContent = currentCalendarYear;
            
            // Disable next button if viewing current year
            var nextBtn = document.getElementById('next-year-btn');
            var currentYear = new Date().getFullYear();
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
                if (level === 0) return '#E5E7EB';
                if (level <= 2) return '#E5D4FF';
                if (level <= 4) return '#C9A9FF';
                if (level <= 6) return '#A475FF';
                return '#6D28D9';
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
                        var color = getColor(level);
                        
                        // Check if this date is today in Philippines timezone
                        var isToday = date.getFullYear() === philippinesToday.getFullYear() && 
                                     date.getMonth() === philippinesToday.getMonth() && 
                                     date.getDate() === philippinesToday.getDate();
                        var isFuture = date > philippinesToday;
                        var isInYear = date.getFullYear() === currentCalendarYear;
                        var isInThisMonth = date.getMonth() === monthIndex;
                        
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
            
            var html = '';
            students.forEach(function(student) {
                var status = student.status || 'active';
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
                    : '<span class="badge badge-warning text-white">✗ Not Verified</span>';
                
                var approvedHours = student.approved_hours || 0;
                var hoursDisplay = '<span class="font-semibold text-primary-purple">' + approvedHours + ' hours</span>';
                
                html += '<tr class="hover cursor-pointer" onclick="openStudentEditModal(' + student.id + ')">' +
                        '<td class="text-center">' + (student.student_id || '—') + '</td>' +
                        '<td class="text-center">' + (student.name || '—') + '</td>' +
                        '<td class="text-center">' + (student.email || '—') + '</td>' +
                        '<td class="text-center">' + verifiedBadge + '</td>' +
                        '<td class="text-center">' + hoursDisplay + '</td>' +
                        '<td class="text-center">' + statusBadge + '</td>' +
                        '<td class="text-center">' +
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
            document.getElementById('delete-student-name-display').textContent = student.name || '—';
            document.getElementById('delete-student-id-display').textContent = student.student_id || '—';
            document.getElementById('delete-student-email-display').textContent = student.email || '—';
            
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
            loadSubmissions();
            initPendingRequestsChart();
            generateActivityCalendar();
            
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
            
            // Helper function to reset all indicators
            function resetAllSortIndicators() {
                document.getElementById('hours-sort-indicator').textContent = '▼';
                document.getElementById('studentid-sort-indicator').textContent = '▼';
                document.getElementById('date-sort-indicator').textContent = '▼';
                document.getElementById('studentname-sort-indicator').textContent = '▼';
                document.getElementById('eventname-sort-indicator').textContent = '▼';
                document.getElementById('organization-sort-indicator').textContent = '▼';
                document.getElementById('supervisor-sort-indicator').textContent = '▼';
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
                    hoursSortIndicator.textContent = hoursSortDirection === 'asc' ? '▲' : '▼';
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
                    studentIdSortIndicator.textContent = studentIdSortDirection === 'asc' ? '▲' : '▼';
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
                    dateSortIndicator.textContent = dateSortDirection === 'asc' ? '▲' : '▼';
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
                    studentNameSortIndicator.textContent = studentNameSortDirection === 'asc' ? '▲' : '▼';
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
                    eventNameSortIndicator.textContent = eventNameSortDirection === 'asc' ? '▲' : '▼';
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
                    organizationSortIndicator.textContent = organizationSortDirection === 'asc' ? '▲' : '▼';
                    renderSubmissions(allSubmissions, 'organization');
                });
            }
            
            // Supervisor sort toggle event listener
            var supervisorSortToggle = document.getElementById('supervisor-sort-toggle');
            var supervisorSortIndicator = document.getElementById('supervisor-sort-indicator');
            if (supervisorSortToggle && supervisorSortIndicator) {
                supervisorSortToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentSortBy = 'supervisor';
                    supervisorSortDirection = supervisorSortDirection === 'asc' ? 'desc' : 'asc';
                    resetAllSortIndicators();
                    supervisorSortIndicator.textContent = supervisorSortDirection === 'asc' ? '▲' : '▼';
                    renderSubmissions(allSubmissions, 'supervisor');
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

            // Logout handler
            document.getElementById('confirm-logout-btn').addEventListener('click', function() {
                // Call the logout API endpoint
                fetch(`${BASE_PATH}/super-admin/logout`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                })
                .then(function() {
                    // Redirect to super admin login page after successful logout
                    window.location.href = `${BASE_PATH}/super-admin/login`;
                })
                .catch(function(error) {
                    console.error('Logout error:', error);
                    // Still redirect even if there's an error
                    window.location.href = `${BASE_PATH}/super-admin/login`;
                });
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
    <script src="{{ asset('js/session-keeper.js') }}"></script>
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
</body>
</html>