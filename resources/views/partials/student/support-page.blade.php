<div id="support-page" class="page-content hidden flex flex-col">
              <p class="text-sm text-gray-600 mt-2 pb-6 text-center lg:text-left">Submit new tickets and review responses from administrators regarding student support requests.</p>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 mb-6 gap-4">
        <button class="btn btn-primary-purple rounded-lg border-0 w-full md:w-auto" onclick="document.getElementById('submit_ticket_modal').showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="whitespace-nowrap">Submit New Ticket</span>
        </button>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 w-full md:w-auto">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="input input-bordered flex items-center gap-2 rounded-lg flex-1 sm:flex-initial">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                    <input type="text" id="ticket-search-input" placeholder="Search tickets by ID, type, or details..." class="grow" />
                </label>
                
                <!-- Refresh Button -->
                <button id="refresh-tickets-btn" onclick="refreshTickets()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh tickets">
                    <svg id="refresh-tickets-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    <span class="hidden lg:inline">Refresh</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Sort Dropdown (visible on mobile only) -->
    <div class="lg:hidden px-4 mb-4">
        <select id="mobile-ticket-sort-select" class="select select-bordered select-sm w-full">
            <option value="id-desc">Ticket ID (Newest First)</option>
            <option value="id-asc">Ticket ID (Oldest First)</option>
            <option value="name-asc">Student Name (A-Z)</option>
            <option value="name-desc">Student Name (Z-A)</option>
            <option value="type-asc">Issue Type (A-Z)</option>
            <option value="type-desc">Issue Type (Z-A)</option>
            <option value="status-asc">Status (A-Z)</option>
            <option value="status-desc">Status (Z-A)</option>
        </select>
    </div>
    
    <div class="flex-1 bg-transparent rounded-2xl p-4 shadow-none overflow-y-auto mx-0">
        <div class="mb-4 flex justify-end items-center">
            <div id="ticket-limit-info" class="text-sm text-gray-600"></div>
        </div>
        
        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-center">
                            <button id="student-ticket-id-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Ticket ID">
                                Ticket ID
                                <span id="student-ticket-id-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center">
                            <button id="student-ticket-name-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Student Name">
                                Student Name
                                <span id="student-ticket-name-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center">
                            <button id="student-ticket-issue-type-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Issue Type">
                                Issue Type
                                <span id="student-ticket-issue-type-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center font-bold">Details</th>
                        <th class="text-center">
                            <button id="student-ticket-status-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Status">
                                Status
                                <span id="student-ticket-status-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center font-bold">Action</th>
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