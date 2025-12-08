<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8 scms-login">
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white"><?php echo e(__('Student Login')); ?></h1>
        <p class="text-sm text-white/80"><?php echo e(__('Enter your plv email and password below to log in')); ?></p>
    </div>

    <!-- Session Status -->
    <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'text-center','status' => ''.e(session('status')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-center','status' => ''.e(session('status')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

    <form method="POST" wire:submit="login" class="space-y-4">
        <!-- Error Messages -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="p-3 rounded text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($error); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('PLV Email address')); ?></label>
            <div class="relative">
                <input
                    wire:model="email"
                    type="text"
                    required
                    autofocus
                    autocomplete="off"
                    placeholder="PLV Email address"
                    class="w-full pr-32"
                    id="login-email-input"
                    oninput="formatLoginEmail(this)"
                    onpaste="handleEmailPaste(event)"
                />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 pointer-events-none select-none">@plv.edu.ph</span>
            </div>
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-white"><?php echo e(__('Password')); ?></label>
            </div>
            <div class="relative">
                <input
                    wire:model="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="<?php echo e(__('Password')); ?>"
                    class="w-full pr-10"
                    id="login-password-input"
                />
                <button
                    type="button"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-white/70 hover:text-white focus:outline-none toggle-password z-10 cursor-pointer"
                    data-input="login-password-input"
                    style="pointer-events: auto;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <div class="flex justify-end mt-1 mb-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                <a href="<?php echo e(route('password.request')); ?>" wire:navigate class="text-sm hover:underline">
                    <?php echo e(__('Forgot your password?')); ?>

                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="scms-remember select-none">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer text-white">
                <input id="remember" type="checkbox" wire:model="remember" class="h-4 w-4 rounded" />
                <span class="text-sm"><?php echo e(__('Remember me')); ?></span>
            </label>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full scms-primary-btn" data-test="login-button">
                <?php echo e(__('Log in')); ?>

            </button>
        </div>
    </form>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
        <div class="text-sm text-center text-white mt-6">
            <span><?php echo e(__('Don\'t have an account?')); ?></span>
            <a href="<?php echo e(route('register')); ?>" wire:navigate class="font-semibold hover:underline ml-1 hover:text-[#8c4cf2] "><?php echo e(__('Sign up')); ?></a>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <script>
    function formatLoginEmail(input) {
        let value = input.value;
        
        // If value contains @, cut everything from @ onwards
        if (value.includes('@')) {
            value = value.split('@')[0];
        }
        
        // Remove all characters except letters (no numbers or special characters)
        value = value.replace(/[^a-zA-Z]/g, '');
        
        // Update display value
        input.value = value;
        
        // Update Livewire with full email
        if (value) {
            window.Livewire.find(input.closest('[wire\\:id]').getAttribute('wire:id')).set('email', value + '@plv.edu.ph');
        }
    }
    
    function handleEmailPaste(event) {
        event.preventDefault();
        
        // Get pasted text
        const pastedText = (event.clipboardData || window.clipboardData).getData('text');
        
        // Extract username before @
        let username = pastedText;
        if (pastedText.includes('@')) {
            username = pastedText.split('@')[0];
        }
        
        // Remove all characters except letters (no numbers or special characters)
        username = username.replace(/[^a-zA-Z]/g, '');
        
        // Set the cleaned value
        const input = event.target;
        input.value = username;
        
        // Trigger input event for any listeners
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }
    
    // Handle autofill and form submit
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.getElementById('login-email-input');
        const form = document.querySelector('form[wire\\:submit="login"]');
        
        // Check for autofill after a short delay
        if (emailInput) {
            setTimeout(function() {
                if (emailInput.value && emailInput.value.includes('@')) {
                    const username = emailInput.value.split('@')[0];
                    emailInput.value = username;
                    // Update Livewire with full email
                    const component = emailInput.closest('[wire\\:id]');
                    if (component && window.Livewire) {
                        window.Livewire.find(component.getAttribute('wire:id')).set('email', username + '@plv.edu.ph');
                    }
                }
            }, 100);
            
            // Also check on change event (for autofill)
            emailInput.addEventListener('change', function() {
                if (this.value && this.value.includes('@')) {
                    const username = this.value.split('@')[0];
                    this.value = username;
                    // Update Livewire with full email
                    const component = this.closest('[wire\\:id]');
                    if (component && window.Livewire) {
                        window.Livewire.find(component.getAttribute('wire:id')).set('email', username + '@plv.edu.ph');
                    }
                }
            });
            
            // On blur, ensure Livewire has full email
            emailInput.addEventListener('blur', function() {
                if (this.value && !this.value.includes('@')) {
                    const component = this.closest('[wire\\:id]');
                    if (component && window.Livewire) {
                        window.Livewire.find(component.getAttribute('wire:id')).set('email', this.value + '@plv.edu.ph');
                    }
                }
            });
        }
    });
    
    // Password toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-password')) {
            const button = e.target.closest('.toggle-password');
            const inputId = button.getAttribute('data-input');
            const input = document.getElementById(inputId);
            const svg = button.querySelector('svg');
            
            if (input && svg) {
                if (input.type === 'password') {
                    input.type = 'text';
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
                } else {
                    input.type = 'password';
                    svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                }
            }
        }
    });
    </script>
    
    <style>
    /* Scoped overrides for login page */
    .scms-login .scms-primary-btn {
        background-color: #6D28D9 !important; /* primary purple */
        color: #ffffff !important;
        border-color: transparent !important;
    }
    .scms-login .scms-primary-btn:hover {
        background-color: #5B21B6 !important; /* darker hover */
    }
    .scms-login .scms-primary-btn:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(109, 40, 217, 0.35) !important;
    }

    /* Force the checkbox check/track to use our brand color */
    .scms-login input[type="checkbox"] {
        accent-color: #6D28D9; /* modern browsers */
    }

    /* Fallback for environments where accent-color isn't applied */
    @supports not (accent-color: #000) {
        .scms-login input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border: 2px solid #9ca3af; /* gray-400 */
            background-color: #ffffff;
            border-radius: 0.25rem !important;
            display: inline-grid; place-content: center;
            cursor: pointer;
            transition: background-color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        .scms-login input[type="checkbox"]:focus { outline: none; box-shadow: 0 0 0 2px rgba(109,40,217,0.35); }
        .scms-login input[type="checkbox"]:checked { background-color: #6D28D9 !important; border-color: #6D28D9 !important; }
        .scms-login input[type="checkbox"]:checked::after {
            content: "";
            width: 0.25rem; height: 0.5rem;
            border: solid #ffffff; border-width: 0 2px 2px 0; transform: rotate(45deg);
        }
    }

    /* Dark scheme tweaks for the fallback */
    @media (prefers-color-scheme: dark) {
        @supports not (accent-color: #000) {
            .scms-login input[type="checkbox"] { background-color: #111827; border-color: #374151; }
            .scms-login input[type="checkbox"]:focus { box-shadow: 0 0 0 2px rgba(109,40,217,0.45); }
        }
    }

    /* Optional: ensure label next to checkbox inherits readable color */
    .scms-login label { color: inherit; }
    
    /* Force white text for login page */
    .scms-login h1, 
    .scms-login h2, 
    .scms-login h3, 
    .scms-login h4, 
    .scms-login h5, 
    .scms-login h6,
    .scms-login p,
    .scms-login label,
    .scms-login span,
    .scms-login a {
        color: #ffffff !important;
    }
    </style>
    <?php if (isset($component)) { $__componentOriginalc9f9db5606acc4a875fc6dea8ae4bcf4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9f9db5606acc4a875fc6dea8ae4bcf4 = $attributes; } ?>
<?php $component = App\View\Components\ReturnToWelcome::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('return-to-welcome'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\ReturnToWelcome::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9f9db5606acc4a875fc6dea8ae4bcf4)): ?>
<?php $attributes = $__attributesOriginalc9f9db5606acc4a875fc6dea8ae4bcf4; ?>
<?php unset($__attributesOriginalc9f9db5606acc4a875fc6dea8ae4bcf4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9f9db5606acc4a875fc6dea8ae4bcf4)): ?>
<?php $component = $__componentOriginalc9f9db5606acc4a875fc6dea8ae4bcf4; ?>
<?php unset($__componentOriginalc9f9db5606acc4a875fc6dea8ae4bcf4); ?>
<?php endif; ?>
</div><?php /**PATH C:\Users\janar\Herd\scms\resources\views\livewire\auth\login.blade.php ENDPATH**/ ?>