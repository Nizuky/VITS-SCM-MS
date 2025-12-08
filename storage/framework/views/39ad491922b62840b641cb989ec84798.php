<style>
  /* ===========================================
     CRITICAL: Pure CSS Layout - No JS Dependency
     This ensures content is ALWAYS visible and centered
     even if JavaScript fails or runs with wrong timing
     =========================================== */
  :root {
    --header-height: 115px;
    --header-height-mobile: 80px;
    --header-height-actual: 115px;
  }
  
  /* Prevent horizontal scroll globally */
  html {
    overflow-x: hidden !important;
    max-width: 100vw !important;
  }
  
  #site-header {
    position: fixed; 
    top: 0; 
    left: 0; 
    right: 0; 
    width: 100%; 
    height: auto; 
    z-index: 1000;
    display: flex; 
    align-items: center; 
    justify-content: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    background: transparent;
    transition: transform .28s ease, opacity .28s ease;
    opacity: 1; 
    will-change: transform, opacity;
    overflow: visible;
    margin: 0;
    padding: 0;
  }
  
  #site-header img {
    width: 100vw;
    height: auto;
    object-fit: cover;
    object-position: center;
    max-width: 100vw;
    min-width: 100vw;
    display: block;
    margin: 0;
    padding: 0;
  }
  
  /* ===========================================
     BODY: Minimal styling - let pages handle their own layout
     =========================================== */
  body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    max-width: 100vw !important;
    overflow-x: hidden !important;
    min-height: 100vh !important;
    min-height: 100dvh !important;
    box-sizing: border-box !important;
  }
  
  /* Mobile */
  @media (max-width: 640px) {
    :root {
      --header-height-actual: var(--header-height-mobile);
    }
  }
  
  /* ===========================================
     Content wrappers - only set width constraints
     =========================================== */
  body > div.w-full {
    width: 100% !important;
    max-width: 100vw !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
  }
  
  /* Header hidden state */
  #site-header.header-hidden { 
    transform: translateY(-120%); 
    opacity: 0; 
    pointer-events: none; 
  }
</style>

<script>
  // ===========================================
  // CRITICAL: Force layout recalculation on page load/restore
  // This fixes the mobile centering issue on bfcache navigation
  // ===========================================
  (function(){
    function forceLayoutRecalc() {
      // Force a reflow by reading offsetHeight
      void document.body.offsetHeight;
      
      // Force repaint by toggling a class
      document.body.classList.add('layout-recalc');
      requestAnimationFrame(function() {
        document.body.classList.remove('layout-recalc');
      });
    }
    
    function adjustPadding() {
      var header = document.getElementById('site-header');
      if (!header) return;
      
      var img = header.querySelector('img');
      
      function applyHeight() {
        var headerHeight = header.offsetHeight;
        if (headerHeight > 0) {
          // Set CSS variable for other components to use
          document.documentElement.style.setProperty('--header-height-actual', headerHeight + 'px');
          // Only override body padding if we have a valid measurement
          document.body.style.paddingTop = headerHeight + 'px';
        }
        // Force layout recalculation after applying padding
        forceLayoutRecalc();
      }
      
      // If image exists and not loaded, wait for it
      if (img && !img.complete) {
        img.addEventListener('load', applyHeight, { once: true });
        // Fallback timeout in case load event doesn't fire
        setTimeout(applyHeight, 500);
      } else {
        applyHeight();
      }
    }
    
    // Run immediately for fastest possible execution
    adjustPadding();
    
    // Run on DOMContentLoaded
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', adjustPadding);
    }
    
    // Run on window load (after images)
    window.addEventListener('load', adjustPadding);
    
    // Run on resize
    window.addEventListener('resize', adjustPadding);
    
    // CRITICAL: Run on pageshow for back/forward cache (bfcache) navigation
    window.addEventListener('pageshow', function(event) {
      // Force immediate layout recalc
      forceLayoutRecalc();
      // Then adjust padding with delays
      adjustPadding();
      setTimeout(adjustPadding, 50);
      setTimeout(adjustPadding, 150);
      setTimeout(forceLayoutRecalc, 200);
    });
    
    // Handle visibility change (mobile browsers)
    document.addEventListener('visibilitychange', function() {
      if (document.visibilityState === 'visible') {
        forceLayoutRecalc();
        setTimeout(adjustPadding, 50);
      }
    });
  })();
</script>

<script>
  // Header scroll show/hide functionality
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
    
    // Initialize scroll handling
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', setup);
    } else {
      setup();
    }
    
    // Re-initialize on pageshow for bfcache
    window.addEventListener('pageshow', setup);
  })();
</script>

<header id="site-header">
  <img src="<?php echo e(asset('storage/vits_header.png')); ?>" onerror="this.onerror=null;this.src='<?php echo e(url('assets/vits_header.png')); ?>'" alt="VITS Header" />
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
</script><?php /**PATH C:\Users\janar\Herd\scms\resources\views\partials\vits_branding.blade.php ENDPATH**/ ?>