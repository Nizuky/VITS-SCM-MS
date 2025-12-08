<div class="text-center space-y-2">
    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
        <?php echo e($title ?? ''); ?>

    </h1>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($description)): ?>
        <p class="text-sm text-zinc-600 dark:text-zinc-400"><?php echo e($description); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\components\auth-header.blade.php ENDPATH**/ ?>