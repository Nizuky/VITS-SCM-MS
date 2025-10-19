<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Submission Management</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'background-light': '#EDF1FA',
                        'primary-purple': '#6D28D9',
                        'primary-purple-hover': '#5B21B6',
                        'text-header': '#2B3674',
                        'text-muted': '#707EAE',
                        'badge-pending-text': '#E29C44',
                        'badge-pending-bg': '#FAEAD0',
                        'badge-verified-text': '#399552',
                        'badge-verified-bg': '#CCEED6',
                        'badge-rejected-text': '#CC525D',
                        'badge-rejected-bg': '#FFD7DB',
                        'success-green': '#4CAF50',
                        'success-green-hover': '#45a049',
                        'danger-red': '#CC525D',
                        'danger-red-hover': '#b33e46',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .active-nav { background-color: #6D28D9; color: white; border-radius: 0.5rem; }
        .flex-1-dynamic { flex: 1 1 auto; min-height: 0; }
        .content-area-auto { height: auto; max-height: 100%; }

        .custom-tab-wrapper {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            margin-right: 1.5rem;
            display: flex;
            align-items: stretch;
            padding: 0.5rem;
        }
        .custom-tab {
            font-weight: 600;
            color: #707EAE;
            padding: 0.5rem 1.25rem;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            background-color: transparent !important;
            border-radius: 0.25rem;
            line-height: 1.5;
        }
        .custom-tab:hover { color: #6D28D9; border-bottom-color: #6D28D9; }
        .custom-tab-active { color: #6D28D9 !important; border-bottom: 2px solid #6D28D9 !important; }
    </style>
</head>
<body class="min-h-screen bg-background-light">
    <?php echo $__env->make('partials.auto_logout_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="flex p-4 gap-4 min-h-screen"> 
        <aside class="flex flex-col w-64 bg-white rounded-2xl p-4 shadow-sm">
            <div class="flex flex-col items-center text-center p-4 border-b border-gray-200">
                <div class="avatar mb-3">
                    <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                        <img src="https://placehold.co/100x100/6D28D9/FFFFFF?text=AD" alt="Admin profile picture" class="rounded-full"/>
                    </div>
                </div>
                <h2 class="font-bold text-lg">Admin</h2>
                <p class="text-sm text-gray-500">Access Level: Manager</p>
            </div>

            <ul class="menu p-0 my-4 flex-grow">
                <li>
                    <a class="py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a class="py-3 bg-primary-purple active-nav" id="nav-submission" onclick="showPage('submission')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Submission
                    </a>
                </li>
            </ul>

            <ul class="menu p-0">
                <li>
                    <a class="py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        Settings
                    </a>
                </li>
                <li>
                    <a class="py-3" href="<?php echo e(route('admin.logout')); ?>" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Log Out
                    </a>
                </li>
            </ul>
        </aside>

        <main class="flex-1 flex flex-col gap-6" id="page-container">
            
            <div class="flex justify-between items-center p-4">
                <h1 class="text-3xl font-bold text-text-header">Submission Management</h1>
            </div>

            <div id="submission-page" class="page-content flex flex-col flex-1-dynamic">

                <div class="flex justify-between items-center px-4 pb-4">
                    
                    <div class="flex space-x-0 custom-tab-wrapper rounded-lg">
                        <a id="pending-tab" class="custom-tab custom-tab-active" onclick="filterSubmissions('pending', this)">Pending</a>
                        <a id="archived-tab" class="custom-tab" onclick="filterSubmissions('archived', this)">Archived</a>
                    </div>
                    
                    <label class="input input-bordered flex items-center gap-2 rounded-lg w-64 bg-white shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70"><path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" /></svg>
                        <input type="text" class="grow" placeholder="Search" />
                    </label>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm content-area-auto mt-6">
                    
                    <div class="overflow-x-auto"> 
                        <table class="table table-fixed w-full">
                            <thead class="bg-gray-50 text-gray-600">
                                <tr>
                                    <th class="w-1/12">ID</th>
                                    <th class="w-2/12">Student Name</th>
                                    <th class="w-2/12">Event Name</th>
                                    <th class="w-1/12 text-center">Hours Rendered</th>
                                    <th class="w-1/12 text-center">Date</th>
                                    <th class="w-3/12 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="submission-table-body">
                                <tr data-status="pending">
                                    <td>23-3333</td>
                                    <td>Escala, Alea</td>
                                    <td>VITS Committee</td>
                                    <td class="text-center">10 hours</td>
                                    <td class="text-center">03-23-25</td>
                                    <td class="flex justify-center gap-2">
                                        <button class="btn btn-sm text-success-green hover:bg-gray-100 bg-white border-success-green" onclick="verifyRecord(this)">Verify</button>
                                        <button class="btn btn-sm text-danger-red hover:bg-gray-100 bg-white border-danger-red" onclick="rejectRecord(this)">Reject</button>
                                    </td>
                                </tr>
                                <tr data-status="pending">
                                    <td>23-3323</td>
                                    <td>Samantha, Luayon</td>
                                    <td>Library Social Contract</td>
                                    <td class="text-center">10 hours</td>
                                    <td class="text-center">03-23-25</td>
                                    <td class="flex justify-center gap-2">
                                        <button class="btn btn-sm text-success-green hover:bg-gray-100 bg-white border-success-green" onclick="verifyRecord(this)">Verify</button>
                                        <button class="btn btn-sm text-danger-red hover:bg-gray-100 bg-white border-danger-red" onclick="rejectRecord(this)">Reject</button>
                                    </td>
                                </tr>
                                <tr data-status="pending">
                                    <td>22-2222</td>
                                    <td>Dimatulac, Coleen</td>
                                    <td>Logistics</td>
                                    <td class="text-center">10 hours</td>
                                    <td class="text-center">03-23-25</td>
                                    <td class="flex justify-center gap-2">
                                        <button class="btn btn-sm text-success-green hover:bg-gray-100 bg-white border-success-green" onclick="verifyRecord(this)">Verify</button>
                                        <button class="btn btn-sm text-danger-red hover:bg-gray-100 bg-white border-danger-red" onclick="rejectRecord(this)">Reject</button>
                                    </td>
                                </tr>
                                <tr data-status="archived" class="hidden">
                                    <td>23-3378</td>
                                    <td>San Andres, Rafael</td>
                                    <td>Library</td>
                                    <td class="text-center">10 hours</td>
                                    <td class="text-center">03-23-25</td>
                                    <td class="text-center text-gray-500">Archived</td>
                                </tr>
                                <tr data-status="archived" class="hidden">
                                    <td>23-3389</td>
                                    <td>Baile, Psalmuelle</td>
                                    <td>Logistics</td>
                                    <td class="text-center">10 hours</td>
                                    <td class="text-center">03-23-25</td>
                                    <td class="text-center text-gray-500">Archived</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 

        </main>
    </div>

    <dialog id="action_confirmation_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg" id="modal-title">Confirm Action</h3>
            <p class="py-4" id="modal-message">Are you sure you want to proceed with this action?</p>
            <div class="modal-action">
                <form method="dialog" class="flex gap-2">
                    <button class="btn" onclick="document.getElementById('action_confirmation_modal').close()">Cancel</button>
                    <button id="confirm-action-btn" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </dialog>


    <form id="admin-logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" class="hidden"><?php echo csrf_field(); ?></form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showPage('submission');
            const defaultTab = document.getElementById('pending-tab');
            if (defaultTab) {
                filterSubmissions('pending', defaultTab);
            }
        });
        
        function showPage(pageId) {
            document.querySelectorAll('aside a').forEach(a => {
                a.classList.remove('bg-primary-purple', 'active-nav', 'rounded-lg');
            });
            document.querySelectorAll('.page-content').forEach(p => { p.classList.add('hidden'); });
            const newPage = document.getElementById(pageId + '-page');
            if (newPage) { newPage.classList.remove('hidden'); }
            const navLink = document.getElementById('nav-' + pageId);
            if (navLink) { navLink.classList.add('bg-primary-purple', 'active-nav', 'rounded-lg'); }
        }

        function filterSubmissions(status, clickedTab) {
            const tableBody = document.getElementById('submission-table-body');
            const rows = tableBody.querySelectorAll('tr');
            const tabs = document.querySelectorAll('.custom-tab');
            tabs.forEach(tab => { tab.classList.remove('custom-tab-active'); });
            if (clickedTab) { clickedTab.classList.add('custom-tab-active'); }
            rows.forEach(row => {
                if (row.getAttribute('data-status') === status) { row.classList.remove('hidden'); }
                else { row.classList.add('hidden'); }
            });
        }

        let currentRow;
        let currentAction;
        const confirmationModal = document.getElementById('action_confirmation_modal');
        const confirmBtn = document.getElementById('confirm-action-btn');
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');

        function openConfirmationModal(row, action) {
            currentRow = row;
            currentAction = action;
            modalTitle.textContent = `Confirm ${action}`;
            modalMessage.textContent = `Are you sure you want to ${action.toLowerCase()} this record? This action cannot be undone.`;
            confirmBtn.className = 'btn';
            if (action === 'Verify') {
                confirmBtn.classList.add('btn-success', 'bg-success-green', 'hover:bg-success-green-hover', 'text-white');
            } else if (action === 'Reject') {
                confirmBtn.classList.add('btn-error', 'bg-danger-red', 'hover:bg-danger-red-hover', 'text-white');
            }
            confirmationModal.showModal();
        }

        function verifyRecord(buttonElement) {
            const row = buttonElement.closest('tr');
            openConfirmationModal(row, 'Verify');
        }

        function rejectRecord(buttonElement) {
            const row = buttonElement.closest('tr');
            openConfirmationModal(row, 'Reject');
        }

        confirmBtn.onclick = function() {
            if (currentRow && currentAction) {
                if (currentAction === 'Verify' || currentAction === 'Reject') {
                    alert(`Record successfully marked as ${currentAction.toUpperCase()}.`);
                    currentRow.setAttribute('data-status', 'archived');
                    const actionCell = currentRow.querySelector('td:last-child');
                    actionCell.className = 'text-center text-gray-500';
                    actionCell.innerHTML = currentAction === 'Verify' ? 'Verified' : 'Rejected';
                    const activeTabElement = document.querySelector('.custom-tab-active');
                    const activeStatus = activeTabElement ? activeTabElement.innerText.toLowerCase() : 'pending';
                    filterSubmissions(activeStatus, activeTabElement);
                }
            }
            confirmationModal.close();
            currentRow = null;
            currentAction = null;
        };
    </script>
</body>
</html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/dashboards/admin.blade.php ENDPATH**/ ?>