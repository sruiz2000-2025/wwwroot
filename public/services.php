<?php
// services.php (split header/footer)
?>
<?php include __DIR__ . "/partials/header.php"; ?>




<div class="container">
  <div class="card">
    <div class="h1">Services</div>
    <p class="sub">Explore what we can take off your plate — organized by category for speed and clarity.</p>
  </div>

  <div class="hr"></div>

  <div class="row two">
    <div class="card">
      <h2>Virtual Assistant</h2>
      <div id="services_va" class="service-wrap"><span class="small">Loading…</span></div>
    </div>
    <div class="card">
      <h2>Other Services</h2>
      <div id="services_other" class="service-wrap"><span class="small">Loading…</span></div>
    </div>
  </div>

  <div class="hr"></div>

  <div class="card">
    <h2>Not sure what you need?</h2>
    <p class="sub">Tell us your workflow and your goals. We’ll recommend the right services and the right level of support.</p>
    <a class="btn primary" href="/contact">Contact us</a>
  </div>
</div>


<script>
(async function(){
  const res = await fetch('/api/services.php', {headers:{'Accept':'application/json'}});
  const data = await res.json();
  const cats = data.categories || [];
  const map = new Map(cats.map(c=>[c.name, c.services||[]]));
  function render(id, arr){
    const el = document.getElementById(id);
    el.innerHTML='';
    for(const s of arr){
      const d=document.createElement('div'); d.className='service'; d.textContent=s.name; d.dataset.selected='0';
      el.appendChild(d);
    }
    if(!arr.length) el.innerHTML='<span class="small">No services found.</span>';
  }
  render('services_va', map.get('Virtual Assistant') || []);
  render('services_other', map.get('Other Services') || []);
})();
</script>


<?php include __DIR__ . "/partials/footer_shared.php"; ?>
