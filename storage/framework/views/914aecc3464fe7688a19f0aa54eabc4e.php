<!-- Students Information Page -->
<div id="students-page" class="page-content hidden flex-col flex-1-dynamic">
    <h1 class="text-4xl font-bold text-primary-purple px-4">Students Information</h1>
    <p class="text-sm text-gray-600 mt-2 pl-4 pb-6 text-center lg:text-left">&#9432 Search, view, and manage student records and contact information.</p>

    <!-- Student stats (2x2 grid on mobile) -->
    <div class="px-4 mb-6">
        <div class="grid grid-cols-2 gap-4 w-full">
            <div class="bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Verified Students</div>
                    <div class="text-2xl font-bold text-primary-purple"><span id="students-verified-count">0</span></div>
                </div>
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Unverified Students</div>
                    <div class="text-2xl font-bold text-primary-purple"><span id="students-unverified-count">0</span></div>
                </div>
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Active Students</div>
                    <div class="text-2xl font-bold text-primary-purple"><span id="students-active-count">0</span></div>
                </div>
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500">Inactive Students</div>
                    <div class="text-2xl font-bold text-primary-purple"><span id="students-inactive-count">0</span></div>
                </div>
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </div>
        </div>
    </div>

     <!-- Search and Actions Bar -->
    <div class="flex items-center justify-between px-4 py-3 gap-4">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <input 
                type="text" 
                id="students-search" 
                placeholder="Search by name, student ID, or email..." 
                class="input input-bordered w-full rounded-lg pr-10"
            />
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 inset-y-0 my-auto h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        
        <!-- Refresh Button -->
        <button id="refresh-students-btn" onclick="refreshStudents()" class="btn btn-ghost btn-sm h-10 gap-2" title="Refresh students list">
            <svg id="refresh-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Refresh
        </button>
    </div>

    <!-- Students Table -->
   <div id="students-table-container" class="bg-white rounded-2xl p-6 shadow-sm flex-1 flex flex-col min-h-0">
         <div class="overflow-x-auto overflow-y-auto rounded-lg" style="max-height: calc(110vh - 280px);"> 
            <table class="table table-xs table-pin-rows">
                <thead class="text-gray-600" style="height: 60px; background-color: #f9fafb !important;">
                    <tr>
                        <th class="text-center" style="min-width: 90px; width: 90px; white-space: nowrap;">
                            <button id="student-id-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Student ID">
                                Student ID
                                <span id="student-id-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 160px; width: 160px; white-space: nowrap;">
                            <button id="full-name-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Full Name">
                                Full Name
                                <span id="full-name-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 200px; width: 200px; white-space: nowrap;">
                            <button id="email-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Email">
                                Email
                                <span id="email-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 110px; width: 110px; white-space: nowrap;">
                            <button id="email-verified-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Email Verified">
                                Email Verified
                                <span id="email-verified-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 120px; width: 120px; white-space: nowrap;">
                            <button id="approved-hours-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Approved Hours">
                                Approved Hours
                                <span id="approved-hours-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center" style="min-width: 80px; width: 80px; white-space: nowrap;">
                            <button id="status-sort" class="btn btn-ghost btn-xs gap-1 hover:bg-gray-200 font-bold" title="Sort by Status">
                                Status
                                <span id="status-sort-icon" class="text-xs">⇅</span>
                            </button>
                        </th>
                        <th class="text-center font-bold" style="min-width: 100px; width: 100px;">Action</th>
                    </tr>
                </thead>
                <tbody id="students-table-body">
                    <tr id="students-loading-row">
                        <td colspan="7" class="text-center py-8">
                            <span class="loading loading-spinner loading-lg text-primary-purple"></span>
                            <p class="mt-2 text-text-muted">Loading students...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/super_admin/students-page.blade.php ENDPATH**/ ?>