<div style="margin-top:20px; text-align:center;">
        {{-- Prefer named route 'home' (root) if available --}}
        @php
                $url = Route::has('home') ? route('home') : url('/');
        @endphp

        <a href="{{ $url }}" class="return-home-btn inline-block text-center no-underline" aria-label="Return to homepage">
            &larr; Return to Homepage
        </a>

        <style>
        .return-home-btn {
            display: inline-block;
            padding: 10px 16px;
            background: transparent;
            color: #ffffff;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
            text-decoration: none;
        }
        .return-home-btn:hover {
            background: #1a00ac;
            color: #ffffff;
        }
        </style>

</div>
