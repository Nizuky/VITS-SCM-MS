<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

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

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
