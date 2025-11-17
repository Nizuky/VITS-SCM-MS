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
            <!-- Main card container (centered & behind) -->
            <div 
                id="personalized-greeting" 
                class="absolute bottom-0 bg-gradient-primary-purple flex items-center rounded-2xl h-[190px] w-[90%] max-w-[800px] shadow-lg z-0 mx-auto left-0 right-0"
                style="display: flex; flex-wrap: nowrap; justify-content: space-between;"
            >
                <!-- Purple curved accent -->
               <div class="absolute top-0 left-0 w-[120px] h-[120px] bg-gradient-to-r from-primary-purple to-transparent rounded-br-full opacity-70"></div>

                <!-- Left text content -->
                <div class="relative z-10 ml-2 pl-4 sm:pl-6 md:pl-10 pr-2" style="max-width: 55%; flex-shrink: 1; flex-grow: 0;">
                    <h2 class="font-semibold text-white" style="font-size: clamp(1.25rem, 3vw, 1.875rem); line-height: 1.2;">
                        Good Day, 
                        <span class="text-white font-bold">
                            {{ Str::of(auth()->user()->name)->explode(' ')->first() }}
                        </span>
                    </h2>
                    <br class="hidden md:block">
                    <p class="text-white mt-1" style="font-size: clamp(0.75rem, 1.5vw, 1rem); line-height: 1.4;">
                        Here you can manage your social <br class="hidden sm:block">
                        contract and track your progress.
                    </p>
                    <p class="text-white font-bold mt-1" style="font-size: clamp(0.75rem, 1.5vw, 1rem); line-height: 1.4;">
                        We make it easier for you ka-VITS!
                    </p>
                </div>
                <!-- Pending hours donut -->
                <div class="flex flex-col items-center ml-auto mr-2 sm:mr-4 p-4" style="flex-shrink: 0;">
                    <h2 class="font-bold text-white mb-2 md:mb-4" style="font-size: clamp(0.875rem, 2vw, 1.25rem);">Pending Hours</h2>
                    <div class="relative w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40">
                        <canvas id="pendingHoursChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="font-bold text-white" id="pending-hours-label" style="font-size: clamp(1.25rem, 3vw, 1.875rem);">0%</span>
                            <p class="text-white" id="pending-amount" style="font-size: clamp(0.75rem, 1.5vw, 0.875rem);">0h of 160h</p>
                        </div>
                    </div>
                </div>
                <div class="absolute top-0 right-0 w-[300px] h-full pointer-events-none"></div>
                <!-- Optional space for balance -->
                <div class="w-0 sm:w-[60px] md:w-[120px]"></div>
            </div>
            
            <!-- Fixed image (not overlay) - hidden on smaller screens -->
            <div class="hidden xl:flex justify-end items-end">
                <img src="{{ asset('storage/images/PLVgirl.png') }}" class="w-[270px] h-auto object-contain drop-shadow-lg" />
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
