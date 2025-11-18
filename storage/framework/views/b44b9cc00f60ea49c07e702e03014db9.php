<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

?>

<div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
    <style>
    /* Force white text for register page */
    h1, h2, h3, h4, h5, h6,
    p, label, span, a {
        color: #ffffff !important;
    }
    </style>
    
    <div class="text-center space-y-2 mb-6">
        <h1 class="text-2xl font-bold text-white"><?php echo e(__('Student Sign up')); ?></h1>
        <p class="text-sm text-white/80"><?php echo e(__('Enter your details below to create your account')); ?></p>
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

    <form method="POST" wire:submit.prevent="register" class="space-y-4">
        <!-- Name -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Name')); ?></label>
            <input
                wire:model="name"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="<?php echo e(__('Full name')); ?>"
                class="w-full"
                id="name-input"
                oninput="this.value = this.value.replace(/\b\w/g, function(l){ return l.toUpperCase() })"
            />
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
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
            <p class="mt-1 text-xs text-white/70">
                Format: Surname, First Name Middle Initial
            </p>
        </div>

        <!-- Student ID -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Student ID')); ?></label>
            <input
                wire:model="student_id"
                type="text"
                required
                placeholder="00-0000"
                pattern="\d{2}-\d{4}"
                title="Format: 00-0000"
                class="w-full"
            />
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['student_id'];
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

        <!-- Email Address -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('PLV Email address')); ?></label>
            <input
                wire:model="email"
                type="email"
                required
                autocomplete="email"
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

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Password')); ?></label>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="new-password"
                placeholder="<?php echo e(__('Password')); ?>"
                class="w-full"
            />
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
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
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password_confirmation'];
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

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full scms-primary-btn" data-test="register-user-button">
                <?php echo e(__('Sign up')); ?>

            </button>
        </div>
    </form>

    <div class="text-sm text-center text-white mt-6">
        <span><?php echo e(__('Already have an account?')); ?></span>
        <a href="<?php echo e(route('login')); ?>" wire:navigate class="font-semibold hover:underline ml-1"><?php echo e(__('Log in')); ?></a>
    </div>
</div><?php /**PATH C:\Users\janar\Herd\scms\resources\views\livewire/auth/register.blade.php ENDPATH**/ ?>