<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <style>
    /* Force white text for forgot-password page */
    h1, h2, h3, h4, h5, h6,
    p, label, span, a {
        color: #ffffff !important;
    }
    
    /* Make Flux input typeable */
    flux\:input,
    [data-flux-input],
    [data-flux-input] *,
    flux\:input * {
        pointer-events: auto !important;
    }
    
    [data-flux-input] input,
    flux\:input input {
        pointer-events: auto !important;
        user-select: text !important;
    }
    </style>
    
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white"><?php echo e(__('Forgot password')); ?></h1>
        <p class="text-sm text-white/80"><?php echo e(__('Enter your email to receive a password reset link')); ?></p>
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

    <form method="POST" wire:submit="sendPasswordResetLink" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('PLV Email Address')); ?></label>
            <input
                wire:model="email"
                type="email"
                required
                autofocus
                placeholder="name@plv.edu.ph"
                pattern="[^@\s]+@plv\.edu\.ph"
                title="Please use your PLV institutional email (@plv.edu.ph)"
                class="w-full"
            />
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-2 text-sm text-white" style="background: rgba(239, 68, 68, 0.2); padding: 0.5rem; border-radius: 0.5rem; border-left: 4px solid #ef4444;">
                    <?php echo e($message); ?>

                </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <button type="submit" class="w-full scms-primary-btn" data-test="email-password-reset-link-button">
            <?php echo e(__('Email password reset link')); ?>

        </button>
    </form>

    <div class="text-sm text-center text-white mt-6">
        <span><?php echo e(__('Or, return to')); ?></span>
        <a href="<?php echo e(route('login')); ?>" wire:navigate class="font-semibold hover:underline ml-1"><?php echo e(__('log in')); ?></a>
    </div>
</div><?php /**PATH C:\Users\janar\Herd\scms\resources\views\livewire/auth/forgot-password.blade.php ENDPATH**/ ?>