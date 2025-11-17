<!-- Dashboard Overview Page -->
<div id="dashboard-page" class="page-content flex-col flex-1-dynamic">
    <h1 class="text-4xl font-bold text-primary-purple px-4 mb-6">Admin Dashboard</h1>
    
    <!-- Welcome Greeting Card -->
    <div class="relative rounded-2xl bg-transparent p-2 mb-6 h-[190px]">
        <div class="bg-gradient-primary-purple flex items-center rounded-2xl h-[190px] w-full shadow-lg relative overflow-hidden" style="display: flex; flex-wrap: nowrap; justify-content: space-between;">
            <!-- Purple curved accent -->
            <div class="absolute top-0 left-0 w-[120px] h-[120px] bg-gradient-to-r from-primary-purple to-transparent rounded-br-full opacity-70"></div>
            
            <!-- Left text content -->
            <div class="relative z-10 ml-2 pl-4 sm:pl-6 md:pl-10 pr-2" style="max-width: 55%; flex-shrink: 1; flex-grow: 0;">
                <h2 class="font-semibold text-white" style="font-size: clamp(1.25rem, 3vw, 1.875rem); line-height: 1.2;">
                    Welcome, 
                    <span class="text-white font-bold">
                        {{ Str::of(auth('admin')->user()->name)->explode(' ')->first() }}
                    </span>
                </h2>
                <br class="hidden md:block">
                <p class="text-white mt-1" style="font-size: clamp(0.75rem, 1.5vw, 1rem); line-height: 1.4;">
                    Manage student submissions and <br class="hidden sm:block">
                    monitor social contract compliance.
                </p>
                <p class="text-white font-bold mt-1" style="font-size: clamp(0.75rem, 1.5vw, 1rem); line-height: 1.4;">
                    Empowering ka-VITS through efficient administration!
                </p>
            </div>
            
            <!-- Pending Requests Donut -->
            <div class="flex flex-col items-center ml-auto mr-2 sm:mr-4 md:mr-8 p-4" style="flex-shrink: 0;">
                <h2 class="font-bold text-white mb-2 md:mb-4" style="font-size: clamp(0.875rem, 2vw, 1.25rem);">Pending Requests</h2>
                <div class="relative w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40">
                    <canvas id="pendingRequestsChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="font-bold text-white" id="pending-requests-label" style="font-size: clamp(1.25rem, 3vw, 1.875rem);">0</span>
                        <p class="text-white" style="font-size: clamp(0.75rem, 1.5vw, 0.875rem);">Requests</p>
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
            <!-- Pending Requests -->
            <div class="bg-gradient-pending p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Pending')">
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
            
            <!-- Accepted Requests -->
            <div class="bg-gradient-accepted p-4 rounded-2xl flex flex-col gap-2 cursor-pointer hover:shadow-lg transition-shadow duration-200" onclick="showStatusModal('Verified')">
                <div class="bg-white p-2 rounded-full w-min">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-text-header"><span id="accepted-requests-count">0</span> Requests</h3>
                    <p class="text-[#0e4848ff] font-semibold">Verified This Month</p>
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
    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <div>
                <h2 class="text-base sm:text-lg md:text-xl font-bold text-text-header">Contract Update Activity</h2>
                <p class="text-xs sm:text-sm text-text-muted">Days when contracts were reviewed and updated (updates tracked in real-time)</p>
            </div>
           <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 w-full md:w-auto">
                <div class="flex items-center gap-3 text-xs">
                    <span class="text-text-muted">Less</span>
                    <div class="flex gap-1">
                        <div class="w-3 h-3 rounded-sm activity-legend-0" title="No activity"></div>
                        <div class="w-3 h-3 rounded-sm activity-legend-1" title="1-2 updates"></div>
                        <div class="w-3 h-3 rounded-sm activity-legend-2" title="3-4 updates"></div>
                        <div class="w-3 h-3 rounded-sm activity-legend-3" title="5-6 updates"></div>
                        <div class="w-3 h-3 rounded-sm activity-legend-4" title="7+ updates"></div>
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
            <!-- Data source: loadActivityData() function calls API endpoint -->
        </div>
    </div>

    <!-- Yearly Approved and Rejected Records Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">
        <!-- Yearly Approved Records -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold text-text-header mb-4">Yearly Approved Records</h2>
            <div class="relative" style="height: 300px;">
                <canvas id="yearlyApprovedChart"></canvas>
            </div>
        </div>
        
        <!-- Yearly Rejected Records -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h2 class="text-xl font-bold text-text-header mb-4">Yearly Rejected Records</h2>
            <div class="relative" style="height: 300px;">
                <canvas id="yearlyRejectedChart"></canvas>
            </div>
        </div>
    </div>
</div>
