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
    <div class="w-full max-w-md mx-auto bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-8">
        <div class="text-center space-y-2 mb-6">
            <h1 class="text-2xl font-bold text-white">Super Admin Login</h1>
        </div>

        <form id="superadmin-login-form" method="POST" action="<?php echo e(route('superadmin.login.submit')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php if(session('success')): ?>
                <div class="p-3 rounded text-sm text-white" style="background:rgba(16, 185, 129, 0.2); border-left:4px solid #10b981;"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="p-3 rounded text-sm text-white" style="background:rgba(239, 68, 68, 0.2); border-left:4px solid #ef4444;">Invalid information</div>
            <?php endif; ?>

            <div>
                <label for="name" class="block text-sm font-medium mb-2 text-white">Admin name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="admin name"
                    value="<?php echo e(old('name', $defaultAdminName ?? '')); ?>"
                    required
                    class="w-full"
                />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-2 text-white">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    required
                    class="w-full"
                />
            </div>

            <button 
                id="superadmin-login-btn"
                type="submit" 
                class="w-full scms-primary-btn"
                aria-busy="false">
                <span class="btn-text">Login</span>
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
<?php if (isset($__attributesOriginaled17748e2d35dfac5d4111319639b524)): ?>
<?php $attributes = $__attributesOriginaled17748e2d35dfac5d4111319639b524; ?>
<?php unset($__attributesOriginaled17748e2d35dfac5d4111319639b524); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled17748e2d35dfac5d4111319639b524)): ?>
<?php $component = $__componentOriginaled17748e2d35dfac5d4111319639b524; ?>
<?php unset($__componentOriginaled17748e2d35dfac5d4111319639b524); ?>
<?php endif; ?>

<script>
    (function(){
        const form = document.getElementById('superadmin-login-form');
        const btn = document.getElementById('superadmin-login-btn');
        const btnText = btn && btn.querySelector('.btn-text');
        const spinner = btn && btn.querySelector('.btn-spinner');

        if (!form || !btn) return;

        function setLoading(state){
            if (state){
                btn.setAttribute('disabled','disabled');
                btn.setAttribute('aria-busy','true');
                if (btnText) btnText.textContent = 'Logging in…';
                if (spinner) spinner.style.display = 'inline-block';
            } else {
                btn.removeAttribute('disabled');
                btn.setAttribute('aria-busy','false');
                if (btnText) btnText.textContent = 'Login';
                if (spinner) spinner.style.display = 'none';
            }
        }

        form.addEventListener('submit', function(e){
            // If JS enabled, intercept and submit via fetch
            e.preventDefault();
            // ensure form submits replace current tab when JS is enabled
            try { form.target = '_self'; } catch (err) {}
            setLoading(true);

            const data = new FormData(form);
            const tokenInput = document.querySelector('input[name="_token"]');
            const token = tokenInput ? tokenInput.value : '';

            fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: data,
                credentials: 'same-origin'
            }).then(res => {
                if (res.ok) return res.json();
                // Try to get error message from response
                return res.json().then(j => Promise.reject(j)).catch(() => {
                    // If JSON parsing fails, reject with status text
                    return Promise.reject({ message: res.statusText || 'Login failed' });
                });
            }).then(json => {
                if (json.redirect) {
                    // Small delay to ensure session cookie is set by browser
                    setTimeout(function() {
                        window.location.href = json.redirect;
                    }, 100);
                } else {
                    setLoading(false);
                }
            }).catch(err => {
                setLoading(false);
                // show error banner
                let msg = 'Invalid user';
                
                let existing = document.querySelector('.superadmin-error-banner');
                if (!existing){
                    const d = document.createElement('div');
                    d.className = 'superadmin-error-banner mb-3 p-3 rounded text-sm text-white';
                    d.style.background = 'rgba(239, 68, 68, 0.2)';
                    d.style.borderLeft = '4px solid #ef4444';
                    d.textContent = msg;
                    form.insertBefore(d, form.firstChild);
                } else {
                    existing.textContent = msg;
                }
            });
        });
    })();
</script><?php /**PATH C:\Users\janar\Herd\scms\resources\views/auth/super-admin-login.blade.php ENDPATH**/ ?>