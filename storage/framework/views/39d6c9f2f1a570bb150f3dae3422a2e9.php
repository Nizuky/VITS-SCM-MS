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

<div class="flex flex-col gap-6 scms-login">
    <?php if (isset($component)) { $__componentOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-header','data' => ['title' => __('Student Login'),'description' => __('Enter your plv email and password below to log in')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Student Login')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Enter your plv email and password below to log in'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd)): ?>
<?php $attributes = $__attributesOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd; ?>
<?php unset($__attributesOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd)): ?>
<?php $component = $__componentOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd; ?>
<?php unset($__componentOriginale5d2f2831f58fdbe96ad6d7cbd41a7dd); ?>
<?php endif; ?>

    <!-- Session Status -->
    <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'text-center','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'text-center','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
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

    <form method="POST" wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="name@plv.edu.ph"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
                viewable
            />

            <!--[if BLOCK]><![endif]--><?php if(Route::has('password.request')): ?>
                <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                    <?php echo e(__('Forgot your password?')); ?>

                </flux:link>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Remember Me (native checkbox for consistent styling) -->
        <div class="scms-remember select-none">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember" type="checkbox" wire:model="remember" class="h-4 w-4 rounded-4" />
                <span><?php echo e(__('Remember me')); ?></span>
            </label>
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full scms-primary-btn" data-test="login-button">
                <?php echo e(__('Log in')); ?>

            </flux:button>
        </div>
    </form>

    <!--[if BLOCK]><![endif]--><?php if(Route::has('register')): ?>
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span><?php echo e(__('Don\'t have an account?')); ?></span>
            <flux:link :href="route('register')" wire:navigate><?php echo e(__('Sign up')); ?></flux:link>
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
    </style>
</div><?php /**PATH C:\Users\janar\Herd\scms\resources\views\livewire/auth/login.blade.php ENDPATH**/ ?>