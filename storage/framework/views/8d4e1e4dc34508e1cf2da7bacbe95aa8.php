<?php $__env->startComponent('components.layouts.auth.simple'); ?>
    <div class="container max-w-md mx-auto p-6">
        <h2 class="mb-4" style="font-weight: bold; text-align: center; font-size: 20px;">Reset Admin Password</h2>
        <form method="POST" action="<?php echo e(route('admin.password.update')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="token" value="<?php echo e($token); ?>" />
            <div class="mb-3">
                <label class="block mb-1">Admin name</label>
                <input name="name" type="text" required class="w-full p-2 border rounded" value="<?php echo e(old('name')); ?>" />
            </div>
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input name="email" type="email" required class="w-full p-2 border rounded" value="<?php echo e(old('email', $email ?? '')); ?>" />
            </div>

            <div class="mb-3">
                <label class="block mb-1">New Password</label>
                <input name="password" type="password" required class="w-full p-2 border rounded" />
            </div>

            <div class="mb-3">
                <label class="block mb-1">Confirm Password</label>
                <input name="password_confirmation" type="password" required class="w-full p-2 border rounded" />
            </div>
            <button class="px-4 py-2 bg-green-600 text-white rounded">Reset Password</button>
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
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/auth/admin-reset-password.blade.php ENDPATH**/ ?>