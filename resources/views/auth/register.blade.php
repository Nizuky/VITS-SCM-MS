@vite('resources/css/app.css')
@component('components.layouts.auth.simple')
    <div class="container max-w-md mx-auto p-6">
        <h2 class="mb-4">Register</h2>

        @php $role = request('role', 'student'); @endphp

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label class="block mb-1">Full name</label>
                <input name="name" type="text" required class="w-full p-2 border rounded" />
            </div>
            <div class="mb-3">
                <label class="block mb-1">Student ID (format: 00-0000)</label>
                <input name="student_id" type="text" value="{{ old('student_id') }}" placeholder="23-3402" required class="w-full p-2 border rounded" />
                <p class="text-xs text-gray-500 mt-1">Enter your student ID using 2 digits, a dash, then 4 digits (example: 23-3402).</p>
            </div>
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input name="email" type="email" required class="w-full p-2 border rounded" />
            </div>
            <div class="mb-3">
                <label class="block mb-1">Password</label>
                <input name="password" type="password" required class="w-full p-2 border rounded" />
            </div>

            {{-- Force role to student by default (unless another role was intentionally provided) --}}
            <input type="hidden" name="role" value="{{ $role }}">
            <button class="px-4 py-2 bg-green-600 text-white rounded">Sign up</button>
        </form>
    </div>
            <x-return-to-welcome />
@endcomponent
