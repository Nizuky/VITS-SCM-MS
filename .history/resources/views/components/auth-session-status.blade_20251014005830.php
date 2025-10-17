@props(['status' => null])

@if ($status)
    <div {{ $attributes->merge(['class' => 'px-3 py-2 rounded bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300']) }}>
        {{ $status }}
    </div>
@endif
