<?php
// prices.php (split header/footer)
?>
<?php include __DIR__ . "/partials/header.php"; ?>




<div class="container">
  <div class="grid two">
    <div class="card">
      <div class="h1">Prices</div>
      <p class="sub">We don’t publish one-size-fits-all pricing because support needs vary by workload, urgency, and expertise. Instead, tell us what you need and we’ll respond with the best-fit option.</p>

      <div class="hr"></div>

      <div class="grid" style="gap:10px;">
        <div class="pill">Custom to your services and workload</div>
        <div class="pill">Often more cost‑effective than hiring in‑house</div>
        <div class="pill">Deadline-driven delivery</div>
        <div class="pill">Scale up or down anytime</div>
      </div>

      <div class="hr"></div>

      <h2>Request a quote</h2>
      <p class="small">Select services to speed up your quote. You only need a name and email to start.</p>

      <div class="row two">
        <div>
          <div class="label">Full name</div>
          <input class="input" id="full_name" placeholder="Your name">
        </div>
        <div>
          <div class="label">Email</div>
          <input class="input" id="email" placeholder="you@email.com">
        </div>
      </div>

      <div style="margin-top:12px;">
        <div class="label">Services filter</div>
        <input class="input" id="services_filter" placeholder="Search services…">
      </div>

      <div style="margin-top:12px;">
        <div class="label">Details</div>
        <textarea class="input" id="details" placeholder="What tasks, how many hours/week, deadlines, tools (Airbnb, CRM, email, etc.)?"></textarea>
      </div>

      <input type="hidden" id="service_ids" value="">
      <input type="hidden" id="csrf" value="<?php echo htmlspecialchars($csrf, ENT_QUOTES); ?>">

      <div id="result" class="notice" style="display:none;margin-top:12px;"></div>

      <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
        <button class="btn primary" id="submit_btn" type="button">Request quote</button>
        <span class="small">We’ll email you a confirmation and follow up with next steps.</span>
      </div>
    </div>

    <div class="card">
      <h2>Select services</h2>
      <p class="sub">Click to add. We auto‑attach them to your details so your request is clear.</p>
      <div id="services_box" class="grid" style="gap:12px;">
        <div class="small">Loading services…</div>
      </div>
    </div>
  </div>
</div>




<script>
(async function(){
  const servicesBox = document.getElementById('services_box');
  const filter = document.getElementById('services_filter');
  const details = document.getElementById('details');
  const hidden = document.getElementById('service_ids');
  const result = document.getElementById('result');
  const btn = document.getElementById('submit_btn');

  let servicesData=null;
  let selected=new Set();

  function show(msg, ok){
    result.style.display='';
    result.className='notice '+(ok?'good':'bad');
    result.textContent=msg;
  }

  function updateDetails(){
    const names=[];
    if(servicesData){
      for(const c of servicesData){
        for(const s of (c.services||[])){ if(selected.has(String(s.id))) names.push(s.name); }
      }
    }
    const prefix = names.length ? ("Services requested: " + names.join(', ') + "\n\n") : "";
    const current = details.value.replace(/^Services requested:.*\n\n/s, '');
    details.value = prefix + current;
    hidden.value = Array.from(selected).join(',');
  }

  function render(ft=''){
    servicesBox.innerHTML='';
    ft=ft.trim().toLowerCase();
    for(const cat of servicesData||[]){
      const items=(cat.services||[]).filter(s=>!ft || s.name.toLowerCase().includes(ft) || cat.name.toLowerCase().includes(ft));
      if(!items.length) continue;
      const card=document.createElement('div'); card.className='card'; card.style.boxShadow='none';
      card.innerHTML=`<h2>${cat.name}</h2><div class="service-wrap"></div>`;
      const wrap=card.querySelector('.service-wrap');
      for(const s of items){
        const pill=document.createElement('div');
        pill.className='service';
        pill.textContent=s.name;
        pill.dataset.selected=selected.has(String(s.id))?'1':'0';
        pill.addEventListener('click', ()=>{
          const id=String(s.id);
          if(selected.has(id)) selected.delete(id); else selected.add(id);
          pill.dataset.selected=selected.has(id)?'1':'0';
          updateDetails();
        });
        wrap.appendChild(pill);
      }
      servicesBox.appendChild(card);
    }
  }

  // cache services
  const key='ot_services_v1';
  const cached=localStorage.getItem(key);
  if(cached){
    try{
      const obj=JSON.parse(cached);
      if(obj && obj.exp && Date.now()<obj.exp) servicesData=obj.data;
    }catch(e){}
  }
  if(!servicesData){
    const res=await fetch('/api/services.php',{headers:{'Accept':'application/json'}});
    const data=await res.json();
    servicesData=data.categories||[];
    localStorage.setItem(key, JSON.stringify({exp: Date.now()+15*60*1000, data: servicesData}));
  }
  render('');

  filter.addEventListener('input', ()=>render(filter.value));

  btn.addEventListener('click', async ()=>{
    btn.disabled=true; btn.textContent='Sending…';
    const payload={
      csrf: document.getElementById('csrf').value,
      full_name: document.getElementById('full_name').value,
      email: document.getElementById('email').value,
      phone: '',
      language_preference: 'English',
      contact_type: 'pricing_request',
      details: details.value,
      service_ids: hidden.value,
      slot_local: '',
      preferred_date: '',
      preferred_time: ''
    };
    try{
      const res=await fetch('/api/book.php', {
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json'},
        body: JSON.stringify(payload)
      });
      const data=await res.json();
      if(!data.ok) throw new Error(data.error || 'Request failed');
      show('Quote request sent. Check your email for confirmation.', true);
      btn.textContent='Sent';
    }catch(e){
      show(e.message || 'Something went wrong.', false);
      btn.disabled=false; btn.textContent='Request quote';
    }
  });
})();
</script>


<?php include __DIR__ . "/partials/footer_shared.php"; ?>
