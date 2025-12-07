<div id="profile-page" class="page-content hidden flex-col">
      <p class="text-sm text-gray-600 mt-2 pb-6 text-center lg:text-left">View and edit your profile information, contact details, and account settings.</p>
    <div class="bg-white rounded-2xl p-6 shadow-sm flex flex-col gap-6">
        <div id="profile-view" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Full Name</p>
                    <p class="font-semibold text-lg text-text-header break-words">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Student Number</p>
                    <p class="font-semibold text-lg text-text-header">{{ auth()->user()->student_id ?? '—' }}</p>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <p class="text-gray-500 text-sm mb-1">Email Address</p>
                    <p class="font-semibold text-lg text-text-header break-all">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Account Type</p>
                    <p class="font-semibold text-lg text-text-header">Student</p>
                </div>
                <div>
                    <p class="text-gray-500 text-sm mb-1">Password</p>
                    <p class="font-semibold text-lg text-text-header">••••••••••</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button class="btn bg-primary-purple hover:bg-primary-purple-hover text-white rounded-lg" onclick="showEditMode('info')">
                    Edit Profile
                </button>
            </div>
        </div>

        <div id="profile-edit" class="space-y-6 hidden">
            <form id="profile-info-form" class="grid grid-cols-2 gap-x-12 gap-y-4">
                <label class="form-control w-full">
                    <div class="label"><span class="label-text font-semibold">Full Name</span></div>
                    <input id="edit-full-name" type="text" value="{{ auth()->user()->name }}" class="input input-bordered w-full rounded-lg" required />
                    <div class="label"><span class="label-text-alt text-gray-500">Surname, First Name Middle Initial</span></div>
                </label>
                <label class="form-control w-full">
                    <div class="label"><span class="label-text">Student Number</span></div>
                    <input type="text" value="{{ auth()->user()->student_id ?? '' }}" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                </label>
                <label class="form-control w-full">
                    <div class="label"><span class="label-text">Email Address</span></div>
                    <input type="email" value="{{ auth()->user()->email }}" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                </label>
                <label class="form-control w-full">
                    <div class="label"><span class="label-text">Account Type</span></div>
                    <input type="text" value="Student" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                </label>

                <div class="col-span-2 space-y-4 pt-4" id="password-view-section">
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Password</span></div>
                        <input type="password" value="••••••••••" class="input input-bordered w-full rounded-lg bg-gray-100" readonly />
                    </label>
                    <button type="button" class="btn btn-link px-0 text-sm text-primary-purple hover:text-primary-purple-hover" onclick="togglePasswordForm('show')">
                        Reset Password?
                    </button>
                </div>

                <div class="col-span-2 space-y-4 pt-4 hidden" id="password-edit-fields">
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Current Password</span></div>
                        <label class="input input-bordered flex items-center gap-2 rounded-lg">
                            <input id="current-password" type="password" placeholder="••••••••••" class="grow" required/>
                            <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('current-password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                            </button>
                        </label>
                    </label>
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">New Password</span></div>
                        <label class="input input-bordered flex items-center gap-2 rounded-lg">
                            <input id="new-password" type="password" placeholder="••••••••••" class="grow" required/>
                            <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('new-password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                            </button>
                        </label>
                    </label>
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Confirm Password</span></div>
                        <label class="input input-bordered flex items-center gap-2 rounded-lg">
                            <input id="confirm-password" type="password" placeholder="••••••••••" class="grow" required/>
                            <button type="button" class="btn btn-ghost btn-xs" onclick="togglePasswordVisibility('confirm-password', this)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                            </button>
                        </label>
                    </label>
                </div>
                
                <div class="col-span-2 pt-6 flex justify-end">
                    <button type="button" id="profile-save-btn" class="btn bg-success-green hover:bg-success-green-hover text-white rounded-lg">
                        Save Changes
                    </button>
                </div>
            </form>
        </div> 
    </div>
</div>
