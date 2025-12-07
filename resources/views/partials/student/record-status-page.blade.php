<div id="record-status-page" class="page-content flex flex-col flex-1-dynamic">
          <p class="text-sm text-gray-600 mt-2 pb-6 text-center lg:text-left">View the status of your submitted records, track approvals, and manage pending entries.</p>
    <div class="flex flex-col md:flex-row justify-between px-4 items-start md:items-center mb-6 gap-4">
        <button class="btn btn-primary-purple rounded-lg border-0 w-full md:w-auto" onclick="document.getElementById('add_record_modal').showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="whitespace-nowrap">Add New Record</span>
        </button>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 w-full md:w-auto">
            <!-- Export PDF Button -->
            <button id="export-pdf-btn" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg border-0" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                </svg>
                <span class="whitespace-nowrap">Export PDF</span>
            </button>
            
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="input input-bordered flex items-center gap-2 rounded-lg flex-1 sm:flex-initial">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                    <input id="record-search" type="text" class="grow" placeholder="Search by event, venue, or organization" />
                </label>
                
                <!-- Refresh Button -->
                <button id="refresh-records-btn" onclick="refreshRecords()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh records">
                    <svg id="refresh-records-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span class="hidden lg:inline">Refresh</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Controls (visible on mobile only) -->
    <div class="lg:hidden px-4 mb-4 flex items-center gap-2">
        <!-- Sort Dropdown for Mobile -->
        <select id="mobile-sort-select" class="select select-bordered select-sm flex-1">
            <option value="date-desc">Date (Newest First)</option>
            <option value="date-asc">Date (Oldest First)</option>
            <option value="eventname-asc">Event Name (A-Z)</option>
            <option value="eventname-desc">Event Name (Z-A)</option>
            <option value="status-asc">Status (A-Z)</option>
            <option value="status-desc">Status (Z-A)</option>
            <option value="hours-desc">Hours (High-Low)</option>
            <option value="hours-asc">Hours (Low-High)</option>
        </select>
        
        <!-- Delete Selected Button for Mobile -->
        <button id="mobile-delete-selected" class="btn btn-error btn-sm" title="Delete selected pending records">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
        </button>
    </div>

    <!-- Desktop Table View (hidden on mobile) -->
    <div class="hidden lg:block bg-white rounded-2xl p-6 shadow-sm flex-1 flex-col min-h-0">
         <div class="overflow-x-auto overflow-y-auto rounded-lg" style="max-height: calc(120vh - 280px);">
            <table class="table table-xs table-pin-rows">
                <thead class="text-gray-600" style="height: 60px; background-color: #f9fafb !important;">
                    <tr style="background-color: #f9fafb !important;">
                        <th class="text-center" style="min-width: 50px; width: 50px; height: 60px;">
                            <button id="delete-selected" class="btn btn-ghost btn-xs" title="Delete selected (Pending only)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 80px; width: 80px; height: 60px; white-space: nowrap;">
                            <button id="date-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Date">
                                Date
                                <span id="date-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 110px; width: 110px; height: 60px; white-space: nowrap;">
                            <button id="eventname-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Event Name">
                                Event Name
                                <span id="eventname-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 100px; width: 100px; height: 60px; white-space: nowrap;">
                            <button id="venue-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Venue">
                                Venue
                                <span id="venue-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 140px; width: 140px; height: 60px; white-space: nowrap;">
                            <button id="organization-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Organization">
                                Organization
                                <span id="organization-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 120px; width: 120px; height: 60px; white-space: nowrap;">
                            <button id="supervisor-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Supervisor">
                                Supervisor
                                <span id="supervisor-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 50px; width: 50px; height: 60px; white-space: nowrap;">
                            <button id="hours-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Hours Rendered">
                                Hours
                                <span id="hours-sort-indicator">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 180px; width: 180px; height: 60px;">
                            <div class="flex items-center justify-center gap-1 font-bold">
                                <button id="status-sort-toggle" class="btn btn-ghost btn-xs gap-1 font-bold" title="Sort by Status">
                                    <span>Status</span>
                                    <span id="status-sort-indicator">⇅</span>
                                </button>
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
                                        <li><a onclick="filterTableByStatus('Approved', event)">Approved</a></li>
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
    
    <!-- Mobile Card View (visible on mobile only) -->
    <div class="lg:hidden flex flex-col gap-3 px-4" id="record-cards-container" style="max-height: calc(100vh - 350px); overflow-y: auto;">
        <!-- Cards will be dynamically inserted here -->
    </div>
</div>

<!-- Delete Confirmation Modal -->
<dialog id="delete_confirmation_modal" class="modal">
    <form method="dialog" class="modal-box">
        <h3 class="font-bold text-lg">Confirm Deletion</h3>
        <p class="py-4">Are you sure you want to delete <span id="delete-count">0</span> pending record(s)? This action cannot be undone.</p>
        <div class="modal-action">
            <button type="button" class="btn" onclick="document.getElementById('delete_confirmation_modal').close()">Cancel</button>
            <button type="button" id="confirm-delete-btn" class="btn btn-error">Delete</button>
        </div>
    </form>
</dialog>
