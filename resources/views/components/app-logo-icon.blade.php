<img src="{{ asset('vits_white.png') }}"
	alt="VITS logo"
	onerror="this.onerror=null; this.src='{{ asset('storage/vits_whites.png') }}'; this.onerror=function(){ this.src='{{ asset('vits_whites.png') }}'; this.onerror=function(){ this.src='{{ asset('vitswhite.png') }}'; this.onerror=function(){ this.src='{{ asset('vitslogo.png') }}'; }; }; };"
	{{ $attributes->merge(['class' => 'block']) }} />
