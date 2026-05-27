<!-- Support Tickets Page -->
<div id="support-page" class="page-content hidden">
    <div class="p-4 flex flex-col lg:flex-row justify-center lg:justify-between items-center gap-4">
        <div>
            <h4 class="text-4xl font-bold text-primary-purple hidden lg:block">Support Tickets</h4>
            <p class="text-sm text-gray-600 mt-2 text-center lg:text-left">&#9432 Review and resolve student support requests</p>
        </div>
    </div>
    
    <!-- Quick stats (Pending Tickets etc.) -->
    <div class="px-4 mb-4">
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl p-4 shadow-sm w-full min-h-0 flex items-center justify-between col-span-1">
                <div>
                    <div class="text-sm text-gray-500">Pending Tickets</div>
                    <div class="text-2xl font-bold text-primary-purple mt-1"><span id="pending-tickets-count">0</span></div>
                </div>
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm w-full min-h-0 flex items-center justify-between col-span-1">
                <div>
                    <div class="text-sm text-gray-500">Total Tickets</div>
                    <div class="text-2xl font-bold text-primary-purple mt-1"><span id="total-tickets-count">0</span></div>
                </div>
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-purple" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M8 7V4a4 4 0 018 0v3"/></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Search (visible on mobile only) -->
    <div class="lg:hidden px-4 mb-4">
        <div class="flex items-center gap-2 mb-3">
            <input type="text" id="ticket-search-input-mobile" placeholder="Search tickets by ID, student name, type, or details..." class="input input-bordered flex-1 min-w-0 rounded-lg" />
            <button id="refresh-support-tickets-btn" class="btn btn-ghost btn-square h-10 w-10" title="Refresh tickets" onclick="refreshSupportTickets()" aria-label="Refresh tickets">
                <svg id="refresh-support-tickets-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </button>
        </div>
        <select id="mobile-admin-ticket-sort-select" class="select select-bordered select-sm w-full">
            <option value="id-desc">Ticket ID (Newest First)</option>
            <option value="id-asc">Ticket ID (Oldest First)</option>
            <option value="student_id-asc">Student ID (A-Z)</option>
            <option value="student_id-desc">Student ID (Z-A)</option>
            <option value="student_name-asc">Student Name (A-Z)</option>
            <option value="student_name-desc">Student Name (Z-A)</option>
            <option value="type-asc">Issue Type (A-Z)</option>
            <option value="type-desc">Issue Type (Z-A)</option>
            <option value="status-asc">Status (A-Z)</option>
            <option value="status-desc">Status (Z-A)</option>
        </select>
    </div>
    
    <div class="flex-1 bg-transparent rounded-2xl p-4 shadow-none overflow-y-auto">
        <div class="hidden lg:flex items-center gap-3 mb-4">
            <input type="text" id="ticket-search-input" placeholder="Search tickets by ID, student name, type, or details..." class="input input-bordered flex-1 rounded-lg" />
            <button id="refresh-support-tickets-btn" class="btn btn-ghost btn-square" title="Refresh tickets" onclick="refreshSupportTickets()">
                <svg id="refresh-support-tickets-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </button>
        </div>
        
        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-center" style="min-width: 100px; width: 100px; white-space: nowrap;">
                            <button id="ticket-id-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Ticket ID">
                                Ticket ID
                                <span id="ticket-id-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 100px; width: 100px; white-space: nowrap;">
                            <button id="ticket-student-id-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Student ID">
                                Student ID
                                <span id="ticket-student-id-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 150px; width: 150px; white-space: nowrap;">
                            <button id="ticket-student-name-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Student Name">
                                Student Name
                                <span id="ticket-student-name-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 120px; width: 120px; white-space: nowrap;">
                            <button id="ticket-issue-type-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Issue Type">
                                Issue Type
                                <span id="ticket-issue-type-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center font-bold" style="min-width: 200px; width: 200px;">Details</th>
                        <th class="text-center" style="min-width: 90px; width: 90px; white-space: nowrap;">
                            <button id="ticket-status-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Status">
                                Status
                                <span id="ticket-status-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody id="ticket-table-body">
                    <tr><td colspan="6" class="text-center text-gray-500 py-4">Loading tickets...</td></tr>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View (visible on mobile only) -->
        <div class="lg:hidden flex flex-col gap-3" id="ticket-cards-container">
            <div class="text-center text-gray-500 py-4">Loading tickets...</div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/super_admin/support-page.blade.php ENDPATH**/ ?>