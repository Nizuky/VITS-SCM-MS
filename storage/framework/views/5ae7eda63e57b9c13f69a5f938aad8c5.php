<?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
<?php $__env->startComponent('components.layouts.auth.simple'); ?>
    <div class="container max-w-md mx-auto p-6">
       <h2 class="mb-4" style="font-weight: bold; text-align: center; font-size: 20px;">Super Admin Login</h2>

        <form id="superadmin-login-form" method="POST" action="<?php echo e(route('superadmin.login.submit')); ?>">
            <?php echo csrf_field(); ?>
            <?php if(session('success')): ?>
                <div class="mb-3 p-3 rounded text-sm" style="background:#e6ffef; border-left:4px solid #24a148; color:#064e3b;"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="mb-3 p-3 rounded text-sm" style="background:#fff3f2; border-left:4px solid #ef4444; color:#7f1d1d;"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <div class="mb-3">
                <label for="name" class="block mb-1 font-semibold text-sm">Admin name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring"
                    placeholder="admin name"
                    value="<?php echo e(old('name', $defaultAdminName ?? '')); ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="password" class="block mb-1 font-semibold text-sm">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring"
                    placeholder="Enter password"
                    required
                >
            </div>

            <button 
                id="superadmin-login-btn"
                type="submit" 
                class="role-btn w-full mt-4 bg-white text-black text-sm rounded py-2 hover:bg-[#1a00ac] hover:text-white transition"
                aria-busy="false">
                <span class="btn-text">Login</span>
                <span class="btn-spinner" style="display:none; margin-left:8px;">
                    <svg width="18" height="18" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle">
                        <circle cx="25" cy="25" r="20" fill="none" stroke="#1a00ac" stroke-width="4" stroke-linecap="round" stroke-dasharray="31.4 31.4">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.9s" repeatCount="indefinite" />
                        </circle>
                    </svg>
                </span>
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
<?php echo $__env->renderComponent(); ?>

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
            const token = document.querySelector('input[name="_token"]').value;

            fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                body: data,
                credentials: 'same-origin'
            }).then(res => {
                if (res.ok) return res.json();
                return res.json().then(j => Promise.reject(j));
            }).then(json => {
                if (json.redirect) window.location.href = json.redirect;
                else setLoading(false);
            }).catch(err => {
                setLoading(false);
                // show error banner
                let msg = 'Invalid credentials.';
                if (err && err.message) msg = err.message;
                let existing = document.querySelector('.superadmin-error-banner');
                if (!existing){
                    const d = document.createElement('div');
                    d.className = 'superadmin-error-banner mb-3 p-3 rounded text-sm';
                    d.style.background = '#fff3f2'; d.style.borderLeft = '4px solid #ef4444'; d.style.color = '#7f1d1d';
                    d.textContent = msg;
                    form.insertBefore(d, form.firstChild);
                } else {
                    existing.textContent = msg;
                }
            });
        });
    })();
</script><?php /**PATH C:\Users\janar\Herd\scms\resources\views/auth/super-admin-login.blade.php ENDPATH**/ ?>