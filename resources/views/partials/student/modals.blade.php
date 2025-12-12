<!-- Add Record Modal -->
<dialog id="add_record_modal" class="modal">
    <div class="modal-box p-6 max-w-lg rounded-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
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
                    <span class="label-text font-semibold">Name of Organizing Committee or Supervisor</span>
                </div>
                <input type="text" id="organization" placeholder="Enter the name here" class="input input-bordered w-full rounded-lg" required />
            </label>

            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">Supervisor Name</span>
                </div>
                <input type="text" id="supervisor-name" placeholder="Enter supervisor name here" class="input input-bordered w-full rounded-lg" />
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

<!-- Confirmation Modal -->
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
                <p class="text-sm text-text-muted" id="status-modal-subtitle">Showing all records with this status</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <!-- Records Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th class="text-center">Date</th>
                        <th class="text-center">Event Name</th>
                        <th class="text-center">Venue</th>
                        <th class="text-center">Supervisor Name</th>
                        <th class="text-center">Organization</th>
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4 status-modal-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <p class="text-gray-500 text-lg font-semibold status-modal-empty-title">No records found</p>
            <p class="text-gray-400 text-sm mt-2 status-modal-empty-subtitle">There are no records with this status yet.</p>
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
            <div>
                <button id="view-status-records-btn" class="btn bg-primary-purple hover:bg-primary-purple-hover text-white rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View
                </button>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Rejection Reason Modal -->
<dialog id="rejection_reason_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-gradient-rejected p-3 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-text-header">Rejection Reason</h3>
                <p class="text-sm text-text-muted">Why this submission was rejected</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-red-100 dark:bg-red-900/20 border-2 border-red-300 dark:border-red-800 rounded-lg p-4">
            <p class="text-base whitespace-pre-wrap font-bold leading-relaxed" id="rejection-reason-text" style="color: #991b1b;">
                <!-- Rejection reason will be injected here -->
            </p>
        </div>
        
        <div class="mt-6 text-center">
            <p class="text-xs text-text-muted">Please review and correct the issues mentioned above before resubmitting.</p>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Delete Pending Records Confirmation Modal -->
<dialog id="delete_pending_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-full delete-modal-icon-bg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600 delete-modal-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-900 delete-modal-title">Delete Pending Records</h3>
                <p class="text-sm text-gray-600 delete-modal-subtitle">Are you sure you want to delete?</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-4 delete-modal-warning-box">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5 delete-modal-warning-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 mb-1 delete-modal-warning-label">Warning</p>
                    <p class="text-sm text-red-700 delete-modal-warning-text">You are about to delete <span id="delete-count-text" class="font-bold">0</span> pending record(s). This action cannot be undone.</p>
                    <p class="text-xs text-red-600 mt-2 delete-modal-note" id="non-pending-warning" style="display: none;">Note: Non-pending records have been excluded from deletion.</p>
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <form method="dialog" class="inline">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('delete_pending_modal').close()">Cancel</button>
            </form>
            <button type="button" class="btn bg-red-600 hover:bg-red-700 text-white" onclick="confirmDeletePending()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Records
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Delete Ticket Confirmation Modal -->
<dialog id="delete_ticket_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-red-100 p-3 rounded-full delete-ticket-icon-bg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600 delete-ticket-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-900 delete-ticket-title">Delete Support Ticket</h3>
                <p class="text-sm text-gray-600 delete-ticket-subtitle">Are you sure you want to delete this ticket?</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-4 delete-ticket-warning-box">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 flex-shrink-0 mt-0.5 delete-ticket-warning-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-yellow-800 mb-1 delete-ticket-warning-label">Warning</p>
                    <p class="text-sm text-yellow-700 delete-ticket-warning-text">This action cannot be undone. The ticket and all its details will be permanently removed.</p>
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <form method="dialog" class="inline">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('delete_ticket_modal').close()">Cancel</button>
            </form>
            <button type="button" class="btn bg-red-600 hover:bg-red-700 text-white" onclick="confirmDeleteTicket()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Ticket
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Mark Ticket as Done Confirmation Modal -->
<dialog id="mark_done_ticket_modal" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-blue-100 p-3 rounded-full mark-done-icon-bg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 mark-done-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-xl text-gray-900 mark-done-title">Mark Ticket as Done</h3>
                <p class="text-sm text-gray-600 mark-done-subtitle">Confirm ticket resolution</p>
            </div>
        </div>
        
        <div class="divider my-4"></div>
        
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-4 mark-done-info-box">
            <div class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5 mark-done-info-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800 mb-1 mark-done-info-label">Information</p>
                    <p class="text-sm text-blue-700 mark-done-info-text">Marking this ticket as done will remove it from your ticket list. Only mark as done if your issue has been fully resolved.</p>
                </div>
            </div>
        </div>
        
        <div class="modal-action">
            <form method="dialog" class="inline">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('mark_done_ticket_modal').close()">Cancel</button>
            </form>
            <button type="button" class="btn bg-blue-600 hover:bg-blue-700 text-white" onclick="confirmMarkTicketDone()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Mark as Done
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- All Notifications Modal -->
<dialog id="all_notifications_modal" class="modal">
    <div class="modal-box max-w-2xl max-h-[80vh]">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="bg-primary-purple p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-xl text-text-header">All Notifications</h3>
                    <p class="text-sm text-text-muted">Your recent activity updates</p>
                </div>
            </div>
            <button id="mark-all-read-btn" class="btn btn-sm btn-ghost text-primary-purple hover:bg-primary-purple/10">
                Mark all as read
            </button>
        </div>
        
        <div class="divider my-4"></div>
        
        <div id="all-notifications-list" class="space-y-2 overflow-y-auto max-h-[500px]">
            <!-- Inactive Account Warning (Always at top if account is inactive) -->
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
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm sticky top-0 z-10 notif-inactive-warning">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 notif-inactive-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-red-800 text-sm notif-inactive-title">⚠️ Account Inactive - Deletion Scheduled</h4>
                            <p class="text-xs text-red-700 mt-1 notif-inactive-text">
                                Your account will be deleted in 
                                <strong class="text-red-900 notif-inactive-strong">
                                    @if($daysRemaining > 0)
                                        {{ $daysRemaining }} day{{ $daysRemaining != 1 ? 's' : '' }}
                                    @else
                                        {{ $hoursRemaining }} hour{{ $hoursRemaining != 1 ? 's' : '' }}
                                    @endif
                                </strong>
                                ({{ $deletionDate->format('M d, Y') }})
                            </p>
                            <p class="text-xs text-red-600 mt-1 notif-inactive-note">Contact your administrator immediately to reactivate your account.</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- All notifications will be loaded here -->
            <div class="flex items-center justify-center py-8">
                <span class="loading loading-spinner loading-md text-primary-purple"></span>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Submit Ticket Modal -->
<dialog id="submit_ticket_modal" class="modal">
    <div class="modal-box p-6 max-w-lg rounded-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        <h3 class="font-bold text-xl mb-6 text-center text-text-header">Submit New Support Ticket</h3>
        <form id="submit-ticket-form" class="space-y-4">
            <p class="text-sm text-text-muted mb-4">Please select the issue you need assistance with.</p>

            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">Select Issue Type</span>
                </div>
                <select id="ticket-issue-type" class="select select-bordered w-full rounded-lg" required>
                    <option value="" disabled selected>Select the issue...</option>
                    <option value="1">Incorrect Student Number</option>
                    <option value="2">Inaccessible PLV Email</option>
                    <option value="3">Incorrect/Misspelled Name</option>
                    <option value="4">Reactivate Account</option>
                    <option value="5">Submitted Record Linked to Wrong Academic Year</option>
                    <option value="99">Others (Specify below)</option>
                </select>
            </label>

            <label id="other-issue-container" class="form-control w-full hidden">
                <div class="label">
                    <span class="label-text font-semibold">Specific Reason (Required)</span>
                </div>
                <textarea id="ticket-details-other" class="textarea textarea-bordered h-24 rounded-lg"
                    placeholder="Please specify the exact nature of your issue..."></textarea>
            </label>

            <label id="record-selector-container" class="form-control w-full hidden">
                <div class="label">
                    <span class="label-text font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 00-4 4v4a4 4 0 004 4h4a4 4 0 004-4V8a4 4 0 00-4-4H8zm0 2h4a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z" clip-rule="evenodd" />
                            <path d="M5 5a1 1 0 011-1h1a1 1 0 110 2H6a1 1 0 01-1-1zm0 4a1 1 0 011-1h1a1 1 0 110 2H6a1 1 0 01-1-1zm9-4a1 1 0 10-2 0v1a1 1 0 102 0V5z" />
                        </svg>
                        Attach/Link Record (Required)
                    </span>
                </div>
                <select id="ticket-record-id" class="select select-bordered w-full rounded-lg">
                    <option value="" disabled selected>Loading records...</option>
                </select>
                <div class="label">
                    <span class="label-text-alt text-text-muted">📌 Select the verified or approved record that was linked to the wrong academic year</span>
                </div>
            </label>
            
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">Reason/Details (required)</span>
                </div>
                <textarea id="ticket-details" class="textarea textarea-bordered h-24 rounded-lg"
                    placeholder="Please briefly explain the issue and what correction is needed." required></textarea>
            </label>

            <div class="mt-8 pt-4 flex justify-center">
                <button type="button" id="confirm-ticket-submit"
                    class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                    Submit Ticket
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Ticket Details Modal -->
<dialog id="ticket_details_modal" class="modal">
    <div class="modal-box p-6 max-w-2xl rounded-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        <h3 class="font-bold text-2xl mb-6 text-center text-primary-purple">Ticket Details</h3>
        
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-600 ticket-detail-label">Ticket ID</p>
                    <p id="modal-ticket-id" class="text-lg font-bold text-gray-900 ticket-detail-value"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600 ticket-detail-label">Status</p>
                    <div id="modal-ticket-status" class="inline-block mt-1"></div>
                </div>
            </div>
            
            <div>
                <p class="text-sm font-semibold text-gray-600 ticket-detail-label">Student Name</p>
                <p id="modal-ticket-student" class="text-base text-gray-900 ticket-detail-value"></p>
            </div>
            
            <div>
                <p class="text-sm font-semibold text-gray-600 ticket-detail-label">Issue Type</p>
                <p id="modal-ticket-type" class="text-base text-gray-900 ticket-detail-value"></p>
            </div>
            
            <div>
                <p class="text-sm font-semibold text-gray-600 ticket-detail-label">Details</p>
                <p id="modal-ticket-details" class="text-base text-gray-900 whitespace-pre-wrap ticket-detail-value"></p>
            </div>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                <div>
                    <p class="text-sm font-semibold text-gray-600 ticket-detail-label">Submitted</p>
                    <p id="modal-ticket-submitted" class="text-sm text-gray-700 ticket-detail-date"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600 ticket-detail-label">Last Updated</p>
                    <p id="modal-ticket-updated" class="text-sm text-gray-700 ticket-detail-date"></p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-center">
            <button type="button" onclick="document.getElementById('ticket_details_modal').close()" 
                class="btn bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                Close
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Export Options Modal -->
<dialog id="export_options_modal" class="modal">
    <div class="modal-box p-6 max-w-sm rounded-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        </form>
        <h3 class="font-bold text-xl mb-6 text-center text-text-header">Export Approved Records</h3>
        <div class="space-y-4">
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">Select School Year</span>
                </div>
                <select id="export-school-year" class="select select-bordered w-full rounded-lg">
                    <!-- Options will be populated by JS -->
                </select>
            </label>
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">Select Year Level</span>
                </div>
                <select id="export-year-level" class="select select-bordered w-full rounded-lg">
                    <option>1st Year</option>
                    <option>2nd Year</option>
                    <option selected>3rd Year</option>
                    <option>4th Year</option>
                </select>
            </label>
            <div class="modal-action mt-6">
                <button type="button" class="btn rounded-lg w-full" style="background-color: #6D28D9; color: white; border: none;" onmouseover="this.style.backgroundColor='#5B21B6'" onmouseout="this.style.backgroundColor='#6D28D9'" onclick="exportToPDF()">Generate PDF</button>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"> <button>close</button> </form>
</dialog>

<!-- DaisyUI toast root (bottom-right) -->
<div id="toast-root" class="toast toast-bottom toast-end fixed bottom-4 right-4 z-[5000] space-y-2"></div>
