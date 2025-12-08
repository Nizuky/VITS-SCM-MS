<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? 'VITS Social Contract Monitoring and Management System' }}</title>

@php
	$iconCandidates = [
		'vits_white.png',
		'storage/vits_whites.png',
		'vits_whites.png',
		'vitswhite.png',
		'vitslogo.png',
		'storage/vits_white.png',
		'storage/vits_header.png',
	];
	$iconUrl = null;
	$iconMTime = null;
	foreach ($iconCandidates as $relPath) {
		try {
			$full = public_path($relPath);
			if (file_exists($full)) {
				$iconUrl = asset($relPath);
				try { $iconMTime = @filemtime($full) ?: null; } catch (Throwable $e) {}
				break;
			}
		} catch (Throwable $e) {}
	}
	if (!$iconUrl) { $iconUrl = asset('vits_white.png'); }
	if ($iconUrl && $iconMTime) { $iconUrl .= '?v=' . $iconMTime; }
@endphp
<link rel="icon" href="{{ $iconUrl }}" sizes="any">
<link rel="icon" href="{{ $iconUrl }}" type="image/png">
<link rel="shortcut icon" href="{{ $iconUrl }}" type="image/png">
<link rel="apple-touch-icon" href="{{ $iconUrl }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@php
	// Use Vite-built assets when available. If the build manifest is missing (e.g. build failed),
	// fall back to the Tailwind CDN so styles still work in deployed environments.
	$viteManifest = public_path('build/manifest.json');
@endphp

@if (file_exists($viteManifest))
	@vite(['resources/css/app.css', 'resources/js/app.js'])
@else
	{{-- Fallback: Tailwind CDN (works when built assets are missing). --}}
	<script>
		if (!window.__TAILWIND_FALLBACK_LOADED__) {
			// Insert the Tailwind CDN script dynamically so it runs early
			var s = document.createElement('script');
			s.src = 'https://cdn.tailwindcss.com';
			s.defer = false;
			document.head.appendChild(s);
			window.__TAILWIND_FALLBACK_LOADED__ = true;
		}
	</script>
	{{-- Optional: include app specific custom CSS that relies on CSS variables (if present in public/css) --}}
	@php $appCss = public_path('css/app.css'); @endphp
	@if (file_exists($appCss))
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	@endif
@endif

@if(class_exists('Flux\Flux'))
    @fluxAppearance
@endif
