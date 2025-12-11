<?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
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
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-4 sm:p-6">
        <h2 class="mb-3 sm:mb-4 text-white text-xl sm:text-2xl font-bold text-center">Register</h2>

        <?php $role = request('role', 'student'); ?>

        <form id="register-form" method="POST" action="<?php echo e(route('register')); ?>" class="space-y-3 sm:space-y-4">
            <?php echo csrf_field(); ?>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="mb-2 sm:mb-3 p-2 sm:p-3 rounded text-xs sm:text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">
                    <ul class="list-disc list-inside">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">Full name</label>
                <input 
                    name="name" 
                    type="text" 
                    required 
                    minlength="3"
                    maxlength="255"
                    autocomplete="name"
                    value="<?php echo e(old('name')); ?>"
                    placeholder="Juan Dela Cruz"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
            </div>
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">Student ID (format: 00-0000)</label>
                <input 
                    name="student_id" 
                    type="text" 
                    value="<?php echo e(old('student_id')); ?>" 
                    placeholder="23-3402" 
                    required 
                    pattern="\d{2}-\d{4}"
                    maxlength="7"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
                <p class="text-[10px] sm:text-xs text-white/70 mt-1">Enter your student ID using 2 digits, a dash, then 4 digits (example: 23-3402).</p>
            </div>
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">PLV Email</label>
                <input 
                    name="email" 
                    type="email" 
                    required 
                    pattern="[^@\s]+@plv\.edu\.ph$"
                    autocomplete="email"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="yourname@plv.edu.ph"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
                <p class="text-[10px] sm:text-xs text-white/70 mt-1">Must be a valid PLV email address (@plv.edu.ph)</p>
            </div>
            <div>
                <label class="block mb-1 sm:mb-1.5 text-white text-xs sm:text-sm font-medium">Password</label>
                <input 
                    name="password" 
                    type="password" 
                    required 
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Minimum 8 characters"
                    class="w-full p-2 sm:p-2.5 border rounded text-sm sm:text-base" 
                />
                <p class="text-[10px] sm:text-xs text-white/70 mt-1">Must be at least 8 characters with letters and numbers</p>
            </div>

            
            <input type="hidden" name="role" value="<?php echo e($role); ?>">
            <button id="register-btn" type="submit" class="px-4 py-2.5 sm:py-3 bg-green-600 text-white rounded hover:bg-green-700 transition w-full text-sm sm:text-base font-semibold" aria-busy="false">
                <span class="btn-text">Sign up</span>
            </button>
        </form>
    </div>
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
    
    <script>
    (function(){
        const form = document.getElementById('register-form');
        const btn = document.getElementById('register-btn');
        if (!form || !btn) return;
        
        let isSubmitting = false;
        
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            
            // Validate form
            if (!form.checkValidity()) {
                return; // Let browser handle validation
            }
            
            isSubmitting = true;
            btn.disabled = true;
            btn.setAttribute('aria-busy', 'true');
            const btnText = btn.querySelector('.btn-text');
            if (btnText) btnText.textContent = 'Creating account...';
        });
    })();
    </script>
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
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\auth\register.blade.php ENDPATH**/ ?>