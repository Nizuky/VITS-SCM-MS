@component('components.layouts.app.simple')
    <div class="p-6">
        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
        <p class="mt-2">Welcome, {{ auth('admin')->user()->name ?? 'Admin' }}.</p>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="mt-4 px-4 py-2 bg-red-600 text-white rounded">Logout</button>
        </form>
    </div>
@endcomponent
