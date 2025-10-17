<style>
  :root { --vits-header-h: 115px; }
  body { padding-top: var(--vits-header-h) !important; }
  #site-header { position: fixed; top: 0; left: 0; width: 100%; height: var(--vits-header-h); z-index: 1000; display:flex; align-items:center; justify-content:center; box-shadow: 0 2px 12px rgba(0,0,0,0.12); background: rgba(255,255,255,0.95); transition: transform .28s ease; }
  #site-header.header-hidden { transform: translateY(-120%); }
</style>

<script>
  (function(){
    const header = document.getElementById('site-header');
    if (!header) return;
    let last = window.pageYOffset || document.documentElement.scrollTop;
    let ticking = false; const threshold = 22; let lastToggle = 0; const minInt = 120;
    function onScroll(){
      const cur = window.pageYOffset || document.documentElement.scrollTop;
      const diff = cur - last; if (Math.abs(diff) < threshold) return;
      const now = Date.now(); if (now - lastToggle < minInt) { last = cur; ticking = false; return; }
      if (diff > 0) header.classList.add('header-hidden'); else header.classList.remove('header-hidden');
      lastToggle = now; last = cur <= 0 ? 0 : cur; ticking = false;
    }
    window.addEventListener('scroll', function(){ if (!ticking){ window.requestAnimationFrame(onScroll); ticking = true; } }, { passive: true });
  })();
</script>

<header id="site-header">
  <img src="{{ asset('storage/vits_header.png') }}" onerror="this.onerror=null;this.src='{{ url('assets/vits_header.png') }}'" alt="VITS Header" style="width:100%; height:100%; object-fit:cover; display:block;" />
</header>

<script>
  // Ensure background image is applied even if storage symlink is missing
  (function(){
    var bg = "{{ asset('storage/vits_bg.png') }}";
    var testImg = new Image();
    testImg.onload = function(){ document.body.style.backgroundImage = 'url(' + bg + ')'; };
    testImg.onerror = function(){ document.body.style.backgroundImage = 'url({{ url('assets/vits_bg.png') }})'; };
    testImg.src = bg;
    document.body.style.backgroundRepeat = 'no-repeat';
    document.body.style.backgroundPosition = 'center top';
    document.body.style.backgroundSize = 'cover';
    document.body.style.backgroundAttachment = 'fixed';
  })();
</script>