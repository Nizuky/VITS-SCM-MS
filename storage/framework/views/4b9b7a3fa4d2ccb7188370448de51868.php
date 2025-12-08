
<?php if (isset($component)) { $__componentOriginaled17748e2d35dfac5d4111319639b524 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled17748e2d35dfac5d4111319639b524 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.auth.login-register','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.auth.login-register'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-6 sm:p-8">
        <div class="text-center space-y-2 mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white">Student Login</h1>
            <p class="text-xs sm:text-sm text-white/80">Enter your plv email and password below to log in</p>
        </div>

        <?php $role = request('role'); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role): ?>
            <p class="mb-4 text-center text-sm text-white/80">Logging in as: <strong><?php echo e($role); ?></strong></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form id="student-login-form" method="POST" action="<?php echo e(route('login')); ?>" class="space-y-3 sm:space-y-4">
            <?php echo csrf_field(); ?>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                <div class="p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;"><?php echo e(session('status')); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;"><?php echo e($errors->first()); ?></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <div>
                <label class="block text-xs sm:text-sm font-medium mb-1.5 sm:mb-2 text-white">Email address</label>
                <input 
                    name="email" 
                    type="email" 
                    required 
                    autocomplete="email"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="name@plv.edu.ph"
                    class="w-full"
                    style="color: #ffffff !important;"
                />
            </div>
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-1.5 sm:mb-2 gap-1">
                    <label class="block text-xs sm:text-sm font-medium text-white">Password</label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>" class="text-xs sm:text-sm hover:underline">Forgot your password?</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <input 
                    name="password" 
                    type="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Password"
                    class="w-full"
                    style="color: #ffffff !important;"
                />
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role): ?>
                <input type="hidden" name="role" value="<?php echo e($role); ?>">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <label class="inline-flex items-center gap-2 cursor-pointer text-white text-xs sm:text-sm">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded" />
                <span class="text-white">Remember me</span>
            </label>

            <button id="student-login-btn" type="submit" class="w-full scms-primary-btn text-sm sm:text-base py-2.5 sm:py-3" aria-busy="false">
                <span class="btn-text">Log in</span>
            </button>
        </form>
        
        <script>
        (function(){
            const form = document.getElementById('student-login-form');
            const btn = document.getElementById('student-login-btn');
            if (!form || !btn) return;
            
            let isSubmitting = false;
            
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return;
                }
                isSubmitting = true;
                btn.disabled = true;
                btn.setAttribute('aria-busy', 'true');
                const btnText = btn.querySelector('.btn-text');
                if (btnText) btnText.textContent = 'Logging in...';
            });
        })();
        </script>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('register')): ?>
            <div class="text-center mt-4 sm:mt-6">
                <span class="text-xs sm:text-sm text-white/80">Don't have an account?</span>
                <a href="<?php echo e(route('register')); ?>" class="text-xs sm:text-sm underline hover:opacity-80 ml-1">Sign up</a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled17748e2d35dfac5d4111319639b524)): ?>
<?php $attributes = $__attributesOriginaled17748e2d35dfac5d4111319639b524; ?>
<?php unset($__attributesOriginaled17748e2d35dfac5d4111319639b524); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled17748e2d35dfac5d4111319639b524)): ?>
<?php $component = $__componentOriginaled17748e2d35dfac5d4111319639b524; ?>
<?php unset($__componentOriginaled17748e2d35dfac5d4111319639b524); ?>
<?php endif; ?>

<style>
/* Force gray placeholders for student login inputs */
input[name="email"]::placeholder,
input[name="password"]::placeholder,
input[name="email"]::-webkit-input-placeholder,
input[name="password"]::-webkit-input-placeholder,
input[name="email"]::-moz-placeholder,
input[name="password"]::-moz-placeholder,
input[name="email"]:-ms-input-placeholder,
input[name="password"]:-ms-input-placeholder {
    color: #9ca3af !important;
    opacity: 1 !important;
}
</style>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\auth\login.blade.php ENDPATH**/ ?>