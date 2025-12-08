@props(['heading' => null, 'subheading' => null])

<div {{ $attributes->merge(['class' => 'settings-layout']) }}>
    <header class="mb-6">
        @if($heading)
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $heading }}</h2>
        @endif

        @if($subheading)
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $subheading }}</p>
        @endif
    </header>

    <div class="settings-content">
        {{ $slot }}
    </div>
</div>
