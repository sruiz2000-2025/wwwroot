<?php
// index.php (split header/footer)
?>

<?php include __DIR__ . "/partials/header.php"; ?>

<!-- HERO SECTION (full width, outside main container) -->
<section class="lux-hero">
  <div class="container">
    <div class="lux-hero-shell">
      <div>
        <div class="lux-eyebrow"><span></span>White-glove virtual support</div>
        <h1 class="lux-title">Luxury-level execution for founders who expect <em>precision</em>.</h1>
        <p class="lux-sub">Ongoingteam pairs you with premium virtual assistants who operate like an extension of your leadership team. We handle calendar orchestration, client follow-ups, market research, and operational support with discreet, deadline-driven focus.</p>
        <div class="lux-actions">
          <a class="btn primary" href="/contact#book">Book a Private Consult</a>
          <a class="btn" href="/services">View Signature Services</a>
          <a class="btn" href="/prices">Explore Pricing</a>
        </div>
      </div>
      <div class="lux-card" style="padding:0; overflow:hidden; border:none;">
        <figure style="margin:0; position:relative;">
          <img src="/img/business/business_image_01.jpg" alt="Operations in action" style="width:100%; display:block; height:100%; object-fit:cover; max-height:400px;">
          <figcaption style="position:absolute; inset:0; background:linear-gradient(180deg, rgba(0,0,0,.2), rgba(0,0,0,.65)); color:#f5f8ff; padding:18px; display:flex; flex-direction:column; justify-content:flex-end; gap:8px;">
            <h3 style="margin:0;">Concierge-level delivery</h3>
            <p class="lux-sub" style="color:rgba(232,236,242,.82); margin:0;">Your dedicated VA manages the details while you stay centered on portfolio growth, client relationships, and strategic decisions.</p>
            <div class="lux-metrics" style="background:transparent; border:none; padding:0; box-shadow:none;">
              <div class="metric" style="background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.15);">
                <strong>20-40 hrs</strong>
                <span>Monthly time reclaimed</span>
              </div>
              <div class="metric" style="background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.15);">
                <strong>48 hrs</strong>
                <span>Average onboarding time</span>
              </div>
              <div class="metric" style="background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.15);">
                <strong>90%</strong>
                <span>Tasks handled within SLA</span>
              </div>
              <div class="metric" style="background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.15);">
                <strong>5-star</strong>
                <span>Client satisfaction focus</span>
              </div>
            </div>
          </figcaption>
        </figure>
      </div>
    </div>
  </div>
</section>

<!-- PROCESS SECTION -->
<section class="lux-process" id="process">
  <div class="container">
    <div class="lux-process-shell">
      <div>
        <div class="lux-eyebrow"><span></span>Streamlined onboarding</div>
        <h2 class="section-title">Getting a quality virtual assistant doesn’t have to be difficult.</h2>
        <p class="section-sub">Discovery, matching, and onboarding are handled with our white-glove playbook so you stay focused on outcomes, not admin.</p>
        <div class="process-steps">
          <div class="process-step active" data-step="1" aria-controls="process-slide-1">
            <div class="step-number">1.</div>
            <div>
              <div class="step-title">Discovery</div>
              <div class="step-sub">We capture priorities, brand voice, tools, and weekly rhythms so we can mirror how you work.</div>
            </div>
          </div>
          <div class="process-step" data-step="2" aria-controls="process-slide-2">
            <div class="step-number">2.</div>
            <div>
              <div class="step-title">Matching</div>
              <div class="step-sub">We shortlist and test candidates, align on SLAs, and present a dedicated VA with backups prepped.</div>
            </div>
          </div>
          <div class="process-step" data-step="3" aria-controls="process-slide-3">
            <div class="step-number">3.</div>
            <div>
              <div class="step-title">Onboarding</div>
              <div class="step-sub">Access, SOPs, and daily cadences go live; you get weekly snapshots on wins, risks, and next moves.</div>
            </div>
          </div>
        </div>
        <div class="lux-actions">
          <a class="btn primary" href="/contact#book">Start Your Intake</a>
          <a class="btn" href="/services">See How We Work</a>
        </div>
      </div>
      <div class="process-visual">
        <div class="process-slide is-visible" data-step="1" id="process-slide-1">
          <div class="device">
            <div class="device-header">
              <span>Discovery Desk</span>
              <div class="device-pills">
                <span class="pill-dot"></span>
                <span class="pill-dot"></span>
                <span class="pill-dot"></span>
              </div>
            </div>
            <div class="process-task">
              <div class="step-title">Intake & priorities</div>
              <div class="step-sub">We map your inbox rules, client tiers, and calendars within 24-48 hours.</div>
              <div class="status"><span class="status-dot done"></span>Done</div>
            </div>
            <div class="process-task">
              <div class="step-title">SOP drafting</div>
              <div class="step-sub">Rapid SOPs for how you want outreach, scheduling, and updates handled.</div>
              <div class="status"><span class="status-dot progress"></span>In progress</div>
            </div>
          </div>
          <img src="/img/process-discovery.svg" alt="Discovery workflows">
        </div>
        <div class="process-slide" data-step="2" id="process-slide-2">
          <div class="device">
            <div class="device-header">
              <span>Matching Board</span>
              <div class="device-pills">
                <span class="pill-dot"></span>
                <span class="pill-dot"></span>
                <span class="pill-dot"></span>
              </div>
            </div>
            <div class="process-task">
              <div class="step-title">Shortlist & QA</div>
              <div class="step-sub">We test for tone, speed, and tools, then present your top pick with backups.</div>
              <div class="status"><span class="status-dot done"></span>Done</div>
            </div>
            <div class="process-task">
              <div class="step-title">Alignment session</div>
              <div class="step-sub">30-minute review of SLAs, KPIs, and escalation paths.</div>
              <div class="status"><span class="status-dot progress"></span>In progress</div>
            </div>
          </div>
          <img src="/img/process-matching.svg" alt="Matching the right assistant">
        </div>
        <div class="process-slide" data-step="3" id="process-slide-3">
          <div class="device">
            <div class="device-header">
              <span>Onboarding Flow</span>
              <div class="device-pills">
                <span class="pill-dot"></span>
                <span class="pill-dot"></span>
                <span class="pill-dot"></span>
              </div>
            </div>
            <div class="process-task">
              <div class="step-title">Live cadences</div>
              <div class="step-sub">Daily check-ins, weekly snapshots, and risk/issue surfacing.</div>
              <div class="status"><span class="status-dot done"></span>Live</div>
            </div>
            <div class="process-task">
              <div class="step-title">Task coverage</div>
              <div class="step-sub">Inbox, calendar, follow-ups, and property updates handled with receipts.</div>
              <div class="status"><span class="status-dot"></span>Done</div>
            </div>
          </div>
          <img src="/img/process-onboarding.svg" alt="Onboarding into your workflows">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VISUAL GALLERY (full width) -->
<section class="lux-section">
  <div class="container">
    <h2 class="section-title">Where we operate</h2>
    <p class="section-sub">A glimpse at the operations we support: property ops, executive desks, creative production, and client delivery.</p>
    <div class="lux-gallery" aria-hidden="true">
      <figure class="hero"><img src="/img/business/business_image_02.jpg" alt=""></figure>
      <figure class="tall"><img src="/img/business/business_image_03.jpg" alt=""></figure>
      <figure><img src="/img/business/business_image_04.jpg" alt=""></figure>
      <figure><img src="/img/business/business_image_05.jpg" alt=""></figure>
      <figure><img src="/img/business/business_image_06.jpg" alt=""></figure>
      <figure><img src="/img/business/business_image_07.jpg" alt=""></figure>
      <figure class="wide"><img src="/img/business/business_image_08.jpg" alt=""></figure>
      <figure><img src="/img/business/business_image_09.jpg" alt=""></figure>
      <figure><img src="/img/business/business_image_10.jpg" alt=""></figure>
      <figure><img src="/img/business/business_image_11.jpg" alt=""></figure>
    </div>
  </div>
</section>

<!-- MAIN CONTENT -->
<div class="container">
  <div class="grid two">
    <div class="card">
      <div class="h1">Premium Virtual Assistants. Real outcomes.</div>
      <p class="sub">Ongoingteam delivers premium virtual assistant support designed for busy founders, real estate operators, and growing teams who need execution they can trust. We handle the repetitive work, the follow-ups, the scheduling, and the details so you stay focused on revenue, clients, and growth. Expect clear communication, structured workflows, and deadline-driven delivery that feels like an in-house team without the overhead.</p>
      <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:14px;">
        <a class="btn primary" href="/contact#book">Book a Meeting</a>
        <a class="btn" href="/services">Explore Services</a>
        <a class="btn" href="/prices">Pricing Approach</a>
      </div>
    </div>
    <div class="card">
      <h2>Why teams hire a VA</h2>
      <div class="hr"></div>
      <div class="grid" style="gap:10px;">
        <div class="pill">Time back: reclaim 20-40+ hours/month</div>
        <div class="pill">Cost-smart: often cheaper than hiring in-house</div>
        <div class="pill">On time: we adjust hours to hit deadlines</div>
        <div class="pill">Consistent: reliable processes, not random freelancers</div>
      </div>
    </div>
  </div>
</div>

<!-- SIGNATURE SUPPORT SECTION (full width) -->
<section class="lux-section">
  <div class="container">
    <h2 class="section-title">Signature support, curated around your brand</h2>
    <p class="section-sub">We build a structured support system that mirrors your standards. Luxury real estate teams, founders, and growth operators trust us to keep every touchpoint impeccable.</p>
    <div class="lux-split">
      <div class="lux-panel">
        <h3>What we oversee</h3>
        <ul class="lux-list">
          <li>Inbox and calendar architecture with concierge response protocols.</li>
          <li>Client follow-up sequences, proposal delivery, and scheduling.</li>
          <li>Market research, list building, and executive-ready summaries.</li>
          <li>CRM hygiene, reporting, and operational coordination.</li>
        </ul>
      </div>
      <div class="lux-panel dark">
        <h3>Why luxury teams stay</h3>
        <p>Discretion, responsiveness, and alignment. We embed into your workflow with proactive updates, clear KPIs, and elegant communication that protects your brand.</p>
        <ul class="lux-list">
          <li>Dedicated account lead and documented playbooks.</li>
          <li>Weekly performance snapshots and priority reviews.</li>
          <li>Flexible hours that scale with your calendar.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- CLIENT IMPRESSIONS SECTION (full width) -->
<section class="lux-section" style="padding-top:0;">
  <div class="container">
    <h2 class="section-title">Client impressions</h2>
    <p class="section-sub">Luxury is consistency. Our clients describe the experience as having a calm, meticulous executive assistant in their corner.</p>
    <div class="lux-quote-grid">
      <div class="lux-quote">
        <p>"Every client touchpoint feels elevated. The follow-up cadence is flawless."</p>
        <span>Principal Broker, Luxury Real Estate</span>
      </div>
      <div class="lux-quote">
        <p>"They anticipate what I need before I ask. My calendar finally feels curated."</p>
        <span>Founder, Private Equity Operations</span>
      </div>
      <div class="lux-quote">
        <p>"Polished, responsive, and on brand. The team handles the details with ease."</p>
        <span>COO, Hospitality Portfolio</span>
      </div>
    </div>
  </div>
</section>

<!-- FAQ SECTION -->
<section class="lux-section lux-faq" id="faq">
  <div class="container">
    <h2 class="section-title">FAQs built around our signature services</h2>
    <p class="section-sub">Based on what clients book most: real estate admin, Airbnb/short-term rental ops, inbox/calendar orchestration, lead intake, creative support, and documented workflows.</p>
    <div class="faq-grid">
      <div class="faq-card" data-faq-open="0">
        <h3>How do you handle real estate admin and transaction support?</h3>
        <p>We manage listings, offer packets, disclosures, and client updates, and keep your CRM clean with weekly hygiene so every buyer/seller touchpoint is on-brand.</p>
      </div>
      <div class="faq-card" data-faq-open="1">
        <h3>What does “Airbnb / short-term rental operations” include?</h3>
        <p>Guest messaging, calendar sync, cleaner/vendor coordination, review responses, and KPI snapshots (occupancy, ADR, issues) so you see the health of each door.</p>
      </div>
      <div class="faq-card" data-faq-open="2">
        <h3>How do you manage inboxes and calendars without bottlenecks?</h3>
        <p>We set routing rules, VIP/SLA tagging, templated replies, and time-blocked calendars. You get a daily “decide/route/delegate” digest to stay ahead.</p>
      </div>
      <div class="faq-card" data-faq-open="3">
        <h3>Can you cover lead intake and client follow-ups?</h3>
        <p>Yes—scripted intake, qualification, call/meeting scheduling, proposal sends, and warm follow-up sequences tuned to your tone and pace.</p>
      </div>
      <div class="faq-card" data-faq-open="4">
        <h3>Do you offer creative help like social media and video edits?</h3>
        <p>We can draft captions, schedule posts, clip reels, trim videos, and package assets, then report what performed so you can double down.</p>
      </div>
      <div class="faq-card" data-faq-open="5">
        <h3>How do you document processes and data entry work?</h3>
        <p>We write step-by-step SOPs, keep them versioned, and run QA spot checks on data entry so every workflow is repeatable and auditable.</p>
      </div>
      <div class="faq-card" data-faq-open="6">
        <h3>What if I need a custom workflow or new tool setup?</h3>
        <p>We can scope, prototype, and own a custom playbook—CRMs, project boards, automations—then train your team and keep it maintained.</p>
      </div>
      <div class="faq-card" data-faq-open="7">
        <h3>How quickly can onboarding start?</h3>
        <p>Most clients start within 48 hours. Day 1: standards and access. Week 1: routing rules and first deliverables. Ongoing: weekly performance snapshots.</p>
      </div>
    </div>
  </div>
</section>

<!-- FAQ OVERLAY -->
<div class="faq-overlay" id="faqOverlay" aria-hidden="true">
  <div class="faq-overlay-backdrop" data-faq-close></div>
  <div class="faq-modal" role="dialog" aria-modal="true" aria-labelledby="faqModalTitle">
    <div class="faq-modal-header">
      <div>
        <div class="lux-eyebrow"><span></span>FAQs</div>
        <h3 class="section-title" id="faqModalTitle">Details clients ask before starting</h3>
        <p class="section-sub">A rapid tour through how we work, what’s included, and how we keep communication crisp.</p>
      </div>
      <button class="btn" type="button" data-faq-close aria-label="Close FAQ">Close</button>
    </div>
    <div class="faq-carousel">
      <button class="faq-nav prev" type="button" aria-label="Previous FAQ" data-faq-prev>‹</button>
      <div class="faq-slide is-visible" data-faq-id="0">
        <div class="faq-meta">Real estate • Playbook depth</div>
        <h4>How do you handle real estate admin and transaction support?</h4>
        <p class="faq-lede">Think of it like a transaction desk that never drops a thread: listings, offers, disclosures, status updates, and CRM hygiene—always current.</p>
        <ul class="faq-detail">
          <li>Offer packets, disclosures, client touchpoints, and weekly CRM hygiene.</li>
          <li>Coordination with TC/attorney/lender plus status digests for your sellers and buyers.</li>
          <li>Receipts for every milestone so you don’t hunt for updates.</li>
        </ul>
        <div class="faq-links">
          <a href="/contact">Book a real estate setup</a>
          <a href="/services">See admin services</a>
        </div>
      </div>
      <div class="faq-slide" data-faq-id="1">
        <div class="faq-meta">STR / Airbnb • Operations</div>
        <h4>What’s included for Airbnb / STR operations?</h4>
        <p class="faq-lede">Daily guest comms, cleaner/vendor handoffs, and a pulse on each door’s occupancy, ADR, and exceptions.</p>
        <ul class="faq-detail">
          <li>24/7 guest messaging with SLA tags and templated replies in your voice.</li>
          <li>Cleaner/vendor coordination with photos, receipts, and issue tracking.</li>
          <li>Weekly KPIs: occupancy, ADR, reviews, and exception callouts.</li>
        </ul>
        <div class="faq-links">
          <a href="/contact">Ask about a property</a>
        </div>
      </div>
      <div class="faq-slide" data-faq-id="2">
        <div class="faq-meta">Inbox & Calendar • Control</div>
        <h4>How do you manage inboxes and calendars?</h4>
        <p class="faq-lede">We turn the inbox into a control center: VIP routing, SLA tags, templated replies, and calendars with breathing room.</p>
        <ul class="faq-detail">
          <li>Rules for VIPs, SLAs, and quiet hours with auto-filing and receipts.</li>
          <li>Daily “decide/route/delegate” digest to keep you ahead.</li>
          <li>Calendar holds, prep notes, and buffers so meetings run cleanly.</li>
        </ul>
        <div class="faq-links">
          <a href="/contact">Tune my inbox</a>
        </div>
      </div>
      <div class="faq-slide" data-faq-id="3">
        <div class="faq-meta">Pipeline • Follow-through</div>
        <h4>Do you handle lead intake and follow-ups?</h4>
        <p class="faq-lede">Scripted intake, qualification, scheduling, and follow-up loops that don’t stall—always in your tone.</p>
        <ul class="faq-detail">
          <li>Qualification and next-step scheduling with proposals and reminders.</li>
          <li>Warm follow-up sequences that feel personal, not canned.</li>
          <li>Receipts in CRM so every touch is logged and visible.</li>
        </ul>
        <div class="faq-links">
          <a href="/contact">Share your follow-up flow</a>
        </div>
      </div>
      <div class="faq-slide" data-faq-id="4">
        <div class="faq-meta">Creative • Social & video</div>
        <h4>Do you offer creative help like social media and video edits?</h4>
        <p class="faq-lede">Yes. We package social and video tasks into repeatable sprints with clear approvals.</p>
        <ul class="faq-detail">
          <li>Captions, scheduling, and reel trims/clips with approval checklists.</li>
          <li>Asset organization so future posts pull from a ready library.</li>
          <li>Performance notes to double down on what resonates.</li>
        </ul>
        <div class="faq-links">
          <a href="/services">See creative support</a>
        </div>
      </div>
      <div class="faq-slide" data-faq-id="5">
        <div class="faq-meta">SOPs • Data hygiene</div>
        <h4>How do you document processes and data entry work?</h4>
        <p class="faq-lede">Every recurring task gets an owner, an SOP, and QA checks so it’s repeatable and auditable.</p>
        <ul class="faq-detail">
          <li>Step-by-step SOPs with screenshots and acceptance criteria.</li>
          <li>Versioning and scheduled QA spot checks on data entry.</li>
          <li>Exception reporting when something drifts off spec.</li>
        </ul>
        <div class="faq-links">
          <a href="/contact">Document my workflow</a>
        </div>
      </div>
      <div class="faq-slide" data-faq-id="6">
        <div class="faq-meta">Custom • Workflows & tools</div>
        <h4>Can you customize workflows and tools?</h4>
        <p class="faq-lede">We scope, build, and maintain playbooks—CRMs, boards, automations—then train your team.</p>
        <ul class="faq-detail">
          <li>Architecture recommendations and rapid prototyping.</li>
          <li>QA, documentation, and ownership rules per workflow.</li>
          <li>Live training with quick-reference guides.</li>
        </ul>
        <div class="faq-links">
          <a href="/contact">Start a workflow scope</a>
        </div>
      </div>
      <div class="faq-slide" data-faq-id="7">
        <div class="faq-meta">Onboarding • Speed</div>
        <h4>How quickly can onboarding start?</h4>
        <p class="faq-lede">Most clients go live inside a week. Access and standards first, then daily delivery with weekly snapshots.</p>
        <ul class="faq-detail">
          <li>Day 1-2: access, brand voice, and tool audit.</li>
          <li>Day 3-5: first deliverables + daily check-ins.</li>
          <li>Week 1: snapshot on wins, risks, and next moves.</li>
        </ul>
        <div class="faq-links">
          <a href="/contact">Book onboarding</a>
        </div>
      </div>
      <button class="faq-nav next" type="button" aria-label="Next FAQ" data-faq-next>›</button>
    </div>
    <div class="faq-dots" id="faqDots" aria-label="FAQ navigation"></div>
  </div>
</div>

<!-- CTA SECTION (full width) -->
<section class="lux-section">
  <div class="container">
    <div class="lux-cta">
      <h2 class="section-title">Reserve your white-glove onboarding</h2>
      <p>We begin with a private consult to align on standards, workflows, and priorities. You receive a dedicated VA, a luxury-grade onboarding checklist, and a clear weekly rhythm.</p>
      <div class="lux-actions">
        <a class="btn primary" href="/contact#book">Schedule the Consult</a>
        <a class="btn" href="/contact">Ask a Question</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . "/partials/footer_shared.php"; ?>
