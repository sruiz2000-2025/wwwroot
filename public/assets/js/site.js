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