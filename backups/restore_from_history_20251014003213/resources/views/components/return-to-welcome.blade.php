<div style="margin-top:20px; text-align:center;">
    {{-- Prefer named route 'home' (root) if available --}}
    @php
        $url = Route::has('home') ? route('home') : url('/');
    @endphp

    <button type="button" class="return-home-btn inline-block text-center" aria-label="Return to homepage" data-target="{{ $url }}">
        &larr; Return to Homepage
    </button>

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
        border: none;
        cursor: pointer;
    }
    .return-home-btn:hover {
        background: #1a00ac;
        color: #ffffff;
    }
    </style>

    <script>
    (function(){
        const btn = document.querySelector('.return-home-btn');
        if (!btn) return;
        const url = btn.getAttribute('data-target');

        btn.addEventListener('click', function(e){
            e.preventDefault();

            // If there is an opener (original tab) and same-origin, try to redirect it and close this tab
            try {
                if (window.opener && !window.opener.closed) {
                    try {
                        window.opener.location.href = url;
                        // Give the opener a moment to navigate, then close this tab
                        setTimeout(function(){ window.close(); }, 250);
                        return;
                    } catch (err) {
                        // cross-origin or other issue — fall through to fallback
                        console.debug('opener redirect failed', err);
                    }
                }
            } catch (e) {
                console.debug('opener check failed', e);
            }

            // Fallback: navigate current tab to URL then attempt to close (closing will usually be blocked unless opened by script)
            window.location.href = url;
            setTimeout(function(){ try { window.close(); } catch(e) {} }, 400);
        });
    })();
    </script>
</div>
