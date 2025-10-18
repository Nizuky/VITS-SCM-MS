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
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Ensure active nav exactly matches primary purple */
        .active-nav { background-color: #6D28D9; color: #ffffff; border-radius: 0.5rem; }
        .flex-1-dynamic { flex: 1 1 auto; min-height: 0; }
        .content-area-auto { height: auto; max-height: 100%; }
        /* Optional utility for static-looking inputs */
        .static-input { border: none !important; box-shadow: none !important; padding-left: 0 !important; background-color: transparent !important; cursor: default !important; }
        /* Page background image */
        .bg-custom {
            background-color: #EDF1FA; /* fallback */
            background-image: url('<?php echo e(asset('vits_bg.png')); ?>');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        /* Gradients for summary cards */
        .bg-gradient-approved { background-image: linear-gradient(to bottom, #DCFCE7, #81FFAC); }
        .bg-gradient-pending { background-image: linear-gradient(to bottom, #FFF4DE, #FFE0A2); }
        .bg-gradient-rejected { background-image: linear-gradient(to bottom, #FFE2E5, #FFB7BE); }
    </style>
</head>
<body class="min-h-screen bg-custom">
    <script>window.__SCMS_DISABLE_AUTO_LOGOUT = true;</script>
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
        <aside class="flex flex-col w-64 bg-white rounded-2xl p-4 shadow-sm">
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
                    <a class="py-3" id="nav-profile" onclick="showPage('profile')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Profile
                    </a>
                </li>
                <li>
                    <form id="logout-form-visible" action="<?php echo e(route('logout')); ?>" method="POST" class="m-0 p-0" novalidate>
                        <?php echo csrf_field(); ?>
                        <button id="logout-button-visible" type="button" class="py-3 w-full text-left flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Log Out
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <main class="flex-1 flex flex-col gap-6" id="page-container">
            <div class="flex justify-between items-center p-4">
                <div id="main-greeting" class="text-white drop-shadow-md"> 
                    <p class="text-sm opacity-90">Hi <?php echo e(Str::of(auth()->user()->name)->explode(' ')->first()); ?>,</p>
                    <h1 class="text-2xl font-extrabold">Welcome to Student Contract Management System!</h1>
                </div>
                
                <div class="dropdown dropdown-end" id="notification-dropdown-container">
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <span class="badge badge-xs indicator-item bg-primary-purple border-0"></span>
                        </div>
                    </div>
                    <ul tabindex="0" class="dropdown-content z-[1] menu p-0 shadow bg-base-100 rounded-box w-80 mt-4 overflow-hidden">
                        
                        <li class="p-4 font-bold text-gray-700 border-b">Notifications</li>

                        <li>
                            <div class="flex items-start p-3 w-full border-b border-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-badge-verified-text mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Well done! Your submitted form has been <span class="text-badge-verified-text">Verified</span>.</p>
                                    <p class="text-xs text-gray-500 mt-1">Yesterday</p>
                                </div>
                            </div>
                        </li>

                        <li>
                            <div class="flex items-start p-3 w-full border-b border-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-badge-rejected-text mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Your record has been <span class="text-badge-rejected-text">Rejected</span> due to the reason attached</p>
                                    <p class="text-xs text-gray-500 mt-1">23 June 2024</p>
                                </div>
                            </div>
                        </li>
                        
                        <li class="border-t border-gray-100">
                            <a class="text-center text-sm py-2">See All Notifications</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Dashboard overview page with summary cards and charts -->
            <div id="dashboard-page" class="page-content hidden flex-col flex-1-dynamic">
                <div class="bg-white rounded-2xl p-4 shadow-sm mb-4">
                    <h2 class="text-xl font-bold text-text-header mb-1">Social Contract Summary</h2>
                    <p class="text-sm text-text-muted mb-4">Contract Status Overview (Approved, Pending, Rejected)</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Approved Records -->
                        <div class="bg-gradient-approved p-4 rounded-2xl flex flex-col gap-2">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="approved-count">8</span> Records</h3>
                                <p class="text-green-800 font-semibold">Approved</p>
                                <p class="text-xs text-text-muted mt-1">last update: <span id="summary-last-updated">oct 18, 2025</span></p>
                            </div>
                        </div>
                        <!-- Pending Records -->
                        <div class="bg-gradient-pending p-4 rounded-2xl flex flex-col gap-2">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="pending-count">8</span> Records</h3>
                                <p class="text-yellow-800 font-semibold">Pending</p>
                                <p class="text-xs text-text-muted mt-1">last update: <span id="summary-last-updated-2">oct 18, 2025</span></p>
                            </div>
                        </div>
                        <!-- Rejected Records -->
                        <div class="bg-gradient-rejected p-4 rounded-2xl flex flex-col gap-2">
                            <div class="bg-white p-2 rounded-full w-min">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-text-header"><span id="rejected-count">0</span> Record</h3>
                                <p class="text-red-800 font-semibold">Rejected</p>
                                <p class="text-xs text-text-muted mt-1">last update: <span id="summary-last-updated-3">oct 18, 2025</span></p>
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
                    <div class="lg:col-span-2 bg-white rounded-2xl p-4 shadow-sm flex flex-col items-center justify-center">
                        <h2 class="text-xl font-bold text-text-header mb-4">Rendered Hours Completion</h2>
                        <div class="relative w-40 h-40">
                            <canvas id="hoursCompletionChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold text-primary-purple" id="hours-completion-label">80%</span>
                                <p class="text-sm text-text-muted">Rendered Hours</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="record-status-page" class="page-content flex flex-col flex-1-dynamic">
                <div class="flex justify-between items-center px-4 mb-6">
                    <button class="btn bg-primary-purple hover:bg-primary-purple-hover text-white rounded-lg" onclick="document.getElementById('add_record_modal').showModal()">
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
                                    <th class="text-center w-1/6">Hours Rendered</th>
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
                <h1 class="text-2xl font-bold p-4 text-white drop-shadow-md">Profile Information</h1>
                
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
                                <div class="label"><span class="label-text">Full Name</span></div>
                                <input type="text" value="<?php echo e(auth()->user()->name); ?>" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                                <div class="label"><span class="label-text-alt">SN, FN, MI</span></div>
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
                                <button type="submit" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg" onclick="showViewMode()">
                                    Save Changes
                                </button>
                            </div>
                        </form>
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

    <script>
    // --- Page Navigation Functions ---
        function showPage(pageId) {
            document.querySelectorAll('aside a').forEach(a => {
                a.classList.remove('bg-primary-purple', 'active-nav', 'rounded-lg');
            });
            document.querySelectorAll('.page-content').forEach(p => { p.classList.add('hidden'); });
            const greetingElement = document.getElementById('main-greeting');
            const notificationContainer = document.getElementById('notification-dropdown-container');
            if (pageId === 'profile') { greetingElement.classList.add('hidden'); notificationContainer.classList.add('hidden'); }
            else { greetingElement.classList.remove('hidden'); notificationContainer.classList.remove('hidden'); }
            const newPage = document.getElementById(pageId + '-page'); if (newPage) newPage.classList.remove('hidden');
            const navLink = document.getElementById('nav-' + pageId); if (navLink) navLink.classList.add('bg-primary-purple', 'active-nav', 'rounded-lg');
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

        function renderCharts() {
            try {
                const yearlyCanvas = document.getElementById('yearlyRecordsChart');
                const hoursCanvas = document.getElementById('hoursCompletionChart');
                if (!yearlyCanvas || !hoursCanvas) return;

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
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false } },
                            x: { grid: { display: false } }
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
                                : [80, 20],
                            backgroundColor: ['#6D28D9', '#E9D5FF'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '80%',
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });
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
            showPage('record-status');
            // Load existing records for the current student's latest social contract (single-account mode)
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let allRecords = [];
            // Date sort: 'desc' by default for latest first
            let dateSortDirection = 'desc';
            const dateSortToggle = document.getElementById('date-sort-toggle');
            const dateSortIndicator = document.getElementById('date-sort-indicator');
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
            function loadRecords() {
                tableBody.innerHTML = '';
                fetch(`${BASE_PATH}/api/social-contract/records`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache',
                        'Pragma': 'no-cache',
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
                        allRecords = records;
                        renderTable();
                        updateDashboardFromRecords(allRecords);
                    })
                    .catch((err) => { console.error('Failed to load records', err); });
            }
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
                // sort by date
                filtered.sort((a, b) => {
                    const da = new Date(a.date);
                    const db = new Date(b.date);
                    if (isNaN(da) && isNaN(db)) return 0;
                    if (isNaN(da)) return dateSortDirection === 'asc' ? -1 : 1;
                    if (isNaN(db)) return dateSortDirection === 'asc' ? 1 : -1;
                    return dateSortDirection === 'asc' ? da - db : db - da;
                });
                filtered.forEach(rec => {
                    const formattedDate = normalizeDateString(rec.date);
                    const row = document.createElement('tr');
                    row.dataset.recordId = rec.id;
                    row.dataset.status = rec.status;
                    row.innerHTML = `
                        <td class="text-center"><input type="checkbox" class="record-checkbox" ${rec.status !== 'Pending' ? 'disabled' : ''}></td>
                        <td class="text-center">${formattedDate}</td>
                        <td class="text-center">${rec.event_name}</td>
                        <td class="text-center">${rec.venue}</td>
                        <td class="text-center">${rec.organization}</td>
                        <td class="text-center">${rec.hours_rendered} hours</td>
                        <td class="text-center">${renderStatusBadge(rec.status)}</td>
                    `;
                    tableBody.appendChild(row);
                });
            }
            searchInput.addEventListener('input', renderTable);
            // Date sort toggle
            dateSortToggle.addEventListener('click', (e) => {
                e.preventDefault();
                dateSortDirection = dateSortDirection === 'asc' ? 'desc' : 'asc';
                dateSortIndicator.textContent = dateSortDirection === 'asc' ? '▲' : '▼';
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
                        alert('Validation error:\n' + messages);
                    } else if (err && err.status === 401) {
                        window.location.href = `${BASE_PATH}/login`;
                    } else {
                        alert('Failed to save record. Please sign in again if your session expired.');
                    }
                    confirmationModal.close();
                });
            });
            document.getElementById('delete-selected').addEventListener('click', () => {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const ids = Array.from(document.querySelectorAll('#record-table-body tr'))
                    .filter(tr => tr.querySelector('.record-checkbox')?.checked)
                    .map(tr => parseInt(tr.dataset.recordId, 10))
                    .filter(Boolean);
                if (!ids.length) return;
                Promise.all(ids.map(id => fetch(`${BASE_PATH}/api/social-contract/records/${id}`, {
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
                    allRecords = allRecords.filter(r => !ids.includes(r.id));
                    renderTable();
                    updateDashboardFromRecords(allRecords);
                })
                .catch((err) => { console.error('Failed to delete selected records', err); });
            });
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDashboard, { once: true });
        } else {
            initDashboard();
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
            if (status === 'Verified') {
                return '<div class="badge text-badge-verified-text bg-badge-verified-bg font-semibold border-0">Verified</div>';
            }
            if (status === 'Rejected') {
                return '<div class="badge text-badge-rejected-text bg-badge-rejected-bg font-semibold border-0">Rejected</div>';
            }
            return '<div class="badge text-badge-pending-text bg-badge-pending-bg font-semibold border-0">Pending</div>';
        }
        function filterTableByStatus(status, event) {
            const tableBody = document.getElementById('record-table-body');
            const rows = tableBody.querySelectorAll('tr');
            rows.forEach(row => {
                const rowStatus = row.children[6].textContent.trim();
                row.style.display = (status === 'All' || rowStatus === status) ? '' : 'none';
            });
            if (event && event.target) {
                let dropdownContainer = event.target.closest('.dropdown');
                if (dropdownContainer) {
                    const dropdownToggle = dropdownContainer.querySelector('[tabindex="0"]');
                    if (dropdownToggle) { dropdownToggle.click(); }
                }
            }
        }

        // --- Dashboard metrics from records ---
        function updateDashboardFromRecords(records) {
            try {
                const elApproved = document.getElementById('approved-count');
                const elPending = document.getElementById('pending-count');
                const elRejected = document.getElementById('rejected-count');
                const elHoursLabel = document.getElementById('hours-completion-label');

                if (!Array.isArray(records)) records = [];

                const counts = { Approved: 0, Verified: 0, Pending: 0, Rejected: 0 };
                let totalHours = 0;
                let lastDate = null;
                const yearMap = new Map(); // year -> approved count

                for (const r of records) {
                    const status = String(r.status || '').trim();
                    if (status === 'Verified' || status === 'Approved') {
                        counts.Verified++;
                        // aggregate yearly approved
                        const y = safeYear(r.date);
                        if (y !== null) {
                            yearMap.set(y, (yearMap.get(y) || 0) + 1);
                        }
                    } else if (status === 'Pending') {
                        counts.Pending++;
                    } else if (status === 'Rejected') {
                        counts.Rejected++;
                    }
                    // hours
                    const h = Number(r.hours_rendered || 0);
                    if (!Number.isNaN(h)) totalHours += h;
                    // last date
                    const d = safeDate(r.date);
                    if (d && (!lastDate || d > lastDate)) lastDate = d;
                }

                // Update cards (treat Verified as Approved)
                if (elApproved) elApproved.textContent = String(counts.Verified);
                if (elPending) elPending.textContent = String(counts.Pending);
                if (elRejected) elRejected.textContent = String(counts.Rejected);
                const lastUpdatedText = lastDate ? lastDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).toLowerCase() : 'n/a';
                setTextIf('#summary-last-updated', lastUpdatedText);
                setTextIf('#summary-last-updated-2', lastUpdatedText);
                setTextIf('#summary-last-updated-3', lastUpdatedText);

                // Hours completion percent: if there's a target, use it; else derive a soft target (e.g., 100 hours)
                const targetHours = window.__SCMS_TARGET_HOURS || 100;
                const pct = Math.max(0, Math.min(100, Math.round((totalHours / targetHours) * 100)));
                if (elHoursLabel) elHoursLabel.textContent = pct + '%';
                window.__scms_hoursPercent = pct;

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
</html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/dashboard.blade.php ENDPATH**/ ?>