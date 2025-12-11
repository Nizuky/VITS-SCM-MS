<!-- Submission Page -->
<div id="submission-page" class="page-content hidden">
    <h1 class="text-4xl font-bold text-primary-purple px-4">Submission Management</h1>
    <p class="text-sm text-gray-600 mt-2 pl-4 pb-6 text-center lg:text-left">&#9432 Review and process submitted records; approve, reject, or archive as needed.</p>

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
            <div class="flex space-x-1 custom-tab-wrapper">
                <a role="tab" class="custom-tab custom-tab-active" onclick="filterSubmissions('pending',this)">Pending</a>
                <a role="tab" class="custom-tab" onclick="filterSubmissions('for-approval',this)">For Approval</a>
                <a role="tab" class="custom-tab" onclick="filterSubmissions('archived',this)">Archived</a>
            </div>
            
            <!-- Search + Refresh (grouped to sit on one line on mobile) -->
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label class="input input-bordered flex items-center gap-2 rounded-lg bg-white h-10 flex-1 min-w-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
                        <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd"/>
                    </svg>
                    <input type="text" class="flex-1 min-w-0 bg-transparent" placeholder="Search" id="search-input">
                </label>

                <!-- Refresh Button -->
                <button id="refresh-submissions-btn" onclick="refreshSubmissions()" class="btn btn-ghost btn-square h-10 w-10" title="Refresh submissions" aria-label="Refresh submissions">
                    <svg id="refresh-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </button>
            </div>
        </div>

    <!-- Submission Table -->
    <div class="bg-white rounded-2xl p-6 shadow-sm flex-1 flex flex-col min-h-0">
        <div class="overflow-x-auto overflow-y-auto rounded-lg w-full" style="max-height: calc(100vh - 280px);">
            <table class="table table-xs table-pin-rows">
                <thead class="text-gray-600" style="height: 60px; background-color: #f9fafb !important;">
                    <tr id="table-header-row" style="background-color: #f9fafb !important;">
                        <th class="text-center" style="height: 60px; max-height: 60px; min-width: 70px; width: 70px; white-space: nowrap;">
                            <button id="studentid-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student ID">
                                Student ID
                                <span id="studentid-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="height: 60px; max-height: 60px; min-width: 140px; width: 140px; white-space: nowrap;">
                            <button id="studentname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Student Name">
                                Student Name
                                <span id="studentname-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="height: 60px; max-height: 60px; min-width: 110px; width: 110px; white-space: nowrap;">
                            <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Event Name">
                                Event Name
                                <span id="eventname-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="height: 60px; max-height: 60px; min-width: 140px; width: 140px;">
                            <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Organization">
                                <div class="flex flex-col items-center">
                                    <span style="white-space: nowrap;">Organization/</span>
                                    <span style="white-space: nowrap;">Supervisor</span>
                                </div>
                                <span id="organization-sort-indicator">⇅</span>
                            </button>
                        </th> 
                        <th class="text-center" style="height: 60px; max-height: 60px; min-width: 50px; width: 50px; white-space: nowrap;">
                            <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Hours Rendered">
                                Hours
                                <span id="hours-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="height: 60px; max-height: 60px; min-width: 80px; width: 80px; white-space: nowrap;">
                            <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Date">
                                Date
                                <span id="date-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="height: 60px; max-height: 60px; min-width: 180px; width: 180px;">
                            <div class="flex items-center justify-center gap-1 font-bold">
                                <span>Action</span>
                            </div>
                        </th>
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
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\partials\super_admin\submission-page.blade.php ENDPATH**/ ?>