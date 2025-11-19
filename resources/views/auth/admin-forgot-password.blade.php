<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        @php
            $defaultAdmin = App\Models\AdminUser::first();
            $defaultEmail = $defaultAdmin ? $defaultAdmin->email : null;
            $knownNames = [];
            if ($defaultEmail) {
                $knownNames = App\Models\AdminUser::where('email', $defaultEmail)->pluck('name')->unique()->values()->all();
            }
        @endphp

        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">Admin Password Reset</h1>
            <p class="text-sm text-white/80">The password reset link will be sent to the shared admin email on file. Enter the shared email to request a reset.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 rounded text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 rounded text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium mb-2 text-white">Email</label>
                <input
                    name="email"
                    type="email"
                    required
                    value="{{ old('email', $defaultEmail) }}"
                    class="w-full"
                />
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-white">Admin name</label>
                <input
                    name="name"
                    list="admin-names"
                    type="text"
                    required
                    value="{{ old('name') }}"
                    class="w-full"
                />
                @if(count($knownNames) > 0)
                    <datalist id="admin-names">
                        @foreach($knownNames as $n)
                            <option value="{{ $n }}"></option>
                        @endforeach
                    </datalist>
                @endif
            </div>

            <button type="submit" class="w-full scms-primary-btn">
                Send Reset Link
            </button>
        </form>
        
        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>
