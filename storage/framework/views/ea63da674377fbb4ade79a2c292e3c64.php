<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
    <head>
        <?php echo $__env->make('partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <?php
                // Compute profile and logout URLs once to avoid RouteNotFoundException when route names are missing
                $profileRouteName = null;
                foreach (['profile.edit', 'admin.profile.edit', 'superadmin.profile.edit'] as $r) {
                    if (\Illuminate\Support\Facades\Route::has($r)) {
                        $profileRouteName = $r;
                        break;
                    }
                }
                $profileUrl = $profileRouteName ? route($profileRouteName) : '#';

                if (auth('admin')->check() && \Illuminate\Support\Facades\Route::has('admin.logout')) {
                    $logoutUrl = route('admin.logout');
                } elseif (auth('superadmin')->check() && \Illuminate\Support\Facades\Route::has('superadmin.logout')) {
                    $logoutUrl = route('superadmin.logout');
                } elseif (\Illuminate\Support\Facades\Route::has('logout')) {
                    $logoutUrl = route('logout');
                } else {
                    $logoutUrl = '#';
                }
            ?>
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="<?php echo e(route('dashboard')); ?>" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <?php if (isset($component)) { $__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3)): ?>
<?php $attributes = $__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3; ?>
<?php unset($__attributesOriginal7b17d80ff7900603fe9e5f0b453cc7c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3)): ?>
<?php $component = $__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3; ?>
<?php unset($__componentOriginal7b17d80ff7900603fe9e5f0b453cc7c3); ?>
<?php endif; ?>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate><?php echo e(__('Dashboard')); ?></flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                <?php echo e(__('Repository')); ?>

                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                <?php echo e(__('Documentation')); ?>

                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                        >
                                            <?php echo e(auth()->user()->initials()); ?>

                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold"><?php echo e(auth()->user()->name); ?></span>
                                        <span class="truncate text-xs"><?php echo e(auth()->user()->email); ?></span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profileUrl !== '#'): ?>
                                <flux:menu.item href="<?php echo e($profileUrl); ?>" icon="cog" wire:navigate><?php echo e(__('Settings')); ?></flux:menu.item>
                            <?php else: ?>
                                <flux:menu.item href="#" icon="cog"><?php echo e(__('Settings')); ?></flux:menu.item>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="<?php echo e($logoutUrl); ?>" class="w-full">
                            <?php echo csrf_field(); ?>
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                                <?php echo e(__('Log Out')); ?>

                            </flux:menu.item>
                        </form>
                    </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        <?php echo e(auth()->user()->initials()); ?>

                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold"><?php echo e(auth()->user()->name); ?></span>
                                    <span class="truncate text-xs"><?php echo e(auth()->user()->email); ?></span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profileUrl !== '#'): ?>
                            <flux:menu.item href="<?php echo e($profileUrl); ?>" icon="cog" wire:navigate><?php echo e(__('Settings')); ?></flux:menu.item>
                        <?php else: ?>
                            <flux:menu.item href="#" icon="cog"><?php echo e(__('Settings')); ?></flux:menu.item>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="<?php echo e($logoutUrl); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            <?php echo e(__('Log Out')); ?>

                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <?php echo e($slot); ?>


        <?php echo $__env->make('partials.auto_logout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(class_exists('Flux\\Flux')): ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </body>
</html>
<?php /**PATH C:\Users\janar\Herd\scms\resources\views\components\layouts\app\sidebar.blade.php ENDPATH**/ ?>