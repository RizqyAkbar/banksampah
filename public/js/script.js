/* ════════════════════════════════
    1. SCROLL PROGRESS BAR
════════════════════════════════ */
const bar = document.getElementById('progress-bar');
window.addEventListener('scroll', () => {
  const max = document.documentElement.scrollHeight - window.innerHeight;
  bar.style.width = (window.scrollY / max * 100) + '%';
}, { passive: true });

/* ════════════════════════════════
    2. NAV: shrink on scroll + active link
════════════════════════════════ */
const nav = document.querySelector('nav');
const sections = document.querySelectorAll('section[id], div[id]');
const navLinks = document.querySelectorAll('.nav-links a');

window.addEventListener('scroll', () => {
  nav.classList.toggle('scrolled', window.scrollY > 40);

  // active link highlight
  let current = '';
  sections.forEach(s => {
    if (window.scrollY >= s.offsetTop - 120) current = s.id;
  });
  navLinks.forEach(a => {
    a.classList.toggle('active', a.getAttribute('href') === '#' + current);
  });
}, { passive: true });

/* ════════════════════════════════
    3. INTERSECTION OBSERVER: data-reveal & data-stagger
════════════════════════════════ */
const revealObs = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (!e.isIntersecting) return;
    const delay = e.target.dataset.delay || 0;
    setTimeout(() => e.target.classList.add('revealed'), delay * 1000);
    revealObs.unobserve(e.target);
  });
}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('[data-reveal], [data-stagger]').forEach(el => revealObs.observe(el));

// old .fade-in compat
const fadeObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visible'); fadeObs.unobserve(e.target); }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.fade-in').forEach(el => fadeObs.observe(el));

/* ════════════════════════════════
    4. COUNTER ANIMATION
════════════════════════════════ */
function animateCounter(el) {
  const target  = parseFloat(el.dataset.target);
  const decimal = parseInt(el.dataset.decimal || 0);
  const suffix  = el.dataset.suffix || '';
  const dur     = 1600;
  const start   = performance.now();

  function step(now) {
    const p = Math.min((now - start) / dur, 1);
    const ease = 1 - Math.pow(1 - p, 4);
    const val = (target * ease).toFixed(decimal);
    el.textContent = val + suffix;
    if (p < 1) requestAnimationFrame(step);
  }
  requestAnimationFrame(step);
}

const counterObs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      animateCounter(e.target);
      counterObs.unobserve(e.target);
    }
  });
}, { threshold: 0.5 });
document.querySelectorAll('.stat-count').forEach(el => counterObs.observe(el));

/* ════════════════════════════════
    5. MAGNETIC BUTTON EFFECT
════════════════════════════════ */
document.querySelectorAll('.btn-primary, .btn-cta, .btn-wa').forEach(btn => {
  btn.addEventListener('mousemove', e => {
    const r = btn.getBoundingClientRect();
    const x = e.clientX - r.left - r.width / 2;
    const y = e.clientY - r.top  - r.height / 2;
    btn.style.transform = `translate(${x * 0.18}px, ${y * 0.22}px)`;
  });
  btn.addEventListener('mouseleave', () => {
    btn.style.transform = '';
  });
});

/* ════════════════════════════════
    6. RIPPLE ON CLICK
════════════════════════════════ */
document.querySelectorAll('.btn-primary, .btn-cta, .btn-wa, .btn-price').forEach(btn => {
  btn.addEventListener('click', function(e) {
    const r = this.getBoundingClientRect();
    const ripple = document.createElement('span');
    ripple.style.cssText = `
      position:absolute; border-radius:50%;
      width:10px; height:10px;
      background:rgba(255,255,255,0.5);
      transform:translate(-50%,-50%) scale(0);
      animation:rippleAnim 0.6s ease-out forwards;
      left:${e.clientX - r.left}px; top:${e.clientY - r.top}px;
      pointer-events:none;
    `;
    this.appendChild(ripple);
    setTimeout(() => ripple.remove(), 700);
  });
});

/* ════════════════════════════════
    7. SERVICE CARD TILT
════════════════════════════════ */
document.querySelectorAll('.service-card, .testi-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const r = card.getBoundingClientRect();
    const x = (e.clientX - r.left) / r.width  - 0.5;
    const y = (e.clientY - r.top)  / r.height - 0.5;
    card.style.transform = `translateY(-4px) rotateX(${-y * 6}deg) rotateY(${x * 6}deg)`;
    card.style.transition = 'transform 0.1s ease';
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = '';
    card.style.transition = 'all 0.4s ease';
  });
});