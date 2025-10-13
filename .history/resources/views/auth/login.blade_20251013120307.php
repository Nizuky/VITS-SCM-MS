
@component('components.layouts.auth.simple')
    <div class="container max-w-md mx-auto p-6">
        <h2 class="mb-4">Login</h2>

        @php $role = request('role'); @endphp
        @if ($role)
            <p class="mb-2">Logging in as: <strong>{{ $role }}</strong></p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input name="email" type="email" required class="w-full p-2 border rounded" />
            </div>
            <div class="mb-3">
                <label class="block mb-1">Student ID (optional)</label>
                <input name="student_id" type="text" placeholder="23-3402" class="w-full p-2 border rounded" />
            </div>
            <div class="mb-3">
                <label class="block mb-1">Password</label>
                <input name="password" type="password" required class="w-full p-2 border rounded" />
            </div>
            @if ($role)
                <input type="hidden" name="role" value="{{ $role }}">
            @endif
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Login</button>
        </form>
    </div>
    <x-return-to-welcome />
@endcomponent
