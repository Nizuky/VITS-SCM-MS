@props(['on' => null])

<div {{ $attributes->merge(['class' => 'action-message']) }} x-data="{ show: false }" x-on:{{ $on }}.window="show = true; setTimeout(()=> show = false, 3000)" x-show="show">
    {{ $slot }}
</div>
