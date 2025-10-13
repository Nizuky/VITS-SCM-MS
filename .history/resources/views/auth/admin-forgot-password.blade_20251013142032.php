@component('components.layouts.auth.simple')
    <div class="container max-w-md mx-auto p-6">
        @php
            $defaultAdmin = App\Models\AdminUser::first();
            $defaultEmail = $defaultAdmin ? $defaultAdmin->email : null;
        @endphp
         <h2 class="mb-4" style="font-weight: bold; text-align: center; font-size: 20px;">Admin Password Reset</h2>

        <p>The password reset link will be sent to the shared admin email on file. Enter the shared email to request a reset.</p>

        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input name="email" type="email" required class="w-full p-2 border rounded" value="{{ old('email', $defaultEmail) }}" />
            </div>

            <button class="px-4 py-2 bg-yellow-600 text-white rounded">Send Reset Link</button>
        </form>
        <x-return-to-welcome />
    </div>
@endcomponent
