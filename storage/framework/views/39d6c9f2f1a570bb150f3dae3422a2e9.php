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
        <!--[if BLOCK]><![endif]--><?php if($errors->any()): ?>
            <div class="p-3 rounded text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($error); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('PLV Email address')); ?></label>
            <input
                wire:model="email"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="name@plv.edu.ph"
                class="w-full"
            />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-white"><?php echo e(__('Password')); ?></label>
                <!--[if BLOCK]><![endif]--><?php if(Route::has('password.request')): ?>
                    <a href="<?php echo e(route('password.request')); ?>" wire:navigate class="text-sm text-white hover:underline">
                        <?php echo e(__('Forgot your password?')); ?>

                    </a>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="<?php echo e(__('Password')); ?>"
                class="w-full"
            />
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

    <!--[if BLOCK]><![endif]--><?php if(Route::has('register')): ?>
        <div class="text-sm text-center text-white mt-6">
            <span><?php echo e(__('Don\'t have an account?')); ?></span>
            <a href="<?php echo e(route('register')); ?>" wire:navigate class="font-semibold hover:underline ml-1"><?php echo e(__('Sign up')); ?></a>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
</div><?php /**PATH C:\Users\janar\Herd\scms\resources\views\livewire/auth/login.blade.php ENDPATH**/ ?>