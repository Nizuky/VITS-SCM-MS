<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <style>
    /* Force white text for reset-password page */
    h1, h2, h3, h4, h5, h6,
    p, label, span, a {
        color: #ffffff !important;
    }
    </style>
    
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white"><?php echo e(__('Reset password')); ?></h1>
        <p class="text-sm text-white/80"><?php echo e(__('Please enter your new password below')); ?></p>
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

    <form method="POST" wire:submit="resetPassword" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Email')); ?></label>
            <input
                wire:model="email"
                type="email"
                required
                autocomplete="email"
                readonly
                class="w-full"
            />
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('New Password')); ?></label>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="<?php echo e(__('New Password')); ?>"
                class="w-full"
            />
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Confirm password')); ?></label>
            <input
                wire:model="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="<?php echo e(__('Confirm password')); ?>"
                class="w-full"
            />
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full scms-primary-btn" data-test="reset-password-button">
                <?php echo e(__('Reset password')); ?>

            </button>
        </div>
    </form>
    
    <script>
    // Optional: if the server indicated auto=1 and the inputs were prefilled, auto-click the submit button.
    document.addEventListener('DOMContentLoaded', function(){
        try {
            const params = new URLSearchParams(window.location.search);
            if (params.get('auto') === '1') {
                // Small delay to allow Livewire rendering
                setTimeout(() => {
                    const btn = document.querySelector('[data-test="reset-password-button"]');
                    if (btn) btn.click();
                }, 150);
            }
        } catch (_) {}
    });
    </script>
</div><?php /**PATH C:\Users\janar\Herd\scms\resources\views\livewire\auth\reset-password.blade.php ENDPATH**/ ?>