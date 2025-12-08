    <!-- Modals -->
    <!-- Verify Modal -->
    <dialog id="verify_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Verify Submission</h3>
            <p class="py-4">Are you sure you want to verify this submission? This will move it to "For Approval" status.</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-verify-btn" class="btn btn-action-verify text-white" style="background-color: #13AAAA;">
                        Yes, verify
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Approve Modal -->
    <dialog id="approve_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg dark:text-white" style="color: #000000;">Approve Submission</h3>
            <p class="py-4 dark:text-gray-300" style="color: #3d3d3dff;">Are you sure you want to approve this submission?</p>
            <div class="bg-yellow-100 dark:bg-yellow-900/30 border-l-4 border-yellow-500 dark:border-yellow-600 p-4 rounded-r-lg mb-4" role="alert">
                <p class="font-bold"  style="color: #9f5700ff;">Important Notice</p>
                <p class="dark:text-yellow-200" style="color: #9f5700ff;">Once approved, this record will now appear on the Students page and this action cannot be undone.</p>
            </div>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-approve-btn" class="btn bg-success-green hover:bg-success-green-hover text-white">
                        Yes, approve
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Reject Modal with Reason -->
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
        <form method="dialog" class="modal-backdrop" onclick="resetRejectModal()">
            <button>close</button>
        </form>
    </dialog>

    <!-- Activity Details Modal -->
    <dialog id="activity_details_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <h3 class="font-bold text-lg text-text-header mb-4">Activity on <span id="activity-date"></span></h3>
            
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

    <!-- Logout Modal -->
    <dialog id="logout_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Log Out?</h3>
            <p class="py-4">Are you sure you want to log out?</p>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Cancel</button>
                </form>
                <button onclick="window.location.href='<?php echo e(route('superadmin.logout.beacon')); ?>'" class="btn bg-danger-red hover:bg-danger-red-hover text-white">
                    Yes, log out
                </button>
            </div>
        </div>
    </dialog>

    <!-- Student Edit Modal -->
    <dialog id="student_edit_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl p-6 relative">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <h3 class="font-bold text-2xl text-primary-purple mb-6">Student Profile</h3>
            
            <form id="student-edit-form" class="space-y-4">
                <input type="hidden" id="edit-student-user-id" />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Full Name</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit-student-name" 
                            class="input input-bordered w-full" 
                            required 
                        />
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Student ID</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit-student-id" 
                            class="input input-bordered w-full" 
                            required 
                        />
                    </div>
                </div>
                
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Email Address</span>
                    </label>
                    <input 
                        type="email" 
                        id="edit-student-email" 
                        class="input input-bordered w-full bg-gray-100" 
                        readonly 
                    />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Approved Hours</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit-student-approved-hours" 
                            class="input input-bordered w-full bg-gray-100" 
                            readonly 
                        />
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Account Status</span>
                        </label>
                        <select id="edit-student-status" class="select select-bordered w-full">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        
                        <!-- Inactive Account Warning -->
                        <div id="inactive-warning-box" class="hidden mt-2 p-3 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-800 rounded-r-lg">
                            <div class="flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-red-800 dark:text-red-300">Account scheduled for deletion</p>
                                    <p id="inactive-countdown-text" class="text-xs text-red-700 dark:text-red-400 mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-action justify-between">
                    <button type="button" class="btn bg-danger-red hover:bg-danger-red-hover text-white" onclick="openDeleteStudentModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Account
                    </button>
                    <div class="flex gap-2">
                        <form method="dialog" class="inline">
                            <button type="button" class="btn" onclick="document.getElementById('student_edit_modal').close()">Cancel</button>
                        </form>
                        <button type="submit" class="btn bg-success-green hover:bg-success-green-hover text-white">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </dialog>

    <!-- View Student Modal (Read-Only) -->
    <dialog id="student_view_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl p-6 relative">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <h3 class="font-bold text-2xl text-primary-purple mb-6">Student Information</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Full Name</span>
                        </label>
                        <p id="view-student-name" class="text-base font-medium px-3 py-2 student-info-box rounded-lg">â€”</p>
                    </div>
                    
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Student ID</span>
                        </label>
                        <p id="view-student-id" class="text-base font-medium px-3 py-2 student-info-box rounded-lg">â€”</p>
                    </div>
                </div>
                
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Email Address</span>
                    </label>
                    <p id="view-student-email" class="text-base font-medium px-3 py-2 student-info-box rounded-lg">â€”</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Email Verified</span>
                        </label>
                        <div id="view-student-email-verified" class="px-3 py-2">â€”</div>
                    </div>

                    <div class="form-control w-full">
                        <label class="label">
                            <span class="label-text font-semibold">Approved Hours</span>
                        </label>
                        <p id="view-student-approved-hours" class="text-base font-semibold text-primary-purple px-3 py-2 student-info-box rounded-lg">â€”</p>
                    </div>
                </div>
                
                <div class="form-control w-full">
                    <label class="label">
                        <span class="label-text font-semibold">Account Status</span>
                    </label>
                    <div id="view-student-status" class="px-3 py-2">â€”</div>
                </div>
                
                <div class="modal-action">
                    <button type="button" class="btn" onclick="document.getElementById('student_view_modal').close()">Close</button>
                    <button type="button" class="btn bg-primary-purple hover:bg-primary-purple-hover text-white" onclick="openEditFromView()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Student
                    </button>
                </div>
            </div>
        </div>
    </dialog>

    <!-- Delete Student Confirmation Modal -->
    <dialog id="delete_student_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg text-danger-red">Delete Student Account</h3>
            <p class="py-4">Are you sure you want to permanently delete this student account?</p>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-4" role="alert">
                <p class="font-bold">Warning</p>
                <p>This action cannot be undone. All data associated with this student will be permanently deleted from the database.</p>
            </div>
            <div class="space-y-2 mb-4">
                <p class="text-sm"><strong>Student Name:</strong> <span id="delete-student-name-display"></span></p>
                <p class="text-sm"><strong>Student ID:</strong> <span id="delete-student-id-display"></span></p>
                <p class="text-sm"><strong>Email:</strong> <span id="delete-student-email-display"></span></p>
            </div>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn">Cancel</button>
                    <button id="confirm-delete-student-btn" type="button" class="btn bg-danger-red hover:bg-danger-red-hover text-white">
                        Yes, Delete Account
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Submission Details Modal -->
    <dialog id="submission_details_modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl p-6 relative">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
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
                <div class="flex items-center gap-2">
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

    <!-- Status Records Modal -->
    <dialog id="status_records_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
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

    <!-- Submit Ticket Modal -->
    <dialog id="submit_ticket_modal" class="modal">
        <!-- Removed: Super admin doesn't submit tickets -->
    </dialog>

    <!-- Ticket Details Modal for Super Admin -->
    <dialog id="ticket_details_modal" class="modal">
        <div class="modal-box p-6 max-w-2xl rounded-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            <h3 class="font-bold text-2xl mb-6 text-center text-primary-purple">Ticket Details</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Ticket ID</p>
                        <p id="modal-ticket-id" class="text-lg font-bold text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Status</p>
                        <div id="modal-ticket-status" class="inline-block mt-1"></div>
                    </div>
                </div>
                
                <div>
                    <p class="text-sm font-semibold text-gray-600">Student Name</p>
                    <p id="modal-ticket-student" class="text-base text-gray-900"></p>
                </div>
                
                <div>
                    <p class="text-sm font-semibold text-gray-600">Student ID</p>
                    <p id="modal-ticket-student-id" class="text-base text-gray-900"></p>
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-600">Issue Type</p>
                        <button id="find-record-btn" class="btn btn-sm btn-outline btn-primary rounded-lg hidden" onclick="findLinkedRecord()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Find Record
                        </button>
                    </div>
                    <p id="modal-ticket-type" class="text-base text-gray-900"></p>
                </div>
                
                <div>
                    <p class="text-sm font-semibold text-gray-600">Details</p>
                    <p id="modal-ticket-details" class="text-base text-gray-900 whitespace-pre-wrap"></p>
                </div>
                
                <div id="modal-ticket-linked-record-container" class="hidden">
                    <p class="text-sm font-semibold text-gray-600 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 00-4 4v4a4 4 0 004 4h4a4 4 0 004-4V8a4 4 0 00-4-4H8zm0 2h4a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z" clip-rule="evenodd" />
                        </svg>
                        Linked Record
                    </p>
                    <p id="modal-ticket-linked-record" class="text-base text-gray-900 bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg mt-2"></p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Submitted</p>
                        <p id="modal-ticket-submitted" class="text-sm text-gray-700"></p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Last Updated</p>
                        <p id="modal-ticket-updated" class="text-sm text-gray-700"></p>
                    </div>
                </div>

                <div class="pt-4 border-t" id="resolve-action-container">
                    <p class="text-sm font-semibold text-gray-600 mb-2">Actions</p>
                    <button type="button" id="resolve-ticket-btn" class="btn bg-green-600 hover:bg-green-700 text-white rounded-lg w-full">
                        Mark as Resolved
                    </button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- First Delete Confirmation Modal -->
    <dialog id="delete_record_modal_1" class="modal">
        <div class="modal-box max-w-md rounded-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <div class="text-center py-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-error/10 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="font-bold text-2xl mb-2 text-text-header">Delete Record?</h3>
                <p class="text-text-muted mb-4">Are you sure you want to delete this record?</p>
                
                <div class="bg-base-200 p-4 rounded-lg mb-6 text-left">
                    <p class="text-sm text-text-muted mb-1">Student ID</p>
                    <p id="delete-modal-1-student-id" class="font-semibold text-text-header mb-3"></p>
                    <p class="text-sm text-text-muted mb-1">Event Name</p>
                    <p id="delete-modal-1-event-name" class="font-semibold text-text-header"></p>
                </div>
                
                <div class="flex gap-3 justify-center">
                    <form method="dialog">
                        <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <button onclick="showSecondDeleteModal()" class="btn bg-error hover:bg-error/90 text-white">
                        Continue
                    </button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Second Delete Confirmation Modal (Final Warning) -->
    <dialog id="delete_record_modal_2" class="modal">
        <div class="modal-box max-w-md rounded-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-4 top-4">✕</button>
            </form>
            
            <div class="text-center py-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-error mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                
                <h3 class="font-bold text-2xl mb-2 text-error">Final Warning!</h3>
                <p class="text-text-muted mb-2 font-semibold">This action cannot be undone!</p>
                <p class="text-sm text-text-muted mb-6">All information about this record will be permanently deleted from the database and social contract.</p>
                
                <div class="alert alert-error mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-sm font-semibold">This will permanently delete the record!</span>
                </div>
                
                <div class="flex gap-3 justify-center">
                    <form method="dialog">
                        <button class="btn btn-ghost">Cancel</button>
                    </form>
                    <button onclick="confirmDeleteRecord()" class="btn bg-error hover:bg-error/90 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Permanently
                    </button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Toast Container -->
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\partials\super_admin\modals.blade.php ENDPATH**/ ?>