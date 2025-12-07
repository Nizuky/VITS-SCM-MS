<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-5 sm:p-8 text-center">
        <h1 class="text-xl sm:text-2xl font-bold text-white mb-2 sm:mb-4">Are you a student?</h1>
        <p class="text-xs sm:text-sm text-white/80 mb-4 sm:mb-6">Please choose your role to continue.</p>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
            <a href="{{ route('student.exists') }}" class="w-full sm:w-auto px-5 py-2.5 sm:py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg text-sm sm:text-base font-semibold hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                Yes, I'm a student
            </a>
            <a href="{{ route('nonstudent.select') }}" class="w-full sm:w-auto px-5 py-2.5 sm:py-3 bg-white/10 border border-white/30 text-white rounded-lg text-sm sm:text-base font-semibold hover:bg-white/20 transition-all duration-300">
                No, I'm not a student
            </a>
        </div>
        
        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>
