@props(['url'])
<tr>
    <td class="header" style="padding: 25px 0; text-align: center;">
        @php
            $candidates = [
                'vits_white.png',
                'storage/vits_whites.png',
                'vits_whites.png',
                'vitswhite.png',
                'vitslogo.png',
                'storage/vits_header.png',
            ];
            $logoUrl = null;
            foreach ($candidates as $rel) {
                try {
                    if (file_exists(public_path($rel))) { $logoUrl = asset($rel); break; }
                } catch (Throwable $e) {}
            }
            if (!$logoUrl) { $logoUrl = asset('vits_white.png'); }
        @endphp
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ $logoUrl }}" class="logo" alt="{{ config('app.name') }}" style="width:120px; height:auto; display:block; margin:0 auto;" />
        </a>
    </td>
</tr>
