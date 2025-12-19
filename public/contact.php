<?php
// contact.php (split header/footer)
?>
<?php include __DIR__ . "/partials/header.php"; ?>



<div class="container">
  <div class="grid two">
    <div class="card">
      <div class="h1" id="book">Contact</div>
      <p class="sub">Tell us what you need, how you like to work, and the deadlines you're up against. Book a Zoom, request a call, or drop a note—whatever is fastest for you.</p>

      <div id="result" class="notice" style="display:none;"></div>

      <div class="row two" style="margin-top:14px;">
        <div>
          <div class="label">Full name</div>
          <input class="input" id="full_name" autocomplete="name" placeholder="Your name">
        </div>
        <div>
          <div class="label">Email</div>
          <input class="input" id="email" autocomplete="email" placeholder="you@email.com">
        </div>
      </div>

      <div class="row two" style="margin-top:12px;">
        <div>
          <div class="label">Phone (optional)</div>
          <input class="input" id="phone" autocomplete="tel" placeholder="+1 (555) 123-4567">
        </div>
        <div>
          <div class="label">Language preference</div>
          <select class="input" id="language_preference">
            <option>English</option>
            <option>Spanish</option>
          </select>
        </div>
      </div>

      <div class="row two" style="margin-top:12px;">
        <div>
          <div class="label">Type of contact</div>
          <select class="input" id="contact_type">
            <option value="" selected disabled>Select contact type</option>
            <option value="zoom">Zoom meeting</option>
            <option value="phone">Phone</option>
            <option value="email">Email</option>
          </select>
        </div>
      </div>

      <div id="zoom_box" style="margin-top:12px;">
        <div class="card" style="box-shadow:none;">
          <h2>Zoom scheduling</h2>
          <p class="small">Pick a date to load available slots. If nothing fits, request a specific time—we'll route it to the team.</p>
          <div class="row two" style="margin-top:8px;">
            <div>
              <div class="label">Pick a date to browse availability</div>
              <input class="input" id="preferred_date" type="date">
            </div>
            <div>
              <div class="label">Available slots</div>
              <select class="input" id="slot_select">
                <option value="">Select a slot (optional)</option>
              </select>
              <div class="small" id="special_info" style="margin-top:6px;"></div>
            </div>
          </div>

          <div class="hr"></div>

          <div class="row two">
            <div>
              <div class="label">Specific date (if you need it)</div>
              <input class="input" id="preferred_date_specific" type="date">
            </div>
            <div>
              <div class="label">Specific time</div>
              <input class="input" id="preferred_time" type="time">
            </div>
          </div>
          <div class="small" style="margin-top:6px;">If the date/time is blocked, we'll log it as a special request and reply with the closest match.</div>
        </div>
      </div>

      <div style="margin-top:12px;">
        <div class="label">Details</div>
        <textarea class="input" id="details" placeholder="What do you want off your plate? Tasks, tools, timelines, and what success looks like."></textarea>
      </div>

      <input type="hidden" id="service_ids" value="">
      <input type="hidden" id="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES); ?>">

      <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
        <button class="btn primary" id="submit_btn" type="button">Send request</button>
        <span class="small">You'll get a confirmation email; our team is notified immediately.</span>
      </div>
    </div>

    <div class="grid" style="gap:18px;">
      <div class="card">
        <h2>Pick your services</h2>
        <p class="sub">Tap to select. On mobile, chips wrap for easy tapping. We attach your selections to the request automatically.</p>
        <div class="label" style="margin-top:8px;">Services filter</div>
        <input class="input" id="services_filter" placeholder="Search services...">
        <div id="services_box" class="grid" style="gap:12px; margin-top:12px;">
          <div class="small">Loading services...</div>
        </div>
      </div>

      <div class="card">
        <h2>Professional, fast, and clear</h2>
        <p class="sub">We align on your standards, send concise updates, and course-correct quickly if priorities shift.</p>
        <div class="hr"></div>
        <div class="grid" style="gap:10px;">
          <div class="pill">Consistent execution with fewer interruptions</div>
          <div class="pill">Lower cost than in-house hires</div>
          <div class="pill">Deadline-driven delivery</div>
          <div class="pill">Scales up or down with your needs</div>
        </div>
        <a class="btn" style="margin-top:12px;" href="/prices">See pricing approach</a>
      </div>
    </div>
  </div>
</div>



<script src="/assets/js/contact.js"></script>
<script>
(function(){
  const result = document.getElementById('result');
  const btn = document.getElementById('submit_btn');

  function show(msg, ok){
    result.style.display = '';
    result.className = 'notice ' + (ok ? 'good' : 'bad');
    result.textContent = msg;
    result.scrollIntoView({behavior:'smooth', block:'start'});
  }

  btn.addEventListener('click', async ()=>{
    btn.disabled = true;
    btn.textContent = 'Sending...';

    const contactType = document.getElementById('contact_type').value;
    const slotLocal = document.getElementById('slot_select').value;
    const dateBrowse = document.getElementById('preferred_date').value;
    const dateSpecific = document.getElementById('preferred_date_specific').value;
    const timeSpecific = document.getElementById('preferred_time').value;

    const payload = {
      csrf: document.getElementById('csrf').value,
      full_name: document.getElementById('full_name').value,
      email: document.getElementById('email').value,
      phone: document.getElementById('phone').value,
      language_preference: document.getElementById('language_preference').value,
      contact_type: contactType,
      details: document.getElementById('details').value,
      service_ids: document.getElementById('service_ids').value,
      slot_local: (contactType==='zoom') ? slotLocal : '',
      preferred_date: (contactType==='zoom') ? (dateSpecific || dateBrowse) : '',
      preferred_time: (contactType==='zoom') ? timeSpecific : ''
    };

    try{
      const res = await fetch('/api/book.php', {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json'},
        body: JSON.stringify(payload)
      });
      const data = await res.json();
      if(!data.ok) throw new Error(data.error || 'Request failed');
      if(data.special_request) {
        show('Request sent. Your preferred date/time was recorded as a special request. We will follow up by email.', true);
      } else {
        show('Request sent. If you selected a Zoom slot, it is now reserved. Check your email for confirmation.', true);
      }
      btn.textContent='Sent';
    }catch(e){
      show(e.message || 'Something went wrong. Please try again.', false);
      btn.disabled=false;
      btn.textContent='Send request';
    }
  });
})();
</script>


<?php include __DIR__ . "/partials/footer_shared.php"; ?>
