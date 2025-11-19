<!-- Submission Page -->
<div id="submission-page" class="page-content hidden">
    <h1 class="text-4xl font-bold text-primary-purple px-4">Submission Management</h1>
    <br>
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 px-4 gap-4 md:gap-0">
        <!-- Tabs -->
        <div class="flex space-x-2 custom-tab-wrapper w-full md:w-auto" style="min-width: 220px;">
            <a role="tab" class="custom-tab custom-tab-active whitespace-nowrap" onclick="filterSubmissions('Pending',this)">Pending</a>
            <a role="tab" class="custom-tab whitespace-nowrap" onclick="filterSubmissions('Archived',this)">Archived</a>
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

    <!-- Submission Table -->
    <div class="bg-white rounded-2xl p-6 shadow-sm flex-1 flex flex-col min-h-0">
        <div class="overflow-x-auto overflow-y-auto rounded-lg" style="max-height: calc(120vh - 280px); max-width: calc(120vw - 280px);">
            <table class="table table-xs table-pin-rows">
                <thead class="text-gray-600" style="height: 60px; background-color: #f9fafb !important;">
                    <tr style="background-color: #f9fafb !important;">
                        <th class="text-center" style="min-width: 90px; width: 90px; height: 60px; white-space: nowrap;">
                            <button id="studentid-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student ID">
                                Student ID
                                <span id="studentid-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 140px; width: 140px; height: 60px; white-space: nowrap;">
                            <button id="studentname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student Name">
                                Student Name
                                <span id="studentname-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 110px; width: 110px; height: 60px; white-space: nowrap;">
                            <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Event Name">
                                Event Name
                                <span id="eventname-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 140px; width: 140px; height: 60px;">
                            <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1 flex-col font-bold" title="Sort by Organization/Supervisor">
                                <span style="white-space: nowrap;">Organization/Supervisor</span>
                                <span id="organization-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 80px; width: 80px; height: 60px; white-space: nowrap;">
                            <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Hours Rendered">
                                Hours
                                <span id="hours-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 80px; width: 80px; height: 60px; white-space: nowrap;">
                            <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Date">
                                Date
                                <span id="date-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" id="action-status-header" style="min-width: 180px; width: 180px; height: 60px;">
                            <div class="flex items-center justify-center gap-1 font-bold">
                                <span id="action-label">Action</span>
                            </div>
                            <div class="hidden flex items-center justify-center gap-1 font-bold" id="status-header-wrapper">
                                <div class="flex items-center justify-center gap-1">
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
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody id="submission-table-body"></tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/admin/submission-page.blade.php ENDPATH**/ ?>