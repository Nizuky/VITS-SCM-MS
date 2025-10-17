<div class="text-center space-y-2">
    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">
        {{ $title ?? '' }}
    </h1>
    @if (! empty($description))
        <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ $description }}</p>
    @endif
</div>
