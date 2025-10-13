@component('components.layouts.auth.simple')
    <div class="container max-w-md mx-auto p-6">
        <h2 class="mb-4" style="font-weight: bold; text-align: center; font-size: 20px;">Reset Admin Password</h2>

        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />
            <div class="mb-3">
                <label class="block mb-1">Admin name</label>
                <input name="name" type="text" required class="w-full p-2 border rounded" value="{{ old('name') }}" />
            </div>
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input name="email" type="email" required class="w-full p-2 border rounded" value="{{ old('email', $email ?? '') }}" />
            </div>

            <div class="mb-3">
                <label class="block mb-1">New Password</label>
                <input name="password" type="password" required class="w-full p-2 border rounded" />
            </div>

            <div class="mb-3">
                <label class="block mb-1">Confirm Password</label>
                <input name="password_confirmation" type="password" required class="w-full p-2 border rounded" />
            </div>
            <button class="px-4 py-2 bg-green-600 text-white rounded">Reset Password</button>
        </form>
         <x-return-to-welcome />
    </div>
@endcomponent
