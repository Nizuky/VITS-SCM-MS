@vite('resources/css/app.css')
<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-4 sm:p-6">
        <h2 class="mb-3 sm:mb-4 text-white text-xl sm:text-2xl font-bold text-center">Register</h2>

        @php $role = request('role', 'student'); @endphp

        <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-3 sm:space-y-4">
            @csrf
            
            @if($errors->any())
                <div class="mb-2 sm:mb-3 p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">Full name</label>
                <input 
                    name="name" 
                    type="text" 
                    required 
                    minlength="3"
                    maxlength="255"
                    autocomplete="name"
                    value="{{ old('name') }}"
                    placeholder="Juan Dela Cruz"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
            </div>
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">Student ID (format: 00-0000)</label>
                <input 
                    name="student_id" 
                    type="text" 
                    value="{{ old('student_id') }}" 
                    placeholder="23-3402" 
                    required 
                    pattern="\d{2}-\d{4}"
                    maxlength="7"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
                <p class="text-[10px] sm:text-xs text-white/70 mt-1">Enter your student ID using 2 digits, a dash, then 4 digits (example: 23-3402).</p>
            </div>
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">PLV Email</label>
                <input 
                    name="email" 
                    type="email" 
                    required 
                    pattern="[^@\s]+@plv\.edu\.ph$"
                    autocomplete="email"
                    value="{{ old('email') }}"
                    placeholder="yourname@plv.edu.ph"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
                <p class="text-[10px] sm:text-xs text-white/70 mt-1">Must be a valid PLV email address (@plv.edu.ph)</p>
            </div>
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">Password</label>
                <input 
                    name="password" 
                    type="password" 
                    required 
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Minimum 8 characters"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
                <p class="text-[10px] sm:text-xs text-white/70 mt-1">Must be at least 8 characters with letters and numbers</p>
            </div>

            {{-- Force role to student by default (unless another role was intentionally provided) --}}
            <input type="hidden" name="role" value="{{ $role }}">
            <button id="register-btn" type="submit" class="px-4 py-2.5 sm:py-3 bg-green-600 text-white rounded hover:bg-green-700 transition w-full text-sm sm:text-base font-semibold" aria-busy="false">
                <span class="btn-text">Sign up</span>
            </button>
        </form>
    </div>
    <x-return-to-welcome />
    
    <script>
    (function(){
        const form = document.getElementById('register-form');
        const btn = document.getElementById('register-btn');
        if (!form || !btn) return;
        
        let isSubmitting = false;
        
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            
            // Validate form
            if (!form.checkValidity()) {
                return; // Let browser handle validation
            }
            
            isSubmitting = true;
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            const btnText = btn.querySelector('.btn-text');
            if (btnText) btnText.textContent = 'Creating account...';
        });
    })();
    </script>
</x-layouts.auth.login-register>
