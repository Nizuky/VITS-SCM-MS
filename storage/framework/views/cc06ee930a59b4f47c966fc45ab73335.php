<!-- Data Management Page -->
<div id="data-management-page" class="page-content hidden flex flex-col">
    <div class="p-4">
        <h4 class="text-2xl md:text-3xl lg:text-4xl font-bold text-primary-purple">Data Management</h4>
        <p class="text-sm text-gray-600 mt-2 pl-4 pb-6 text-center lg:text-left">&#9432 Manage rejected records and inactive accounts.</p>
    </div>

    <!-- Rejected Records Section -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
            <div>
                <h2 class="text-lg md:text-xl font-bold text-text-header">Rejected Records</h2>
                <p class="text-xs md:text-sm text-text-muted">Records rejected more than 7 days ago are eligible for deletion</p>
            </div>
            <button id="delete-all-eligible-records-btn" class="btn btn-error text-white rounded-lg text-xs md:text-sm whitespace-nowrap" onclick="document.getElementById('confirm_delete_all_records_modal').showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span class="hidden sm:inline">Delete All Eligible Records</span>
                <span class="sm:hidden">Delete All</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-center">Student Name</th>
                        <th class="text-center">Event Name</th>
                        <th class="text-center">Rejected Date</th>
                        <th class="text-center">Days Since Rejection</th>
                        <th class="text-center">Deletion Eligible</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="rejected-records-table">
                    <tr><td colspan="6" class="text-center text-gray-500 py-4">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inactive Accounts Section -->
    <div class="bg-white rounded-2xl p-4 md:p-6 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
            <div>
                <h2 class="text-lg md:text-xl font-bold text-text-header">Inactive Accounts</h2>
                <p class="text-xs md:text-sm text-text-muted">Accounts inactive for more than 7 days are eligible for deletion</p>
            </div>
            <button id="delete-all-eligible-accounts-btn" class="btn btn-error text-white rounded-lg text-xs md:text-sm whitespace-nowrap" onclick="document.getElementById('confirm_delete_all_accounts_modal').showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span class="hidden sm:inline">Delete All Eligible Accounts</span>
                <span class="sm:hidden">Delete All</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-center">Student Name</th>
                        <th class="text-center">Student Number</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Inactive Since</th>
                        <th class="text-center">Days Inactive</th>
                        <th class="text-center">Time Until Deletion</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="inactive-accounts-table">
                    <tr><td colspan="7" class="text-center text-gray-500 py-4">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/admin/data-management-page.blade.php ENDPATH**/ ?>