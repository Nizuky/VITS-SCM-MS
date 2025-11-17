<?php $__env->startComponent('components.layouts.auth.simple'); ?>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white"><?php echo e(__('Reset Admin Password')); ?></h1>
            <p class="text-sm text-white/80"><?php echo e(__('Enter your details below to reset your password')); ?></p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.password.update')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="token" value="<?php echo e($token); ?>" />
            
            <!-- Admin Name -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Admin name')); ?></label>
                <input name="name" type="text" required value="<?php echo e(old('name')); ?>" placeholder="<?php echo e(__('Admin name')); ?>" />
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Email')); ?></label>
                <input name="email" type="email" required value="<?php echo e(old('email', $email ?? '')); ?>" placeholder="admin@plv.edu.ph" />
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('New Password')); ?></label>
                <input name="password" type="password" required placeholder="<?php echo e(__('Password')); ?>" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium mb-2 text-white"><?php echo e(__('Confirm password')); ?></label>
                <input name="password_confirmation" type="password" required placeholder="<?php echo e(__('Confirm password')); ?>" />
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="w-full scms-primary-btn"><?php echo e(__('Reset Password')); ?></button>
            </div>
        </form>
        
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
    </div>
<?php echo $__env->renderComponent(); ?>

<style>
h1, h2, h3, h4, h5, h6,
p, label, span, a {
    color: #ffffff !important;
}
</style>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/auth/admin-reset-password.blade.php ENDPATH**/ ?>