(async function(){
  const $ = (sel)=>document.querySelector(sel);
  const $$ = (sel)=>Array.from(document.querySelectorAll(sel));

  const contactType = $('#contact_type');
  const zoomBox = $('#zoom_box');
  const availabilityBox = $('#availability_box');
  const slotsSelect = $('#slot_select');
  const preferredDate = $('#preferred_date');
  const preferredTime = $('#preferred_time');
  const specialInfo = $('#special_info');

  const servicesBox = $('#services_box');
  const servicesFilter = $('#services_filter');
  const details = $('#details');

  let servicesData = null;
  let selectedServiceIds = new Set();

  function debounce(fn, ms){
    let t=null; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); };
  }

  function setZoomVisibility(){
    const isZoom = contactType.value === 'zoom';
    zoomBox.style.display = isZoom ? '' : 'none';
  }

  function updateDetailsFromServices(){
    const selectedNames = [];
    if (servicesData){
      for (const cat of servicesData){
        for (const s of cat.services){
          if (selectedServiceIds.has(String(s.id))) selectedNames.push(s.name);
        }
      }
    }
    const prefix = selectedNames.length ? ("Services requested: " + selectedNames.join(', ') + "\n\n") : "";
    const current = details.value.replace(/^Services requested:.*\n\n/s, '');
    details.value = prefix + current;
  }

  function renderServices(filterText=''){
    if (!servicesData) return;
    servicesBox.innerHTML = '';
    const ft = filterText.trim().toLowerCase();

    for (const cat of servicesData){
      const catWrap = document.createElement('div');
      catWrap.className = 'card';
      catWrap.innerHTML = `<h2>${cat.name}</h2><div class="service-wrap"></div>`;
      const wrap = catWrap.querySelector('.service-wrap');

      const filtered = cat.services.filter(s => !ft || (s.name.toLowerCase().includes(ft) || cat.name.toLowerCase().includes(ft)));
      if (!filtered.length) continue;

      for (const s of filtered){
        const pill = document.createElement('div');
        pill.className = 'service';
        pill.textContent = s.name;
        pill.dataset.id = s.id;
        pill.dataset.selected = selectedServiceIds.has(String(s.id)) ? '1' : '0';
        pill.addEventListener('click', ()=>{
          const id = String(s.id);
          if (selectedServiceIds.has(id)) selectedServiceIds.delete(id);
          else selectedServiceIds.add(id);
          pill.dataset.selected = selectedServiceIds.has(id) ? '1' : '0';
          updateDetailsFromServices();
          $('#service_ids').value = Array.from(selectedServiceIds).join(',');
        });
        wrap.appendChild(pill);
      }
      servicesBox.appendChild(catWrap);
    }
  }

  async function loadServices(){
    // simple localStorage cache (15 minutes)
    const key = 'ot_services_v1';
    const cached = localStorage.getItem(key);
    if (cached){
      try{
        const obj = JSON.parse(cached);
        if (obj && obj.exp && Date.now() < obj.exp && obj.data){
          servicesData = obj.data;
          renderServices('');
          return;
        }
      }catch(e){}
    }
    const res = await fetch('/api/services.php', {headers:{'Accept':'application/json'}});
    const data = await res.json();
    servicesData = data.categories || [];
    localStorage.setItem(key, JSON.stringify({exp: Date.now() + 15*60*1000, data: servicesData}));
    renderServices('');
  }

  async function loadAvailability(){
    // Only when zoom
    if (contactType.value !== 'zoom') return;
    availabilityBox.style.display = '';
    specialInfo.textContent = 'Checking availability…';
    slotsSelect.innerHTML = '';

    const start = preferredDate.value;
    if (!start){
      specialInfo.textContent = 'Pick a date to see available times.';
      return;
    }
    // Pull 7 days starting from selected date
    const url = new URL('/api/availability.php', window.location.origin);
    url.searchParams.set('start', start);
    url.searchParams.set('days', '7');

    const res = await fetch(url.toString(), {headers:{'Accept':'application/json'}});
    const data = await res.json();
    const days = data.days || [];

    let total = 0;
    for (const d of days){
      for (const s of (d.slots||[])){
        const opt = document.createElement('option');
        opt.value = s.value; // local time string
        opt.textContent = `${d.date} — ${s.label} (${s.timezone})`;
        slotsSelect.appendChild(opt);
        total++;
      }
    }

    if (total === 0){
      specialInfo.textContent = 'No available slots found in the next 7 days. You can still request a specific date/time below.';
    } else {
      specialInfo.textContent = 'Select an available slot, or request a specific date/time if you need something else.';
    }
  }

  contactType.addEventListener('change', ()=>{
    setZoomVisibility();
  });

  servicesFilter.addEventListener('input', debounce((e)=>{
    renderServices(e.target.value);
  }, 80));

  preferredDate.addEventListener('change', loadAvailability);

  // Initial
  setZoomVisibility();
  await loadServices();

  // For fast first paint, load availability only if they switch to Zoom and pick a date
  // but if zoom is default and date exists, load:
  if (contactType.value === 'zoom' && preferredDate.value) loadAvailability();

})();
