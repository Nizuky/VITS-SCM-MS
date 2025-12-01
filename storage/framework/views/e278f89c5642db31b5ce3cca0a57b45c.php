<div id="dashboard-page" class="page-content hidden flex-col flex-1-dynamic">

    <!-- Inactive Account Warning Banner -->
    <?php if(auth()->user()->status === 'inactive' && auth()->user()->inactive_at): ?>
        <?php
            $inactiveSince = \Carbon\Carbon::parse(auth()->user()->inactive_at);
            $deletionDate = $inactiveSince->copy()->addDays(7);
            $now = now();
            
            // Calculate days remaining - use ceiling to always round up
            $totalHours = $now->diffInHours($deletionDate, false);
            $daysRemaining = max(0, (int) ceil($totalHours / 24));
            $hoursRemaining = max(0, (int) $totalHours);
        ?>
        <div class="alert shadow-xl mb-4 md:mb-6 bg-red-900/20 border border-red-600 border-l-4 md:border-l-8 rounded-lg md:rounded-xl p-3 md:p-4" role="alert">
            <div class="flex flex-col sm:flex-row items-start w-full gap-3 md:gap-4">
                <div class="bg-red-600 p-2 md:p-3 rounded-lg flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-7 md:w-7 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1 w-full">
                    <h3 class="text-lg md:text-xl font-bold text-orange-500 mb-2 md:mb-3">Account Deactivated & Scheduled for Deletion</h3>
                    
                    <p class="text-gray-400 mb-3 md:mb-4 text-sm md:text-base">
                        <span class="font-semibold">Status:</span> Your account has been deactivated by an administrator. You won't be able to use the system until it is reactivated.
                    </p>
                    
                    <div class="border-t border-b border-gray-600 py-3 md:py-4 mb-3 md:mb-4">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                            <div class="flex items-center gap-2 sm:gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h4 class="text-base md:text-lg font-bold text-blue-400">
                                    <?php if($daysRemaining > 0): ?>
                                        <?php echo e($daysRemaining); ?> Day<?php echo e($daysRemaining != 1 ? 's' : ''); ?> Remaining
                                    <?php else: ?>
                                        <?php echo e($hoursRemaining); ?> Hour<?php echo e($hoursRemaining != 1 ? 's' : ''); ?> Remaining
                                    <?php endif; ?>
                                </h4>
                            </div>
                        </div>
                        <p class="text-orange-400 text-xs md:text-sm">
                            Your account will be <strong>permanently deleted</strong> on <strong><?php echo e($deletionDate->format('F d, Y')); ?></strong> at <strong><?php echo e($deletionDate->format('g:i A')); ?></strong>
                        </p>
                    </div>
                    
                    <div class="flex items-start gap-2 mb-3 md:mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 text-green-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-green-400">
                            <h5 class="font-bold mb-1 text-sm md:text-base">Action Required</h5>
                            <p class="text-gray-400 text-xs md:text-sm">
                                To reactivate your account and prevent permanent deletion, please contact an administrator immediately.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 md:gap-3 mt-3 md:mt-4">
                        <button onclick="showPage('support')" class="btn btn-sm w-full sm:w-auto text-white transition-colors text-xs md:text-sm" style="background-color: #0f9e43ff;" onmouseover="this.style.backgroundColor='#078334ff'" onmouseout="this.style.backgroundColor='#10ae4aff'">Contact Administrator</button>
                        <button onclick="document.getElementById('deactivation_details_modal').showModal()" class="btn btn-outline btn-sm w-full sm:w-auto text-xs md:text-sm">View Deactivation Details</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deactivation Details Modal -->
        <dialog id="deactivation_details_modal" class="modal">
            <div class="modal-box bg-gray-800 border border-gray-700">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-lg text-orange-500 mb-4">Deactivation Details</h3>
                <div class="space-y-4">
                    <div class="alert alert-warning bg-yellow-900/20 border border-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <h4 class="font-bold text-yellow-400">What This Means</h4>
                            <p class="text-sm text-gray-300 mt-2">
                                You have done something against the rules, or you are already graduated. 
                                All your data, including social contract records, will be permanently removed on the deletion date.
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-700/50 p-4 rounded-lg">
                        <h5 class="font-semibold text-gray-200 mb-2">Account Information</h5>
                        <ul class="text-sm text-gray-400 space-y-1">
                            <li>• <strong>Status:</strong> Inactive</li>
                            <li>• <strong>Deactivated:</strong> <?php echo e($inactiveSince->format('F d, Y \a\t g:i A')); ?></li>
                            <li>• <strong>Deletion Date:</strong> <?php echo e($deletionDate->format('F d, Y \a\t g:i A')); ?></li>
                        </ul>
                    </div>
                    
                    <div class="text-sm text-gray-400">
                        <p>If you believe this is an error or need to recover your account, please contact an administrator before the deletion date.</p>
                    </div>
                </div>
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn btn-sm text-white transition-colors" style="background-color: #0f9e43ff;" onmouseover="this.style.backgroundColor='#078334ff'" onmouseout="this.style.backgroundColor='#10ae4aff'">Close</button>
                    </form>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    <?php endif; ?>

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
                            <?php echo e(Str::of(auth()->user()->name)->explode(' ')->first()); ?>

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
                <img src="<?php echo e(asset('storage/images/PLVgirl.png')); ?>" class="w-[270px] h-auto object-contain drop-shadow-lg" />
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
                    <p class="text-green-800 dark:text-green-200 font-semibold">Approved</p>
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
                    <p class="text-teal-800 dark:text-teal-200 font-semibold">Verified</p>
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
                    <p class="text-yellow-800 dark:text-yellow-200 font-semibold">Pending</p>
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
                    <p class="text-red-800 dark:text-red-200 font-semibold">Rejected</p>
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
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm flex flex-col gap-6">
            <!-- Approved / Completion donut -->
            <div class="flex flex-col items-center w-full">
                <h2 class="text-lg font-bold text-white mb-4">Approved Hours Completion</h2>
                <div class="relative w-48 h-48">
                    <canvas id="hoursCompletionChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-bold text-white" id="hours-completion-label">0%</span>
                        <p class="text-sm text-white" id="hours-amount">0h of 160h</p>
                    </div>
                </div>
            </div>

            <div class="divider my-0"></div>

            <!-- Status Distribution Chart -->
            <div class="flex flex-col items-center w-full flex-1" style="color: #FFFFFF !important;">
                <h2 class="text-lg font-bold text-white mb-4">Status Distribution</h2>
                <div class="relative w-full" style="min-height: 220px; height: 220px; color: #FFFFFF !important;">
                    <canvas id="statusDistributionChart" width="400" height="220" style="cursor: pointer;"></canvas>
                </div>
                <p class="text-xs text-white mt-2 text-center">Click any segment to view filtered records</p>
            </div>
        </div>
    </div>
</div>

<script>
// Status Distribution Chart with Gradients
(function() {
    console.log('🔍 Status Distribution Chart Loading...');
    
    function tryCreateChart() {
        const canvas = document.getElementById('statusDistributionChart');
        
        if (!canvas) {
            console.error('❌ Canvas not found!');
            return;
        }
        
        if (typeof Chart === 'undefined') {
            console.error('❌ Chart.js not loaded yet, waiting...');
            setTimeout(tryCreateChart, 100);
            return;
        }
        
        // Check if canvas is in the DOM
        if (!canvas.isConnected) {
            console.error('❌ Canvas not connected to DOM, waiting...');
            setTimeout(tryCreateChart, 100);
            return;
        }
        
        console.log('✅ Chart.js loaded, creating gradient chart...');
        
        const ctx = canvas.getContext('2d');
        
        if (!ctx) {
            console.error('❌ Could not get canvas context');
            return;
        }
        
        // Get actual counts from window variables
        const approvedCount = parseInt(window.__scms_approvedCount) || 0;
        const verifiedCount = parseInt(window.__scms_verifiedCount) || 0;
        const pendingCount = parseInt(window.__scms_pendingCount) || 0;
        const rejectedCount = parseInt(window.__scms_rejectedCount) || 0;
        
        // Override any global Chart defaults that might be setting dark text
        if (typeof Chart.defaults !== 'undefined') {
            Chart.overrides.doughnut = Chart.overrides.doughnut || {};
            Chart.overrides.doughnut.plugins = Chart.overrides.doughnut.plugins || {};
            Chart.overrides.doughnut.plugins.legend = Chart.overrides.doughnut.plugins.legend || {};
            Chart.overrides.doughnut.plugins.legend.labels = {
                color: '#FFFFFF'
            };
        }
        
        // Custom plugin to force white legend text on every render
        const whiteLegendPlugin = {
            id: 'whiteLegendText',
            afterUpdate: (chart) => {
                if (chart.legend && chart.legend.legendItems) {
                    chart.legend.legendItems.forEach(item => {
                        item.fontColor = '#FFFFFF';
                    });
                }
                // Force options update
                if (chart.options.plugins.legend.labels) {
                    chart.options.plugins.legend.labels.color = '#FFFFFF';
                }
            }
        };
        
        try {
            const chartInstance = new Chart(ctx, {
            type: 'doughnut',
            plugins: [whiteLegendPlugin],
            data: {
                labels: ['Approved', 'Verified', 'Pending', 'Rejected'],
                datasets: [{
                    data: [approvedCount, verifiedCount, pendingCount, rejectedCount],
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {ctx, chartArea} = chart;
                        
                        if (!chartArea) {
                            return ['#10B981', '#14B8A6', '#F59E0B', '#EF4444'][context.dataIndex];
                        }
                        
                        const centerX = (chartArea.left + chartArea.right) / 2;
                        const centerY = (chartArea.top + chartArea.bottom) / 2;
                        const r = Math.min(
                            (chartArea.right - chartArea.left) / 2,
                            (chartArea.bottom - chartArea.top) / 2
                        );
                        
                        const gradients = [
                            // Approved (Green)
                            function() {
                                const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
                                gradient.addColorStop(0, '#34D399');
                                gradient.addColorStop(1, '#059669');
                                return gradient;
                            },
                            // Verified (Teal)
                            function() {
                                const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
                                gradient.addColorStop(0, '#2DD4BF');
                                gradient.addColorStop(1, '#0D9488');
                                return gradient;
                            },
                            // Pending (Orange)
                            function() {
                                const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
                                gradient.addColorStop(0, '#FBBF24');
                                gradient.addColorStop(1, '#D97706');
                                return gradient;
                            },
                            // Rejected (Red)
                            function() {
                                const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, r);
                                gradient.addColorStop(0, '#F87171');
                                gradient.addColorStop(1, '#DC2626');
                                return gradient;
                            }
                        ];
                        
                        return gradients[context.dataIndex]();
                    },
                    borderWidth: 0,
                    borderColor: 'transparent',
                    hoverBorderWidth: 0,
                    hoverBorderColor: 'transparent',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                onClick: (event, elements) => {
                    console.log('Chart clicked!', elements);
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const labels = ['Approved', 'Verified', 'Pending', 'Rejected'];
                        const status = labels[index];
                        
                        console.log('🎯 Clicked on status:', status);
                        
                        // Use window references explicitly
                        if (typeof window.showPage === 'function') {
                            console.log('📄 Calling showPage...');
                            window.showPage('record-status');
                            
                            // Wait for page to render, then apply filter
                            setTimeout(() => {
                                console.log('🔍 Calling filterTableByStatus...');
                                if (typeof window.filterTableByStatus === 'function') {
                                    window.filterTableByStatus(status, null);
                                    console.log('✅ Filter applied for:', status);
                                } else {
                                    console.error('❌ filterTableByStatus not found');
                                }
                            }, 200);
                        } else {
                            console.error('❌ showPage not found');
                        }
                    }
                },
                plugins: {
                    // Disable built-in legend (we'll render a custom HTML legend below)
                    legend: {
                        display: true,
                        labels: {
                        color: '#FFFFFF' // <--- Set the label text color to white
                    }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#1F2937',
                        titleColor: '#FFFFFF',
                        bodyColor: '#FFFFFF',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${label}: ${value} records (${percentage}%)`;
                            },
                            afterLabel: function(context) {
                                return 'Click to view records';
                            }
                        }
                    }
                }
            }
        });
        
        // Store chart instance globally for updates
        window.statusDistributionChart = chartInstance;
        
        // CRITICAL: Force update the legend color after chart is fully rendered
        // This overrides any parent page settings
        chartInstance.options.plugins.legend.labels.color = '#FFFFFF';
        
        // Also force it in the legend items themselves
        if (chartInstance.legend && chartInstance.legend.legendItems) {
            chartInstance.legend.legendItems.forEach(item => {
                item.fontColor = '#FFFFFF';
            });
        }
        
        // Trigger a full re-render with the white color
        chartInstance.update('none');
        
        // Force legend text to white with CSS - increased delay
        setTimeout(() => {
            const legendItems = canvas.parentElement.parentElement.querySelectorAll('.chartjs-legend li, [id*="legend"] li, canvas + * li, ul li');
            legendItems.forEach(item => {
                item.style.setProperty('color', '#FFFFFF', 'important');
                const spans = item.querySelectorAll('span');
                spans.forEach(span => span.style.setProperty('color', '#FFFFFF', 'important'));
            });
            
            // Also try to find and style any generated legend elements
            const allLegendTexts = canvas.parentElement.parentElement.querySelectorAll('ul li span, ul li');
            allLegendTexts.forEach(el => {
                el.style.setProperty('color', '#FFFFFF', 'important');
            });
        }, 500);
        
        console.log('✅ Chart with percentages created successfully!');
        
        // Add direct canvas click handler as backup
        canvas.addEventListener('click', function(evt) {
            const points = chartInstance.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (points.length) {
                const firstPoint = points[0];
                const label = chartInstance.data.labels[firstPoint.index];
                const value = chartInstance.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
                
                console.log('🖱️ Canvas click detected:', label);
                
                // Navigate and filter
                if (window.showPage) {
                    window.showPage('record-status');
                    setTimeout(() => {
                        if (window.filterTableByStatus) {
                            window.filterTableByStatus(label, null);
                        }
                    }, 300);
                }
            }
        });
        
        } catch (error) {
            console.error('❌ Error creating chart:', error);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', tryCreateChart);
    } else {
        tryCreateChart();
    }
})();
</script>

<style>
/* Force white legend text for Chart.js - Most aggressive fix */
.chartjs-legend,
.chartjs-legend *,
.chartjs-legend ul,
.chartjs-legend ul li,
.chartjs-legend ul li span {
    color: #ffffff !important;
    fill: #ffffff !important;
}

/* Target any list items in flex containers (Chart.js legend) */
div[class*="flex"] > ul > li,
div[class*="flex"] > ul > li *,
.flex-col ul li,
.flex-col ul li * {
    color: #ffffff !important;
}

/* Global override for the chart container */
#statusDistributionChart ~ ul,
#statusDistributionChart ~ ul *,
#statusDistributionChart ~ div ul,
#statusDistributionChart ~ div ul * {
    color: #ffffff !important;
}
</style>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/student/dashboard-page.blade.php ENDPATH**/ ?>