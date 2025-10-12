@component('components.layouts.auth.simple')
    <div class="container max-w-md mx-auto p-6">
        <h2 class="mb-4">Super Admin Password Reset</h2>

        @if (session('status'))
            <div class="mb-3 text-green-600">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('superadmin.password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input name="email" type="email" required class="w-full p-2 border rounded" />
            </div>
                <x-return-to-welcome />

                <button class="px-4 py-2 bg-blue-600 text-white rounded">Send reset link</button>
        </form>
    </div>
@endcomponent
