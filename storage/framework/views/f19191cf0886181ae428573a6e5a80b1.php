<!-- Modals -->
<!-- Activity Details Modal -->
<dialog id="activity_details_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <h3 class="font-bold text-lg text-text-header mb-4">Activity on <span id="activity-date-header"></span></h3>
        
        <div id="activity-details-content" class="space-y-3">
            <!-- Activity details will be loaded here -->
        </div>
        
        <div id="activity-loading" class="text-center py-8">
            <span class="loading loading-spinner loading-lg text-primary-purple"></span>
            <p class="mt-2 text-text-muted">Loading activities...</p>
        </div>
        
        <div id="activity-no-data" class="text-center py-8 hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="mt-4 text-text-muted">No activity recorded on this date</p>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

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
        <h3 class="font-bold text-lg text-text-header">Reject Submission</h3>
        <p class="py-4 text-text-body">Please select or provide a reason for rejecting this submission. The student will be notified.</p>
        
        <label class="form-control w-full mb-4">
            <div class="label">
                <span class="label-text font-semibold">Rejection Reason</span>
            </div>
            <select id="rejection-reason-select" class="select select-bordered w-full">
                <option disabled selected value="">Select a reason</option>
                <option value="Incorrect or Invalid Information&#10;The provided details do not match official PLV records or contain false information.">
                    Incorrect or Invalid Information
                </option>
                <option value="Duplicate Submission&#10;The same form or request has already been submitted and is recorded in the system.">
                    Duplicate Submission
                </option>
                <option value="Late Submission&#10;The form was filed beyond the official deadline or submission period.">
                    Late Submission
                </option>
                <option value="Others">Others</option>
            </select>
        </label>
        
        <label id="other-reason-label" class="form-control w-full hidden">
            <div class="label">
                <span class="label-text font-semibold">Specify Reason</span>
            </div>
            <textarea 
                id="reject-reason-textarea" 
                class="textarea textarea-bordered h-24 resize-none focus:outline-none focus:border-primary-purple" 
                placeholder="Please specify the reason for rejection..."
            ></textarea>
        </label>
        
        <div class="modal-action mt-6">
            <form method="dialog" class="flex gap-2">
                <button class="btn btn-ghost" onclick="resetRejectModal()">Cancel</button>
                <button id="confirm-reject-btn" type="button" class="btn bg-danger-red hover:bg-danger-red-hover text-white">
                    Yes, reject
                </button>
            </form>
        </div>
    </div>
</dialog>

<!-- Submission Details Modal -->
<dialog id="submission_details_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl p-6 relative">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
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
            <div class="flex items-center gap-3">
                <div id="details-status-badge" class="status-badge"></div>
                <span id="details-action-date" class="text-sm text-gray-500"></span>
            </div>
        </div>
        
        <div id="details-reason-container" class="hidden mt-4 border-t pt-4">
            <label class="details-label">Reason for Rejection</label>
            <p class="font-medium whitespace-pre-line bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-800 p-3 rounded-lg" style="color: #1a1a1a;" id="details-reason-text"></p>
        </div>
        
        <div id="details-action-buttons" class="mt-6 flex gap-2"></div>
    </div>
</dialog>

<!-- Confirm Delete Single Record Modal -->
<dialog id="confirm_delete_record_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-900">Delete Record</h3>
                <p class="text-sm text-gray-600">Confirm deletion</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-4">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-yellow-800 mb-1">Warning</p>
                    <p class="text-sm text-yellow-700">Are you sure you want to delete this rejected record? This action cannot be undone.</p>
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('confirm_delete_record_modal').close()">Cancel</button>
            <button type="button" class="btn bg-red-600 hover:bg-red-700 text-white" onclick="confirmDeleteRecord()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Record
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Confirm Delete Single Account Modal -->
<dialog id="confirm_delete_account_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-900">Delete Account</h3>
                <p class="text-sm text-gray-600">Permanently remove user</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-4">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1">Critical Warning</p>
                    <p class="text-sm text-red-700">Are you sure you want to delete this inactive account? This will permanently remove the user and all their data including social contract records.</p>
                    <p class="text-xs text-red-600 mt-2"><strong>This action cannot be undone.</strong></p>
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <form method="dialog" class="inline">
                <button type="button" class="btn btn-ghost">Cancel</button>
            </form>
            <button type="button" class="btn bg-red-600 hover:bg-red-700 text-white" onclick="confirmDeleteAccount()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Account
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Confirm Delete All Records Modal -->
<dialog id="confirm_delete_all_records_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-900">Delete All Eligible Records</h3>
                <p class="text-sm text-gray-600">This action cannot be undone</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-4">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1">Warning</p>
                    <p class="text-sm text-red-700">You are about to permanently delete <strong>all rejected records</strong> that are older than 7 days. This will remove them from the system permanently.</p>
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <form method="dialog" class="inline">
                <button type="button" class="btn btn-ghost">Cancel</button>
            </form>
            <button type="button" class="btn bg-red-600 hover:bg-red-700 text-white" onclick="confirmDeleteAllRecords()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete All Records
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Confirm Delete All Accounts Modal -->
<dialog id="confirm_delete_all_accounts_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-900">Delete All Eligible Accounts</h3>
                <p class="text-sm text-gray-600">This will permanently remove users</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-4">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1">Critical Warning</p>
                    <p class="text-sm text-red-700">You are about to permanently delete <strong>all inactive accounts</strong> that have been inactive for more than 7 days. This will remove all user data including their social contract records.</p>
                    <p class="text-xs text-red-600 mt-2"><strong>This action cannot be undone.</strong></p>
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <form method="dialog" class="inline">
                <button type="button" class="btn btn-ghost">Cancel</button>
            </form>
            <button type="button" class="btn bg-red-600 hover:bg-red-700 text-white" onclick="confirmDeleteAllAccounts()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete All Accounts
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Status Records Modal -->
<dialog id="status_records_modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-6">
            <div id="status-modal-icon" class="bg-white p-3 rounded-full">
                <!-- Icon will be injected here -->
            </div>
            <div>
                <h3 class="font-bold text-2xl text-text-header" id="status-modal-title">Records</h3>
                <p class="text-sm text-text-muted" id="status-modal-subtitle">Showing all records with this status this week</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <!-- Records Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th class="text-center">Student ID</th>
                        <th class="text-center">Student Name</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Event Name</th>
                        <th class="text-center">Venue</th>
                        <th class="text-center">Hours</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="status-modal-table-body">
                    <!-- Records will be injected here -->
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <div id="status-modal-empty" class="hidden text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-500 text-lg font-semibold">No records found</p>
            <p id="status-modal-empty-text" class="text-gray-400 text-sm mt-2">There are no records with this status.</p>
        </div>
        
        <!-- Summary Footer -->
        <div class="bg-base-200 rounded-lg p-4 mt-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-text-muted">Total Records</p>
                <p class="text-xl font-bold text-text-header" id="status-modal-total">0</p>
            </div>
            <div>
                <p class="text-sm text-text-muted">Total Hours</p>
                <p class="text-xl font-bold text-text-header" id="status-modal-hours">0 hours</p>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Toast Container -->
<div id="toast-root" class="toast toast-bottom toast-end fixed bottom-4 right-4 z-[5000] space-y-2"></div>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/admin/modals.blade.php ENDPATH**/ ?>