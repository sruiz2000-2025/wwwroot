/**
 * Ongoingteam — site.js
 * Handles: mobile nav, CSRF-safe AJAX contact form, small UX helpers
 */

(function () {
  'use strict';

  /* =========================
     Mobile Navigation Toggle
  ========================== */
  const nav = document.querySelector('.nav');
  const header = document.querySelector('.header-inner');

  if (nav && header) {
    const toggle = document.createElement('button');
    toggle.className = 'nav-toggle';
    toggle.setAttribute('aria-label', 'Toggle menu');
    toggle.innerHTML = '<span></span><span></span><span></span>';
    header.appendChild(toggle);

    toggle.addEventListener('click', () => {
      nav.classList.toggle('open');
      toggle.classList.toggle('open');
    });
  }

  /* =========================
     CSRF helper
  ========================== */
  function getCSRF() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }

  /* =========================
     AJAX Contact Form
  ========================== */
  const form = document.querySelector('#contactForm');

  if (form) {
    const notice = document.querySelector('#formNotice');
    const submitBtn = form.querySelector('button[type="submit"]');

    function show(type, message) {
      if (!notice) return;
      notice.style.display = 'block';
      notice.className = 'notice ' + type;
      notice.textContent = message;
    }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const fd = new FormData(form);

      if (!fd.get('name') || !fd.get('email') || !fd.get('message')) {
        show('error', 'Please fill in your name, email, and message.');
        return;
      }

      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending…';

      try {
        const res = await fetch('/api/contact', {
          method: 'POST',
          headers: {
            'X-CSRF-Token': getCSRF()
          },
          body: fd
        });

        const data = await res.json();

        if (!res.ok || !data.ok) {
          show('error', data.error || 'Something went wrong. Please try again.');
        } else {
          show('success', data.message || 'Message sent successfully.');
          form.reset();
        }
      } catch (err) {
        show('error', 'Network error. Please try again later.');
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message';
      }
    });
  }

})();

// Process step interactivity
(function () {
  const initProcess = () => {
    const root = document.querySelector('.lux-process');
    if (!root) return;

    const steps = root.querySelectorAll('.process-step');
    const slides = root.querySelectorAll('.process-slide');
    if (!steps.length || !slides.length) return;

    const activate = (stepId) => {
      steps.forEach((s) => {
        const isActive = s.dataset.step === stepId;
        s.classList.toggle('active', isActive);
        s.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });
      slides.forEach((slide) => {
        slide.classList.toggle('is-visible', slide.dataset.step === stepId);
      });
    };

    steps.forEach((step) => {
      step.addEventListener('click', () => activate(step.dataset.step));
      step.addEventListener('keyup', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          activate(step.dataset.step);
        }
      });
      step.setAttribute('tabindex', '0');
      step.setAttribute('role', 'button');
      step.setAttribute('aria-pressed', step.classList.contains('active') ? 'true' : 'false');
      if (step.getAttribute('aria-controls')) {
        step.setAttribute('aria-controls', step.getAttribute('aria-controls'));
      }
    });

    // Activate first slide by default
    const defaultStep = slides[0]?.dataset.step || steps[0]?.dataset.step || '1';
    activate(defaultStep);
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initProcess);
  } else {
    initProcess();
  }
})();

// FAQ overlay carousel
(function(){
  const overlay = document.getElementById('faqOverlay');
  const openBtn = document.getElementById('openFaqModal');
  if(!overlay) return;

  const slides = Array.from(overlay.querySelectorAll('.faq-slide'));
  const dotsWrap = document.getElementById('faqDots');
  const closeEls = overlay.querySelectorAll('[data-faq-close]');
  const prev = overlay.querySelector('[data-faq-prev]');
  const next = overlay.querySelector('[data-faq-next]');
  const idToIndex = slides.reduce((acc, slide, i) => {
    const id = slide.getAttribute('data-faq-id');
    if (id !== null && id !== undefined) acc[id] = i;
    return acc;
  }, {});
  let idx = 0;

  function renderDots(){
    dotsWrap.innerHTML='';
    slides.forEach((_, i)=>{
      const b=document.createElement('button');
      b.className = i===idx ? 'active' : '';
      b.setAttribute('aria-label', 'Go to FAQ '+(i+1));
      b.addEventListener('click', ()=>setIndex(i));
      dotsWrap.appendChild(b);
    });
  }

  function setIndex(newIdx){
    if(!slides.length) return;
    idx = Math.max(0, Math.min(slides.length-1, newIdx));
    slides.forEach((s,i)=>s.classList.toggle('is-visible', i===idx));
    renderDots();
  }

  function open(startIdx=0){
    overlay.classList.add('open');
    overlay.setAttribute('aria-hidden','false');
    setIndex(startIdx);
  }
  function close(){
    overlay.classList.remove('open');
    overlay.setAttribute('aria-hidden','true');
  }

  if(openBtn) openBtn.addEventListener('click', (e)=>{ e.preventDefault(); open(); });
  closeEls.forEach(el=>el.addEventListener('click', close));
  if(prev) prev.addEventListener('click', ()=>setIndex(idx-1));
  if(next) next.addEventListener('click', ()=>setIndex(idx+1));
  document.addEventListener('keyup', (e)=>{ if(e.key==='Escape') close(); });

  // allow clicking FAQ cards to open at that index
  const cardTriggers = document.querySelectorAll('[data-faq-open]');
  cardTriggers.forEach(card=>{
    card.addEventListener('click', ()=>{
      const targetId = card.dataset.faqOpen;
      const mapped = targetId in idToIndex ? idToIndex[targetId] : parseInt(targetId, 10);
      const safeIndex = isNaN(mapped) ? 0 : mapped;
      open(safeIndex);
    });
  });

  renderDots();
})();
