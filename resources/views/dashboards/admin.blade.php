<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<title>Admin - Student Contract Management</title>
<script>
(function(){try{var s=localStorage.getItem('scms_admin_theme');if(s==='dark'||s==='light'){document.documentElement.setAttribute('data-theme',s);}}catch(_){}})();
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
.status-badge.rejected{background-color:#FFD1D3;color:#CC525D}
.scms-badge{display:inline-flex;align-items:center;justify-content:center;font-weight:600;border-radius:9999px;padding:0.25rem 0.5rem;font-size:0.75rem;line-height:1;border:0!important}
.scms-badge--pending{background-color:#FAEAD0!important;color:#E29C44!important}
.scms-badge--verified{background-color:#CCEED6!important;color:#399552!important}
.scms-badge--rejected{background-color:#FFD7DB!important;color:#CC525D!important}
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
[data-theme="dark"] .scms-badge--verified{background-color:#009b29ff!important;color:#ffffffff!important}
[data-theme="dark"] .scms-badge--rejected{background-color:#b8000fff!important;color:#ffffffff!important}
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
</style>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-custom">
@php
    $BASE_PATH = rtrim(parse_url(url('/'), PHP_URL_PATH) ?? '', '/');
    $fullName = trim(auth('admin')->user()->name ?? 'Admin');
    $nameWords = $fullName !== '' ? preg_split('/\s+/', $fullName) : [];
    $initials = '';
    if (!empty($nameWords)) {
        $initials = mb_strtoupper(mb_substr($nameWords[0], 0, 1));
        if (isset($nameWords[1]) && mb_strlen($nameWords[1]) > 0) {
            $initials .= mb_strtoupper(mb_substr($nameWords[1], 0, 1));
        }
    }
    if (!$initials)
        $initials = 'AD';
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
                <p class="text-sm text-gray-500">Administrator</p>
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
            </ul>

            <!-- Bottom Navigation -->
            <ul class="menu p-0">
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
                    <form id="logout-form-visible" 
                          action="{{ route('admin.logout') }}" 
                          method="POST" 
                          class="m-0 p-0 pl-2 pr-0" 
                          novalidate>
                        @csrf
                        <button id="logout-button-visible" 
                                type="button" 
                                class="py-3 px-0 w-full text-left flex items-center gap-2 min-h-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col gap-6" id="page-container">
            
            <!-- Dashboard Overview Page -->
            <div id="dashboard-page" class="page-content flex-col flex-1-dynamic">
                <h1 class="text-4xl font-bold text-primary-purple px-4 mb-6">Admin Dashboard</h1>
                
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
                                    {{ Str::of(auth('admin')->user()->name)->explode(' ')->first() }}
                                </span>
                            </h2>
                            <br>
                            <p class="text-white text-base mt-1">
                                Manage student submissions and <br>
                                monitor social contract compliance.
                            </p>
                            <p class="text-white font-bold text-base mt-1">
                                Empowering ka-VITS through efficient administration!
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
                    <h2 class="text-xl font-bold text-text-header mb-1">Weekly Summary</h2>
                    <p class="text-sm text-text-muted mb-4">Contract requests overview for this week</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Pending Requests -->
                        <div class="bg-gradient-pending p-4 rounded-2xl flex flex-col gap-2">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="pending-requests-count">0</span> Requests</h3>
                                <p class="text-yellow-800 font-semibold">Pending This Week</p>
                                <p class="text-xs text-text-muted mt-1">Awaiting review</p>
                            </div>
                        </div>
                        
                        <!-- Accepted Requests -->
                        <div class="bg-gradient-accepted p-4 rounded-2xl flex flex-col gap-2">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="accepted-requests-count">0</span> Requests</h3>
                                <p class="text-green-800 font-semibold">Accepted This Week</p>
                                <p class="text-xs text-text-muted mt-1">Successfully verified</p>
                            </div>
                        </div>
                        
                        <!-- Rejected Requests -->
                        <div class="bg-gradient-rejected p-4 rounded-2xl flex flex-col gap-2">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="rejected-requests-count">0</span> Requests</h3>
                                <p class="text-red-800 font-semibold">Rejected This Week</p>
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
                    </div>
                    <div id="activity-calendar" class="overflow-x-auto pb-2">
                        <!-- Calendar will be dynamically generated by JavaScript -->
                        <!-- Data source: loadActivityData() function calls API endpoint -->
                    </div>
                </div>
            </div>
            
            <!-- Submission Page -->
            <div id="submission-page" class="page-content hidden flex-col flex-1-dynamic">
                <h1 class="text-4xl font-bold text-primary-purple px-4">Submission Management</h1>
                
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 px-4 gap-4 md:gap-0">
                    <!-- Tabs -->
                    <div class="flex space-x-2 custom-tab-wrapper">
                        <a role="tab" class="custom-tab custom-tab-active" onclick="filterSubmissions('Pending',this)">Pending</a>
                        <a role="tab" class="custom-tab" onclick="filterSubmissions('Archived',this)">Archived</a>
                    </div>
                    
                    <!-- Search -->
                    <label class="input input-bordered flex items-center gap-2 rounded-lg bg-white h-10 w-full md:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                            <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd"/>
                        </svg>
                        <input type="text" class="grow bg-transparent" placeholder="Search" id="search-input">
                    </label>
                </div>

                <!-- Submission Table -->
                <div class="bg-white rounded-2xl p-6 shadow-sm content-area-auto">
                    <div class="overflow-x-auto">
                        <table class="table table-fixed w-full">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="w-[10%] text-center">Student ID</th>
                                    <th class="w-[15%] text-center">Student Name</th>
                                    <th class="w-[20%] text-center">Event Name</th>
                                    <th class="w-[15%] text-center">Organization</th>
                                    <th class="w-[12%] text-center">Hours Rendered</th>
                                    <th class="w-[10%] text-center">Date</th>
                                    <th class="w-[18%] text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="submission-table-body">
                                <!-- Data will be loaded dynamically from database -->
                                <tr id="loading-row">
                                    <td colspan="7" class="text-center py-8">
                                        <span class="loading loading-spinner loading-lg text-primary-purple"></span>
                                        <p class="mt-2 text-text-muted">Loading submissions...</p>
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
                    <h2 class="text-xl font-bold text-text-header mb-6">Change Password</h2>
                    
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
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <!-- Verify Modal -->
    <dialog id="verify_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Verify Submission</h3>
            <p class="py-4">Are you sure you want to verify this submission?</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-verify-btn" class="btn bg-success-green hover:bg-success-green-hover text-white">
                        Yes, verify
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Reject Modal -->
    <dialog id="reject_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Reject Submission</h3>
            <p class="py-4">Are you sure you want to reject this submission?</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-reject-btn" class="btn bg-danger-red hover:bg-danger-red-hover text-white">
                        Yes, reject
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Save Password Modal -->
    <dialog id="save_password_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Save Changes?</h3>
            <p class="py-4">Are you sure you want to save the new password?</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-save-password-btn" class="btn bg-success-green hover:bg-success-green-hover text-white">
                        Yes, save
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
                <div id="details-status-badge" class="status-badge"></div>
            </div>
            
            <div id="details-action-buttons" class="mt-6 flex gap-2"></div>
        </div>
    </dialog>

    <!-- Toast Container -->
    <div id="toast-root" class="toast toast-bottom toast-end fixed bottom-4 right-4 z-[5000] space-y-2"></div>

    <!-- Scripts -->
    <script>
        // Global variables
        var activeRow = null;
        var allSubmissions = []; // Store all submissions data
        var BASE_PATH = @json($BASE_PATH);

        // Load submissions from database
        function loadSubmissions() {
            var tbody = document.getElementById('submission-table-body');
            
            // Show loading state
            tbody.innerHTML = '<tr id="loading-row"><td colspan="7" class="text-center py-8">' +
                '<span class="loading loading-spinner loading-lg text-primary-purple"></span>' +
                '<p class="mt-2 text-text-muted">Loading submissions...</p></td></tr>';
            
            // Fetch submissions from API
            fetch('/admin/api/submissions', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Failed to load submissions');
                }
                return response.json();
            })
            .then(function(result) {
                console.log('API Response:', result);
                if (result.success && result.data) {
                    allSubmissions = result.data;
                    renderSubmissions(result.data);
                    updateWeeklySummaryFromData(result.data);
                } else {
                    throw new Error('Invalid response format');
                }
            })
            .catch(function(error) {
                console.error('Error loading submissions:', error);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">' +
                    'Failed to load submissions. Please refresh the page.</td></tr>';
            });
        }
        
        // Render submissions in the table
        function renderSubmissions(submissions) {
            var tbody = document.getElementById('submission-table-body');
            
            if (!submissions || submissions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-text-muted">' +
                    'No submissions found.</td></tr>';
                return;
            }
            
            var html = '';
            submissions.forEach(function(record) {
                var status = record.status || 'Pending';
                var isPending = status === 'Pending';
                var isVerified = status === 'Verified';
                var isRejected = status === 'Rejected';
                
                var dataStatus = isPending ? 'Pending' : 'Archived';
                var dataArchiveStatus = isPending ? '' : status;
                
                var dateStr = record.date ? formatDate(record.date) : '—';
                
                html += '<tr data-status="' + dataStatus + '" ' +
                        (dataArchiveStatus ? 'data-archive-status="' + dataArchiveStatus + '" ' : '') +
                        'data-record-id="' + record.id + '" ' +
                        'data-venue="' + (record.venue || '') + '" ' +
                        'data-organization="' + (record.organization || '') + '" ' +
                        'class="hover cursor-pointer" onclick="openDetailsModal(this)">' +
                        '<td class="text-center">' + (record.student_id || '—') + '</td>' +
                        '<td class="text-center">' + (record.student_name || '—') + '</td>' +
                        '<td class="text-center">' + (record.event_name || '—') + '</td>' +
                        '<td class="text-center">' + (record.organization || '—') + '</td>' +
                        '<td class="text-center">' + (record.hours_rendered || 0) + ' hours</td>' +
                        '<td class="text-center">' + dateStr + '</td>' +
                        '<td class="text-center">';
                
                if (isPending) {
                    html += '<div class="space-x-2">' +
                            '<button class="btn btn-action btn-action-verify" onclick="openVerifyModal(this,event)">Verify</button>' +
                            '<button class="btn btn-action btn-action-reject" onclick="openRejectModal(this,event)">Reject</button>' +
                            '</div>';
                } else if (isVerified) {
                    html += '<span class="scms-badge scms-badge--verified">Verified</span>';
                } else if (isRejected) {
                    html += '<span class="scms-badge scms-badge--rejected">Rejected</span>';
                }
                
                html += '</td></tr>';
            });
            
            tbody.innerHTML = html;
            
            // Apply current filter
            var activeTab = document.querySelector('.custom-tab-active');
            if (activeTab) {
                filterSubmissions(activeTab.textContent.trim(), activeTab);
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
        
        // Update weekly summary from loaded data
        function updateWeeklySummaryFromData(submissions) {
            var now = new Date();
            var weekAgo = new Date(now);
            weekAgo.setDate(weekAgo.getDate() - 7);
            
            var pending = 0, accepted = 0, rejected = 0;
            
            submissions.forEach(function(record) {
                var recordDate = new Date(record.updated_at || record.created_at);
                var isThisWeek = recordDate >= weekAgo && recordDate <= now;
                
                if (record.status === 'Pending') {
                    pending++;
                } else if (record.status === 'Verified' && isThisWeek) {
                    accepted++;
                } else if (record.status === 'Rejected' && isThisWeek) {
                    rejected++;
                }
            });
            
            document.getElementById('pending-requests-count').textContent = pending;
            document.getElementById('accepted-requests-count').textContent = accepted;
            document.getElementById('rejected-requests-count').textContent = rejected;
            
            // Update the donut chart
            if (pendingRequestsChartInstance) {
                var totalCapacity = 50;
                pendingRequestsChartInstance.data.datasets[0].data = [pending, Math.max(0, totalCapacity - pending)];
                pendingRequestsChartInstance.update();
                document.getElementById('pending-requests-label').textContent = pending;
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
            
            // Load submissions when showing submission page
            if (p === 'submission') {
                loadSubmissions();
            }
        }

        // Filter submissions by status
        function filterSubmissions(s, t) {
            document.querySelectorAll('.custom-tab').forEach(function(tb) {
                tb.classList.remove('custom-tab-active');
            });
            t.classList.add('custom-tab-active');
            
            var st = document.getElementById('search-input').value.toLowerCase();
            var rs = document.querySelectorAll('#submission-table-body tr');
            
            rs.forEach(function(r) {
                var rs = r.dataset.status;
                var id = r.cells[0].textContent.toLowerCase();
                var sn = r.cells[1].textContent.toLowerCase();
                var en = r.cells[2].textContent.toLowerCase();
                var sb = r.cells[3].textContent.toLowerCase();
                var hr = r.cells[4].textContent.toLowerCase();
                var dt = r.cells[5].textContent.toLowerCase();
                var ms = id.includes(st) || sn.includes(st) || en.includes(st) || 
                         sb.includes(st) || hr.includes(st) || dt.includes(st);
                
                if (rs === s && ms) {
                    r.classList.remove('hidden');
                } else {
                    r.classList.add('hidden');
                }
            });
        }

        // Open verify modal
        function openVerifyModal(b, e) {
            e.stopPropagation();
            activeRow = b.closest('tr');
            document.getElementById('verify_modal').showModal();
        }

        // Open reject modal
        function openRejectModal(b, e) {
            e.stopPropagation();
            activeRow = b.closest('tr');
            document.getElementById('reject_modal').showModal();
        }

        // Open details modal
        function openDetailsModal(r) {
            activeRow = r;
            var s = r.dataset.status;
            var v = r.dataset.venue;
            var en = r.cells[2].textContent;
            var sn = r.cells[3].textContent;
            var dt = r.cells[5].textContent;
            var hr = r.cells[4].textContent;
            var oc = r.cells[2].textContent;
            
            document.getElementById('details-event-name').value = en;
            document.getElementById('details-supervisor-name').value = sn;
            document.getElementById('details-venue').value = v;
            document.getElementById('details-date').value = dt;
            document.getElementById('details-hours-rendered').value = hr;
            document.getElementById('details-organizing-committee').value = oc;
            
            var ss = document.getElementById('details-status-section');
            var ab = document.getElementById('details-action-buttons');
            var sb = document.getElementById('details-status-badge');
            
            sb.innerHTML = '';
            ab.innerHTML = '';
            
            if (s === 'Pending') {
                ss.classList.add('hidden');
                ab.classList.remove('hidden');
                ab.innerHTML = '<button class="btn btn-action btn-action-verify flex-1" onclick="handleDetailsVerify()">Verify</button>' +
                               '<button class="btn btn-action btn-action-reject flex-1" onclick="handleDetailsReject()">Reject</button>';
            } else {
                ss.classList.remove('hidden');
                ab.classList.add('hidden');
                var as = r.dataset.archiveStatus;
                sb.textContent = as;
                if (as === 'Verified') {
                    sb.className = 'status-badge verified';
                } else if (as === 'Rejected') {
                    sb.className = 'status-badge rejected';
                }
            }
            
            document.getElementById('submission_details_modal').showModal();
        }

        // Handle verify from details modal
        function handleDetailsVerify() {
            document.getElementById('submission_details_modal').close();
            document.getElementById('verify_modal').showModal();
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
                        localStorage.setItem('scms_admin_theme', m);
                    } catch(_) {}
                    if (lb) lb.textContent = (m === 'dark') ? 'Dark theme' : 'Light theme';
                    if (tg) tg.checked = (m === 'dark');
                };
                
                var sv = 'light';
                try {
                    sv = (localStorage.getItem('scms_admin_theme') === 'dark') ? 'dark' : 'light';
                } catch(_) {}
                
                ap(sv);
                
                if (tg) {
                    tg.addEventListener('change', function() {
                        ap(tg.checked ? 'dark' : 'light');
                    });
                }
            } catch(_) {}
        }

        // Attach logout handler
        function attachLogoutHandler() {
            try {
                var f = document.getElementById('logout-form-visible');
                var b = document.getElementById('logout-button-visible');
                if (!f || !b) return;
                
                var cm = document.querySelector('meta[name="csrf-token"]');
                var cs = cm ? cm.getAttribute('content') : '';
                
                b.replaceWith(b.cloneNode(true));
                var fb = document.getElementById('logout-button-visible');
                if (!fb) return;
                
                fb.addEventListener('click', function(e) {
                    e.preventDefault();
                    try {
                        fetch(f.action, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': cs,
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({'_token': cs}).toString(),
                            keepalive: true
                        }).finally(function() {
                            try {
                                window.location.replace(@json(route('admin.login')));
                            } catch(_) {
                                window.location.href = @json(route('admin.login'));
                            }
                        });
                    } catch(err) {
                        try {
                            window.location.replace(@json(route('admin.login')));
                        } catch(_) {
                            window.location.href = @json(route('admin.login'));
                        }
                    }
                }, {passive: true});
            } catch(_) {}
        }

        // DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            showPage('dashboard');
            initThemeToggle();
            attachLogoutHandler();
            loadActivityData(); // Load activity calendar with API data
            loadSubmissions(); // Load initial submissions data
            initPendingRequestsChart();
            
            // Confirm verify button handler
            document.getElementById('confirm-verify-btn').addEventListener('click', function() {
                if (activeRow) {
                    var recordId = activeRow.dataset.recordId;
                    
                    // TODO: Make API call to verify the submission
                    // fetch('/admin/api/submissions/' + recordId + '/verify', {...})
                    
                    // For now, update UI optimistically
                    activeRow.dataset.status = 'Archived';
                    activeRow.dataset.archiveStatus = 'Verified';
                    activeRow.cells[6].innerHTML = '<span class="scms-badge scms-badge--verified">Verified</span>';
                    
                    showToast('Submission has been verified.', 'success');
                    activeRow = null;
                    
                    // Reload submissions to get fresh data
                    loadSubmissions();
                    loadActivityData(); // Refresh calendar to show new activity
                }
            });
            
            // Confirm reject button handler
            document.getElementById('confirm-reject-btn').addEventListener('click', function() {
                if (activeRow) {
                    var recordId = activeRow.dataset.recordId;
                    
                    // TODO: Make API call to reject the submission
                    // fetch('/admin/api/submissions/' + recordId + '/reject', {...})
                    
                    // For now, update UI optimistically
                    activeRow.dataset.status = 'Archived';
                    activeRow.dataset.archiveStatus = 'Rejected';
                    activeRow.cells[6].innerHTML = '<span class="scms-badge scms-badge--rejected">Rejected</span>';
                    
                    showToast('Submission has been rejected.', 'success');
                    activeRow = null;
                    
                    // Reload submissions to get fresh data
                    loadSubmissions();
                    loadActivityData(); // Refresh calendar to show new activity
                }
            });
            
            // Search input handler
            document.getElementById('search-input').addEventListener('keyup', function() {
                var at = document.querySelector('.custom-tab-active');
                var as = at.textContent.trim();
                filterSubmissions(as, at);
            });
            
            // Password change handlers
            var spb = document.getElementById('save-password-button');
            var spm = document.getElementById('save_password_modal');
            var csp = document.getElementById('confirm-save-password-btn');
            var pcf = document.getElementById('password-change-form');
            
            spb.addEventListener('click', function(e) {
                e.preventDefault();
                if (pcf.checkValidity()) {
                    spm.showModal();
                } else {
                    pcf.reportValidity();
                }
            });
            
            csp.addEventListener('click', function() {
                pcf.reset();
                spm.close();
                showToast('Your password has been updated.', 'success');
            });
        });

        // Generate Activity Calendar (GitHub-style)
        function generateActivityCalendar() {
            var container = document.getElementById('activity-calendar');
            if (!container) return;
            
            // TODO: Replace with actual API call to get admin activity data
            // Expected format: { "2024-10-22": 3, "2024-10-23": 1, ... }
            // For now, generate sample data
            var today = new Date();
            var yearAgo = new Date(today);
            yearAgo.setFullYear(today.getFullYear() - 1);
            
            // Sample activity data - will be replaced with API call
            var activityData = generateSampleActivityData(yearAgo, today);
            
            // Get color based on activity level
            function getColor(level) {
                var colors = ['#E5E7EB', '#E5D4FF', '#C9A9FF', '#A475FF', '#6D28D9'];
                return colors[Math.min(level, 4)] || colors[0];
            }
            
            // Calculate weeks to display
            var startDate = new Date(yearAgo);
            startDate.setDate(startDate.getDate() - startDate.getDay()); // Start from Sunday
            
            var weeks = [];
            var currentWeek = new Date(startDate);
            
            while (currentWeek <= today) {
                var week = [];
                for (var i = 0; i < 7; i++) {
                    var date = new Date(currentWeek);
                    date.setDate(date.getDate() + i);
                    week.push(date);
                }
                weeks.push(week);
                currentWeek.setDate(currentWeek.getDate() + 7);
            }
            
            // Build HTML
            var html = '<div class="flex gap-2">';
            
            // Day labels column
            html += '<div class="flex flex-col justify-between text-xs text-text-muted pr-2" style="padding-top: 24px;">';
            var days = ['Mon', 'Wed', 'Fri'];
            var dayIndices = [1, 3, 5];
            for (var d = 0; d < 3; d++) {
                html += '<div class="h-3 leading-3">' + days[d] + '</div>';
            }
            html += '</div>';
            
            // Calendar grid with month labels
            html += '<div class="flex-1 overflow-x-auto"><div class="inline-flex flex-col">';
            
            // Month labels row
            html += '<div class="flex gap-1 mb-2">';
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var lastMonth = -1;
            var monthPositions = [];
            
            for (var w = 0; w < weeks.length; w++) {
                var weekMonth = weeks[w][0].getMonth();
                if (weekMonth !== lastMonth && w > 0) {
                    monthPositions.push({ week: w, month: weekMonth });
                    lastMonth = weekMonth;
                }
            }
            
            for (var w = 0; w < weeks.length; w++) {
                var showMonth = monthPositions.find(function(mp) { return mp.week === w; });
                if (showMonth) {
                    html += '<div class="text-xs text-text-muted" style="min-width: 13px;">' + months[showMonth.month] + '</div>';
                } else {
                    html += '<div style="min-width: 13px;"></div>';
                }
            }
            html += '</div>';
            
            // Grid rows (Mon, Tue, Wed, Thu, Fri, Sat, Sun)
            for (var dayIndex = 0; dayIndex < 7; dayIndex++) {
                html += '<div class="flex gap-1 mb-1">';
                
                for (var w = 0; w < weeks.length; w++) {
                    var date = weeks[w][dayIndex];
                    var dateStr = date.toISOString().split('T')[0];
                    var level = activityData[dateStr] || 0;
                    var color = getColor(level);
                    
                    var isToday = date.toDateString() === today.toDateString();
                    var isFuture = date > today;
                    
                    var title = dateStr;
                    if (!isFuture) {
                        title += ': ' + level + ' update' + (level !== 1 ? 's' : '');
                    } else {
                        title += ': No data';
                    }
                    
                    var borderClass = isToday ? 'ring-2 ring-primary-purple ring-offset-1' : '';
                    var opacity = isFuture ? 'opacity-30' : '';
                    
                    html += '<div class="w-3 h-3 rounded-sm transition-all hover:ring-2 hover:ring-primary-purple hover:ring-offset-1 cursor-pointer ' + borderClass + ' ' + opacity + '" ' +
                            'style="background-color: ' + color + ';" ' +
                            'title="' + title + '"></div>';
                }
                
                html += '</div>';
            }
            
            html += '</div></div></div>';
            container.innerHTML = html;
        }
        
        // Generate sample activity data (will be replaced with API call)
        function generateSampleActivityData(startDate, endDate) {
            var data = {};
            var current = new Date(startDate);
            
            while (current <= endDate) {
                var dateStr = current.toISOString().split('T')[0];
                // Random activity level (0-4), with higher probability for lower values
                var random = Math.random();
                if (random < 0.3) {
                    data[dateStr] = 0;
                } else if (random < 0.6) {
                    data[dateStr] = 1;
                } else if (random < 0.8) {
                    data[dateStr] = 2;
                } else if (random < 0.95) {
                    data[dateStr] = 3;
                } else {
                    data[dateStr] = 4;
                }
                current.setDate(current.getDate() + 1);
            }
            
            return data;
        }

        // Initialize Pending Requests Donut Chart
        var pendingRequestsChartInstance = null;
        function initPendingRequestsChart() {
            var canvas = document.getElementById('pendingRequestsChart');
            if (!canvas) return;
            
            var ctx = canvas.getContext('2d');
            
            // Destroy existing chart if any
            if (pendingRequestsChartInstance) {
                pendingRequestsChartInstance.destroy();
            }
            
            // Sample data - replace with actual data from API
            var pendingCount = 12;
            var totalCapacity = 50; // Example total capacity
            var percentage = Math.round((pendingCount / totalCapacity) * 100);
            
            pendingRequestsChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [pendingCount, totalCapacity - pendingCount],
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

        // Load Activity Calendar Data from API
        function loadActivityData() {
            fetch('/admin/api/activity-calendar', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch activity data');
                }
                return response.json();
            })
            .then(result => {
                if (result.success && result.data) {
                    // API returns data in format: { "2024-10-22": 3, "2024-10-23": 1, ... }
                    generateActivityCalendarWithData(result.data);
                } else {
                    console.warn('Invalid activity data format, using fallback');
                    generateActivityCalendar();
                }
            })
            .catch(error => {
                console.error('Failed to load activity data:', error);
                // Fall back to sample data
                generateActivityCalendar();
            });
        }
        
        // Generate calendar with provided activity data
        function generateActivityCalendarWithData(activityData) {
            var container = document.getElementById('activity-calendar');
            if (!container) return;
            
            var today = new Date();
            var yearAgo = new Date(today);
            yearAgo.setFullYear(today.getFullYear() - 1);
            
            // Use the same rendering logic but with provided data
            // Get color based on activity level
            function getColor(level) {
                var colors = ['#E5E7EB', '#E5D4FF', '#C9A9FF', '#A475FF', '#6D28D9'];
                return colors[Math.min(level, 4)] || colors[0];
            }
            
            // Calculate weeks to display
            var startDate = new Date(yearAgo);
            startDate.setDate(startDate.getDate() - startDate.getDay());
            
            var weeks = [];
            var currentWeek = new Date(startDate);
            
            while (currentWeek <= today) {
                var week = [];
                for (var i = 0; i < 7; i++) {
                    var date = new Date(currentWeek);
                    date.setDate(date.getDate() + i);
                    week.push(date);
                }
                weeks.push(week);
                currentWeek.setDate(currentWeek.getDate() + 7);
            }
            
            // Build HTML (same as generateActivityCalendar but uses activityData parameter)
            var html = '<div class="flex gap-2">';
            
            html += '<div class="flex flex-col justify-between text-xs text-text-muted pr-2" style="padding-top: 24px;">';
            var days = ['Mon', 'Wed', 'Fri'];
            for (var d = 0; d < 3; d++) {
                html += '<div class="h-3 leading-3">' + days[d] + '</div>';
            }
            html += '</div>';
            
            html += '<div class="flex-1 overflow-x-auto"><div class="inline-flex flex-col">';
            
            html += '<div class="flex gap-1 mb-2">';
            var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var lastMonth = -1;
            var monthPositions = [];
            
            for (var w = 0; w < weeks.length; w++) {
                var weekMonth = weeks[w][0].getMonth();
                if (weekMonth !== lastMonth && w > 0) {
                    monthPositions.push({ week: w, month: weekMonth });
                    lastMonth = weekMonth;
                }
            }
            
            for (var w = 0; w < weeks.length; w++) {
                var showMonth = monthPositions.find(function(mp) { return mp.week === w; });
                if (showMonth) {
                    html += '<div class="text-xs text-text-muted" style="min-width: 13px;">' + months[showMonth.month] + '</div>';
                } else {
                    html += '<div style="min-width: 13px;"></div>';
                }
            }
            html += '</div>';
            
            for (var dayIndex = 0; dayIndex < 7; dayIndex++) {
                html += '<div class="flex gap-1 mb-1">';
                
                for (var w = 0; w < weeks.length; w++) {
                    var date = weeks[w][dayIndex];
                    var dateStr = date.toISOString().split('T')[0];
                    var level = activityData[dateStr] || 0;
                    var color = getColor(level);
                    
                    var isToday = date.toDateString() === today.toDateString();
                    var isFuture = date > today;
                    
                    var title = dateStr;
                    if (!isFuture) {
                        title += ': ' + level + ' update' + (level !== 1 ? 's' : '');
                    } else {
                        title += ': No data';
                    }
                    
                    var borderClass = isToday ? 'ring-2 ring-primary-purple ring-offset-1' : '';
                    var opacity = isFuture ? 'opacity-30' : '';
                    
                    html += '<div class="w-3 h-3 rounded-sm transition-all hover:ring-2 hover:ring-primary-purple hover:ring-offset-1 cursor-pointer ' + borderClass + ' ' + opacity + '" ' +
                            'style="background-color: ' + color + ';" ' +
                            'title="' + title + '"></div>';
                }
                
                html += '</div>';
            }
            
            html += '</div></div></div>';
            container.innerHTML = html;
        }
    </script>
</body>
</html>