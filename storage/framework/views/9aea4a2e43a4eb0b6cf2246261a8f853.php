<?php $__env->startComponent('components.layouts.auth.simple'); ?>
    <div class="container max-w-md mx-auto p-6">
        <?php
            $defaultAdmin = App\Models\AdminUser::first();
            $defaultEmail = $defaultAdmin ? $defaultAdmin->email : null;
            $knownNames = [];
            if ($defaultEmail) {
                $knownNames = App\Models\AdminUser::where('email', $defaultEmail)->pluck('name')->unique()->values()->all();
            }
        ?>
         <h2 class="mb-4" style="font-weight: bold; text-align: center; font-size: 20px;">Admin Password Reset</h2>

        <p>The password reset link will be sent to the shared admin email on file. Enter the shared email to request a reset.</p>

        <form method="POST" action="<?php echo e(route('admin.password.email')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="block mb-1">Email</label>
                <input name="email" type="email" required class="w-full p-2 border rounded" value="<?php echo e(old('email', $defaultEmail)); ?>" />
            </div>

            <div class="mb-3">
                <label class="block mb-1">Admin name</label>
                <input name="name" list="admin-names" type="text" required class="w-full p-2 border rounded" value="<?php echo e(old('name')); ?>" />
                <?php if(count($knownNames) > 0): ?>
                    <datalist id="admin-names">
                        <?php $__currentLoopData = $knownNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($n); ?>"></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </datalist>
                <?php endif; ?>
            </div>

            <button class="px-4 py-2 bg-yellow-600 text-white rounded">Send Reset Link</button>
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
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/auth/admin-forgot-password.blade.php ENDPATH**/ ?>