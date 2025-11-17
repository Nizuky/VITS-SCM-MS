<?php if (isset($component)) { $__componentOriginalce5847ac41e2319cc94841d423efce16 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce5847ac41e2319cc94841d423efce16 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.auth.simple','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.auth.simple'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <?php
            $defaultAdmin = App\Models\AdminUser::first();
            $defaultEmail = $defaultAdmin ? $defaultAdmin->email : null;
            $knownNames = [];
            if ($defaultEmail) {
                $knownNames = App\Models\AdminUser::where('email', $defaultEmail)->pluck('name')->unique()->values()->all();
            }
        ?>

        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">Admin Password Reset</h1>
            <p class="text-sm text-white/80">The password reset link will be sent to the shared admin email on file. Enter the shared email to request a reset.</p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.password.email')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            
            <div>
                <label class="block text-sm font-medium mb-2 text-white">Email</label>
                <input
                    name="email"
                    type="email"
                    required
                    value="<?php echo e(old('email', $defaultEmail)); ?>"
                    class="w-full"
                />
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-white">Admin name</label>
                <input
                    name="name"
                    list="admin-names"
                    type="text"
                    required
                    value="<?php echo e(old('name')); ?>"
                    class="w-full"
                />
                <?php if(count($knownNames) > 0): ?>
                    <datalist id="admin-names">
                        <?php $__currentLoopData = $knownNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($n); ?>"></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </datalist>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full scms-primary-btn">
                Send Reset Link
            </button>
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
<?php endif; ?>
<?php if (isset($__attributesOriginalce5847ac41e2319cc94841d423efce16)): ?>
<?php $attributes = $__attributesOriginalce5847ac41e2319cc94841d423efce16; ?>
<?php unset($__attributesOriginalce5847ac41e2319cc94841d423efce16); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce5847ac41e2319cc94841d423efce16)): ?>
<?php $component = $__componentOriginalce5847ac41e2319cc94841d423efce16; ?>
<?php unset($__componentOriginalce5847ac41e2319cc94841d423efce16); ?>
<?php endif; ?>
<style>
/* Force white text for admin-forgot-password page */
h1, h2, h3, h4, h5, h6,
p, label, span, a {
    color: #ffffff !important;
}
</style>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/auth/admin-forgot-password.blade.php ENDPATH**/ ?>