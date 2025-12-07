<x-layouts.auth.login-register>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-5 sm:p-8 text-center">
        <h1 class="text-xl sm:text-2xl font-bold text-white mb-4 sm:mb-6">Do you have an existing account?</h1>
        
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-2.5 sm:py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg text-sm sm:text-base font-semibold hover:from-purple-700 hover:to-indigo-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                Yes — Login
            </a>
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-2.5 sm:py-3 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-lg text-sm sm:text-base font-semibold hover:from-pink-600 hover:to-rose-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                No — Sign up
            </a>
        </div>
        
        <x-return-to-welcome />
    </div>
</x-layouts.auth.login-register>
