<style>
  :root { --vits-header-h: 115px; }
  body { padding-top: var(--vits-header-h) !important; }
  #site-header {
    position: fixed; top: 0; left: 0; width: 100%; height: var(--vits-header-h); z-index: 1000;
    display:flex; align-items:center; justify-content:center;
    box-shadow: 0 2px 12px rgba(0,0,0,0.12);
    background: rgba(255,255,255,0.95);
    transition: transform .28s ease, opacity .28s ease;
    opacity: 1; will-change: transform, opacity;
  }
  #site-header.header-hidden { transform: translateY(-120%); opacity: 0; pointer-events: none; }
</style>

<script>
  (function(){
    function setup(){
      const header = document.getElementById('site-header');
      if (!header) return;
      const threshold = 8; // pixels of movement before toggling
      const minInterval = 120; // ms between toggles
      let lastToggleAt = 0;

      // Determine the primary scroll container
      function isScrollable(el){
        if (!el) return false;
        try {
          const sh = el.scrollHeight || 0;
          const ch = el.clientHeight || 0;
          if (sh - ch > 2) return true;
        } catch(_) {}
        return false;
      }

      function getScrollTopOf(el){
        if (el === window) return window.pageYOffset || document.documentElement.scrollTop || 0;
        return el.scrollTop || 0;
      }

      function pickScrollTarget(){
        const candidates = [];
        const pageContainer = document.getElementById('page-container');
        if (pageContainer) candidates.push(pageContainer);
        const mainEl = document.querySelector('main');
        if (mainEl) candidates.push(mainEl);
        document.querySelectorAll('.content-area-auto, .page-content').forEach(el => candidates.push(el));
        // Prefer a visible, scrollable candidate
        const chosen = candidates.find(el => isScrollable(el) && el.offsetParent !== null);
        return chosen || window;
      }

      let target = pickScrollTarget();
      let lastTop = getScrollTopOf(target);
      let ticking = false;

      function onScroll(){
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(function(){
          const curTop = getScrollTopOf(target);
          // Always reveal at very top
          if (curTop <= 0) {
            header.classList.remove('header-hidden');
            lastTop = 0; ticking = false; return;
          }
          const diff = curTop - lastTop;
          if (Math.abs(diff) >= threshold) {
            const now = Date.now();
            if (now - lastToggleAt >= minInterval) {
              if (diff > 0) header.classList.add('header-hidden');
              else header.classList.remove('header-hidden');
              lastToggleAt = now;
            }
            lastTop = curTop;
          }
          ticking = false;
        });
      }

      // Attach scroll listener
      const scrollTarget = (target === window) ? window : target;
      scrollTarget.addEventListener('scroll', onScroll, { passive: true });

      // Re-evaluate on resize (layout changes may alter the scroll container)
      window.addEventListener('resize', function(){
        const newTarget = pickScrollTarget();
        if (newTarget !== target) {
          // Remove old listener
          (target === window ? window : target).removeEventListener('scroll', onScroll);
          target = newTarget;
          lastTop = getScrollTopOf(target);
          (target === window ? window : target).addEventListener('scroll', onScroll, { passive: true });
        }
      });
    }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', setup, { once: true });
    } else {
      setup();
    }
  })();
  </script>

<header id="site-header">
  <img src="<?php echo e(asset('storage/vits_header.png')); ?>" onerror="this.onerror=null;this.src='<?php echo e(url('assets/vits_header.png')); ?>'" alt="VITS Header" style="width:100%; height:100%; object-fit:cover; display:block;" />
</header>

<script>
  // Ensure background image is applied even if storage symlink is missing
  (function(){
    var bg = "<?php echo e(asset('storage/vits_bg.png')); ?>";
    var testImg = new Image();
    testImg.onload = function(){ document.body.style.backgroundImage = 'url(' + bg + ')'; };
    testImg.onerror = function(){ document.body.style.backgroundImage = 'url(<?php echo e(url('assets/vits_bg.png')); ?>)'; };
    testImg.src = bg;
    document.body.style.backgroundRepeat = 'no-repeat';
    document.body.style.backgroundPosition = 'center top';
    document.body.style.backgroundSize = 'cover';
    document.body.style.backgroundAttachment = 'fixed';
  })();
</script><?php /**PATH C:\Users\janar\Herd\scms\resources\views/partials/vits_branding.blade.php ENDPATH**/ ?>