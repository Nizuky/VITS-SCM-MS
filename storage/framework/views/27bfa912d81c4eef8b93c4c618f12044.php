<!-- Settings Page -->
<div id="settings-page" class="page-content hidden">
    <div class="flex items-center justify-between p-2">
        <h4 class="text-4xl font-bold text-primary-purple">Settings</h4>
    </div>
    <p class="text-sm text-gray-600 mt-2 pl-4 pb-6 text-center lg:text-left">&#9432 Configure application preferences and update account settings.</p>

    
    <div class="flex-1 bg-white rounded-2xl p-6 shadow-sm flex flex-col gap-6">
        <!-- Change Name Section -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-xl font-bold text-text-header mb-4">Change Name</h2>
            <form id="name-change-form" class="space-y-4 max-w-md">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Admin Name</span>
                        <span class="label-text-alt text-gray-500">Must start with "admin"</span>
                    </div>
                    <input id="admin-name-input" type="text" value="<?php echo e(auth()->guard('admin')->user()->name); ?>" placeholder="adminYourName" class="input input-bordered w-full rounded-lg" required pattern="^admin.+">
                </label>
                
                <div class="pt-4 flex justify-end">
                    <button type="button" id="save-name-button" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                        Update Name
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Section -->
        <div>
            <h2 class="text-xl font-bold text-text-header mb-4">Change Password</h2>
            <p class="text-sm text-text-muted mb-4">A verification email will be sent to <strong><?php echo e(auth()->guard('admin')->user()->email); ?></strong> to confirm your password change.</p>
            
            <form id="password-change-form" class="space-y-4 max-w-md">
            <!-- Current Password -->
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">Current Password</span>
                </div>
                <label class="input input-bordered flex items-center gap-2 rounded-lg">
                    <input id="current-password" type="password" placeholder="" class="grow" required>
                    <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('current-password')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                        </svg>
                    </button>
                </label>
            </label>

            <!-- New Password -->
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">New Password</span>
                </div>
                <label class="input input-bordered flex items-center gap-2 rounded-lg">
                    <input id="new-password" type="password" placeholder="" class="grow" required>
                    <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('new-password')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                        </svg>
                    </button>
                </label>
            </label>

            <!-- Confirm Password -->
            <label class="form-control w-full">
                <div class="label">
                    <span class="label-text font-semibold">Confirm Password</span>
                </div>
                <label class="input input-bordered flex items-center gap-2 rounded-lg">
                    <input id="confirm-password" type="password" placeholder="" class="grow" required>
                    <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('confirm-password')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                        </svg>
                    </button>
                </label>
            </label>

            <div class="pt-4 flex justify-end">
                <button type="button" id="save-password-button" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                    Request Password Change
                </button>
            </div>
        </form>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\partials\admin\settings-page.blade.php ENDPATH**/ ?>