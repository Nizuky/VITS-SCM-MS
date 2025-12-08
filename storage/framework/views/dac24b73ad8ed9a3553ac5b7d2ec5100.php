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
    <?php echo e($slot); ?>

    
    
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
<?php /**PATH C:\Users\janar\Herd\scms\resources\views/components/layouts/auth.blade.php ENDPATH**/ ?>