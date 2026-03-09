<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buang Sampah Jogja — Jasa Angkut Sampah</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,600&family=Nunito:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --green: #00C264;
    --green-dark: #009B4E;
    --green-light: #E8FFF3;
    --orange: #FF5C00;
    --black: #0A0A0A;
    --gray-900: #111111;
    --gray-700: #444444;
    --gray-400: #999999;
    --gray-100: #F5F5F5;
    --white: #FFFFFF;
    --radius: 20px;
    --radius-lg: 32px;
  }

  html { scroll-behavior: smooth; }

  body {
    font-family: 'Nunito', sans-serif;
    background: var(--white);
    color: var(--black);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
  }

  h1, h2, h3, h4, h5 {
    font-family: 'Urbanist', sans-serif;
    line-height: 1.1;
  }

  a { text-decoration: none; color: inherit; }

  /* ─── NAV ─── */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0,0,0,0.06);
  }

  .nav-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 32px;
    height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .logo {
    font-family: 'Urbanist', sans-serif;
    font-weight: 800;
    font-size: 20px;
    letter-spacing: -0.5px;
  }
  .logo span { color: var(--green); }

  .nav-links {
    display: flex;
    gap: 40px;
    list-style: none;
  }
  .nav-links a {
    font-size: 13px;
    font-weight: 500;
    color: var(--gray-700);
    letter-spacing: 0.02em;
    transition: color 0.2s;
  }
  .nav-links a:hover { color: var(--green); }

  .btn-wa {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--green);
    color: white;
    padding: 10px 22px;
    border-radius: 100px;
    font-size: 13px;
    font-weight: 600;
    transition: background 0.2s, transform 0.15s;
  }
  .btn-wa:hover { background: var(--green-dark); transform: translateY(-1px); }

  /* ─── HERO ─── */
  .hero {
    padding-top: 68px;
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding-left: 32px;
    padding-right: 32px;
    gap: 60px;
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--green-light);
    color: var(--green-dark);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 6px 14px;
    border-radius: 100px;
    margin-bottom: 28px;
  }
  .hero-badge-dot {
    width: 7px; height: 7px;
    background: var(--green);
    border-radius: 50%;
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.3); }
  }

  .hero h1 {
    font-size: clamp(40px, 5vw, 64px);
    font-weight: 800;
    color: var(--black);
    letter-spacing: -1.5px;
    margin-bottom: 20px;
  }
  .hero h1 em {
    font-style: normal;
    color: var(--green);
  }

  .hero p {
    font-size: 16px;
    color: var(--gray-700);
    font-weight: 300;
    line-height: 1.75;
    max-width: 420px;
    margin-bottom: 40px;
  }

  .hero-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--orange);
    color: white;
    padding: 15px 30px;
    border-radius: var(--radius);
    font-size: 15px;
    font-weight: 600;
    font-family: 'Urbanist', sans-serif;
    transition: all 0.2s;
  }
  .btn-primary:hover { background: #e05000; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(255,92,0,0.25); }

  .btn-outline {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: var(--black);
    padding: 15px 28px;
    border-radius: var(--radius);
    font-size: 15px;
    font-weight: 500;
    border: 1.5px solid rgba(0,0,0,0.12);
    transition: all 0.2s;
  }
  .btn-outline:hover { border-color: var(--green); color: var(--green); }

  .hero-image {
    position: relative;
  }
  .hero-image img {
    width: 100%;
    height: 560px;
    object-fit: cover;
    border-radius: var(--radius-lg);
    display: block;
  }
  .hero-image::before {
    content: '';
    position: absolute;
    inset: -20px;
    background: var(--green-light);
    border-radius: 40px;
    z-index: -1;
  }

  /* stat chips */
  .hero-stats {
    display: flex;
    gap: 12px;
    margin-top: 44px;
    flex-wrap: wrap;
  }
  .stat-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--gray-100);
    border-radius: 100px;
    padding: 8px 18px 8px 8px;
  }
  .stat-icon {
    width: 32px; height: 32px;
    background: var(--green);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-size: 12px;
    flex-shrink: 0;
  }
  .stat-chip strong {
    font-family: 'Urbanist', sans-serif;
    font-size: 15px;
    font-weight: 700;
  }
  .stat-chip span {
    font-size: 11px;
    color: var(--gray-400);
    display: block;
  }

  /* ─── SECTION BASE ─── */
  section { padding: 100px 32px; }
  .container { max-width: 1200px; margin: 0 auto; }

  .section-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--green);
    margin-bottom: 14px;
  }
  .section-title {
    font-size: clamp(28px, 3.5vw, 44px);
    font-weight: 800;
    letter-spacing: -1px;
    color: var(--black);
    margin-bottom: 16px;
  }
  .section-sub {
    font-size: 15px;
    color: var(--gray-700);
    font-weight: 300;
    max-width: 480px;
  }

  /* ─── SERVICES ─── */
  #layanan { background: var(--gray-100); }

  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 56px;
    gap: 20px;
  }

  .services-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }

  .service-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 36px 28px;
    border: 1px solid transparent;
    transition: all 0.3s;
    cursor: default;
  }
  .service-card:hover {
    border-color: var(--green);
    box-shadow: 0 20px 60px rgba(0,194,100,0.08);
    transform: translateY(-4px);
  }

  .service-icon {
    width: 52px; height: 52px;
    background: var(--green-light);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: var(--green);
    font-size: 20px;
    margin-bottom: 24px;
  }
  .service-card h3 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 10px;
  }
  .service-card p {
    font-size: 13px;
    color: var(--gray-400);
    line-height: 1.7;
    font-weight: 300;
  }

  /* ─── WHY ─── */
  #keunggulan .container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
  }

  .why-features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .feature-item {
    background: var(--gray-100);
    border-radius: var(--radius);
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: background 0.25s;
  }
  .feature-item:hover { background: var(--green-light); }
  .feature-item i { color: var(--green); font-size: 18px; }
  .feature-item span {
    font-family: 'Urbanist', sans-serif;
    font-size: 15px;
    font-weight: 700;
  }

  /* ─── STEPS ─── */
  .steps-section {
    background: var(--black);
    border-radius: 48px;
    margin: 0 16px;
    padding: 80px 32px;
    overflow: hidden;
    position: relative;
  }
  .steps-section::after {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(0,194,100,0.15), transparent 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  .steps-section .section-title { color: var(--white); text-align: center; margin-bottom: 60px; }

  .steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    position: relative;
  }

  .step-item {
    text-align: center;
    padding: 28px 20px;
    border-radius: var(--radius);
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    transition: background 0.3s;
  }
  .step-item:hover { background: rgba(0,194,100,0.12); border-color: rgba(0,194,100,0.3); }

  .step-num {
    font-family: 'Urbanist', sans-serif;
    font-size: 11px;
    font-weight: 700;
    color: var(--green);
    letter-spacing: 0.1em;
    margin-bottom: 14px;
  }
  .step-circle {
    width: 52px; height: 52px;
    background: rgba(255,255,255,0.07);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
    font-size: 18px;
    color: rgba(255,255,255,0.7);
    transition: background 0.3s, color 0.3s;
  }
  .step-item:hover .step-circle { background: var(--green); color: white; }
  .step-item h4 {
    font-family: 'Urbanist', sans-serif;
    font-weight: 700;
    font-size: 15px;
    color: var(--white);
  }
  .step-item p {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    margin-top: 6px;
    font-weight: 300;
  }

  /* ─── PRICING ─── */
  #harga { background: var(--gray-100); }

  .pricing-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    max-width: 780px;
    margin: 56px auto 0;
  }

  .price-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 44px 36px;
    border: 1.5px solid rgba(0,0,0,0.06);
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
  }
  .price-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 24px 60px rgba(0,0,0,0.08);
  }

  .price-card.featured {
    background: var(--green);
    border-color: transparent;
    color: white;
  }

  .price-badge {
    position: absolute;
    top: 20px; right: -30px;
    background: var(--orange);
    color: white;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.06em;
    padding: 5px 40px;
    transform: rotate(45deg);
    transform-origin: center;
  }

  .vehicle-icon {
    width: 56px; height: 56px;
    background: var(--green-light);
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    color: var(--green);
    font-size: 22px;
    margin-bottom: 20px;
  }
  .price-card.featured .vehicle-icon {
    background: rgba(255,255,255,0.15);
    color: white;
  }
  .price-card h3 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 6px;
  }
  .price-card .subtitle {
    font-size: 12px;
    color: var(--gray-400);
    font-style: italic;
    margin-bottom: 28px;
  }
  .price-card.featured .subtitle { color: rgba(255,255,255,0.6); }

  .price-amount {
    font-family: 'Urbanist', sans-serif;
    font-size: 36px;
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 6px;
  }
  .price-per {
    font-size: 12px;
    color: var(--gray-400);
    margin-bottom: 32px;
  }
  .price-card.featured .price-per { color: rgba(255,255,255,0.6); }

  .price-features {
    list-style: none;
    margin-bottom: 32px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .price-features li {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 400;
    color: var(--gray-700);
  }
  .price-card.featured .price-features li { color: rgba(255,255,255,0.85); }
  .price-features li i { color: var(--green); font-size: 12px; }
  .price-card.featured .price-features li i { color: rgba(255,255,255,0.7); }

  .btn-price {
    display: block;
    text-align: center;
    padding: 14px;
    border-radius: var(--radius);
    font-size: 14px;
    font-weight: 600;
    font-family: 'Urbanist', sans-serif;
    transition: all 0.2s;
    border: 1.5px solid rgba(0,0,0,0.1);
    color: var(--black);
  }
  .btn-price:hover { background: var(--black); color: white; border-color: transparent; }
  .price-card.featured .btn-price {
    background: white;
    color: var(--green-dark);
    border-color: transparent;
  }
  .price-card.featured .btn-price:hover { background: var(--black); color: white; }

  /* ─── TESTIMONIALS ─── */
  .testi-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 56px;
  }
  .testi-card {
    background: var(--gray-100);
    border-radius: var(--radius-lg);
    padding: 36px;
    border: 1px solid transparent;
    transition: all 0.25s;
  }
  .testi-card:hover { border-color: var(--green); background: var(--white); box-shadow: 0 16px 48px rgba(0,0,0,0.05); }

  .stars { color: var(--orange); font-size: 13px; margin-bottom: 20px; display:flex; gap:3px; }
  .testi-card blockquote {
    font-size: 15px;
    font-style: italic;
    font-weight: 300;
    color: var(--gray-700);
    line-height: 1.8;
    margin-bottom: 24px;
  }
  .testi-author {
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .testi-avatar {
    width: 38px; height: 38px;
    background: var(--green-light);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--green);
    font-weight: 700;
    font-size: 14px;
    font-family: 'Urbanist', sans-serif;
  }
  .testi-name { font-weight: 600; font-size: 14px; }
  .testi-loc { font-size: 12px; color: var(--gray-400); }

  /* ─── CTA ─── */
  .cta-section {
    background: var(--orange);
    border-radius: 48px;
    margin: 0 16px 80px;
    padding: 80px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
  }
  .cta-section::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 320px; height: 320px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
  }
  .cta-section::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 240px; height: 240px;
    background: rgba(0,0,0,0.08);
    border-radius: 50%;
  }
  .cta-section h2 {
    color: var(--white);
    font-size: clamp(26px, 4vw, 48px);
    font-weight: 800;
    letter-spacing: -1px;
    margin-bottom: 12px;
    position: relative; z-index: 1;
  }
  .cta-section p {
    color: rgba(255,255,255,0.75);
    font-weight: 300;
    margin-bottom: 40px;
    position: relative; z-index: 1;
  }
  .btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: var(--black);
    color: white;
    padding: 16px 36px;
    border-radius: var(--radius);
    font-size: 16px;
    font-weight: 700;
    font-family: 'Urbanist', sans-serif;
    transition: all 0.2s;
    position: relative; z-index: 1;
  }
  .btn-cta:hover { background: var(--white); color: var(--black); transform: translateY(-2px); box-shadow: 0 16px 40px rgba(0,0,0,0.2); }

  /* ─── FOOTER ─── */
  footer {
    background: var(--gray-100);
    border-top: 1px solid rgba(0,0,0,0.06);
    padding: 60px 32px 32px;
  }
  .footer-inner {
    max-width: 1200px;
    margin: 0 auto;
  }
  .footer-top {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr;
    gap: 60px;
    margin-bottom: 48px;
  }
  .footer-logo {
    font-family: 'Urbanist', sans-serif;
    font-size: 22px;
    font-weight: 800;
    margin-bottom: 16px;
  }
  .footer-logo span { color: var(--green); }
  .footer-desc {
    font-size: 13px;
    color: var(--gray-400);
    line-height: 1.8;
    font-weight: 300;
  }
  .footer-heading {
    font-family: 'Urbanist', sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--black);
    margin-bottom: 20px;
  }
  .footer-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .footer-list li {
    font-size: 13px;
    color: var(--gray-700);
    font-weight: 300;
  }
  .social-links { display: flex; gap: 8px; }
  .social-btn {
    width: 40px; height: 40px;
    background: var(--white);
    border: 1px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: var(--gray-700);
    font-size: 15px;
    transition: all 0.2s;
  }
  .social-btn:hover { background: var(--green); color: white; border-color: transparent; }

  .footer-bottom {
    border-top: 1px solid rgba(0,0,0,0.06);
    padding-top: 24px;
    text-align: center;
    font-size: 11px;
    color: var(--gray-400);
    letter-spacing: 0.06em;
    text-transform: uppercase;
    font-weight: 500;
  }

  /* ─── RESPONSIVE ─── */
  @media (max-width: 1024px) {
    .hero {
      grid-template-columns: 1fr;
      padding-top: 120px;
      padding-bottom: 60px;
      gap: 40px;
      min-height: auto;
    }
    .hero-image { display: none; }
    .services-grid { grid-template-columns: 1fr 1fr; }
    #keunggulan .container { grid-template-columns: 1fr; gap: 40px; }
    .steps-grid { grid-template-columns: 1fr 1fr; }
    .footer-top { grid-template-columns: 1fr 1fr; }
  }

  @media (max-width: 640px) {
    section { padding: 60px 20px; }
    .nav-inner { padding: 0 20px; }
    .nav-links { display: none; }
    .hero { padding-left: 20px; padding-right: 20px; }
    .hero h1 { font-size: 36px; }
    .services-grid { grid-template-columns: 1fr; }
    .steps-grid { grid-template-columns: 1fr 1fr; }
    .pricing-grid { grid-template-columns: 1fr; }
    .testi-grid { grid-template-columns: 1fr; }
    .footer-top { grid-template-columns: 1fr; gap: 32px; }
    .cta-section { padding: 60px 24px; }
    .steps-section { margin: 0; border-radius: 0; }
    .cta-section { margin: 0 0 40px; border-radius: 0; }
    .hero-stats { display: none; }
  }

  /* ═══════════════════════════════════
     ANIMATION SYSTEM
  ═══════════════════════════════════ */

  /* — Scroll reveal base — */
  [data-reveal] {
    opacity: 0;
    transition-timing-function: cubic-bezier(0.16, 1, 0.3, 1);
    transition-property: opacity, transform, filter;
    will-change: opacity, transform;
  }
  [data-reveal="fade"]      { transform: translateY(36px); transition-duration: 0.7s; }
  [data-reveal="fade-up"]   { transform: translateY(56px); transition-duration: 0.75s; }
  [data-reveal="fade-left"] { transform: translateX(-56px); transition-duration: 0.7s; }
  [data-reveal="fade-right"]{ transform: translateX(56px); transition-duration: 0.7s; }
  [data-reveal="scale"]     { transform: scale(0.88); transition-duration: 0.65s; }
  [data-reveal="blur"]      { transform: translateY(20px); filter: blur(8px); transition-duration: 0.7s; }
  [data-reveal].revealed    { opacity: 1; transform: none; filter: none; }

  /* stagger children */
  [data-stagger] > * {
    opacity: 0;
    transform: translateY(32px);
    transition: opacity 0.55s cubic-bezier(0.16,1,0.3,1), transform 0.55s cubic-bezier(0.16,1,0.3,1);
  }
  [data-stagger].revealed > *:nth-child(1) { transition-delay: 0s; }
  [data-stagger].revealed > *:nth-child(2) { transition-delay: 0.1s; }
  [data-stagger].revealed > *:nth-child(3) { transition-delay: 0.18s; }
  [data-stagger].revealed > *:nth-child(4) { transition-delay: 0.26s; }
  [data-stagger].revealed > * { opacity: 1; transform: none; }

  /* — Hero entrance — */
  .hero-badge    { opacity: 0; transform: translateY(16px) scale(0.95); animation: heroIn 0.6s 0.1s cubic-bezier(0.16,1,0.3,1) forwards; }
  .hero h1       { opacity: 0; transform: translateY(28px);             animation: heroIn 0.75s 0.22s cubic-bezier(0.16,1,0.3,1) forwards; }
  .hero p        { opacity: 0; transform: translateY(24px);             animation: heroIn 0.7s 0.38s cubic-bezier(0.16,1,0.3,1) forwards; }
  .hero-actions  { opacity: 0; transform: translateY(20px);             animation: heroIn 0.65s 0.52s cubic-bezier(0.16,1,0.3,1) forwards; }
  .hero-stats    { opacity: 0; transform: translateY(16px);             animation: heroIn 0.6s 0.68s cubic-bezier(0.16,1,0.3,1) forwards; }
  .hero-image    { opacity: 0; transform: translateX(40px) scale(0.97); animation: heroIn 0.9s 0.3s cubic-bezier(0.16,1,0.3,1) forwards; }

  @keyframes heroIn {
    to { opacity: 1; transform: none; }
  }

  /* — Floating image decoration — */
  .hero-image img {
    animation: floatImg 6s ease-in-out infinite;
  }
  @keyframes floatImg {
    0%, 100% { transform: translateY(0px); }
    50%       { transform: translateY(-10px); }
  }

  /* — Nav slide down — */
  nav { animation: navDrop 0.5s 0s cubic-bezier(0.16,1,0.3,1) both; }
  @keyframes navDrop {
    from { transform: translateY(-100%); opacity: 0; }
    to   { transform: translateY(0);     opacity: 1; }
  }

  /* — Nav scroll shrink — */
  nav.scrolled { box-shadow: 0 4px 32px rgba(0,0,0,0.07); }

  /* — Active nav link underline — */
  .nav-links a {
    position: relative;
  }
  .nav-links a::after {
    content: '';
    position: absolute;
    bottom: -3px; left: 0;
    width: 0; height: 2px;
    background: var(--green);
    border-radius: 2px;
    transition: width 0.25s ease;
  }
  .nav-links a:hover::after,
  .nav-links a.active::after { width: 100%; }

  /* — Service card icon bounce on hover — */
  .service-card:hover .service-icon i {
    animation: iconBounce 0.5s cubic-bezier(0.36,0.07,0.19,0.97);
  }
  @keyframes iconBounce {
    0%,100% { transform: translateY(0) rotate(0deg); }
    25%     { transform: translateY(-6px) rotate(-5deg); }
    75%     { transform: translateY(-3px) rotate(3deg); }
  }

  /* — Feature card ripple — */
  .feature-item { overflow: hidden; position: relative; }
  .feature-item::before {
    content: '';
    position: absolute;
    inset: 0;
    background: var(--green);
    opacity: 0;
    transform: scale(0);
    border-radius: inherit;
    transition: transform 0.4s ease, opacity 0.4s ease;
    transform-origin: center;
  }
  .feature-item:hover::before { transform: scale(1); opacity: 0.07; }
  .feature-item > * { position: relative; z-index: 1; }

  /* — Step number count-up shimmer — */
  .step-num {
    position: relative;
    overflow: hidden;
  }
  .step-num::after {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: shimmer 2.5s 1.5s infinite;
  }
  @keyframes shimmer {
    to { left: 200%; }
  }

  /* — Pricing card pop — */
  .price-card {
    transform-origin: center bottom;
  }
  .price-card:hover { transform: translateY(-6px) scale(1.01); }
  .price-card.featured:hover { transform: translateY(-6px) scale(1.02); }

  /* — Ripple on buttons — */
  .btn-primary, .btn-cta, .btn-wa, .btn-price {
    position: relative;
    overflow: hidden;
  }
  .btn-primary::after, .btn-cta::after, .btn-wa::after, .btn-price::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.35);
    width: 0; height: 0;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    transition: width 0.5s ease, height 0.5s ease, opacity 0.5s ease;
    opacity: 0;
    pointer-events: none;
  }
  .btn-primary:active::after, .btn-cta:active::after,
  .btn-wa:active::after, .btn-price:active::after {
    width: 300px; height: 300px; opacity: 0;
    transition: 0s;
  }

  /* — WhatsApp button pulse ring — */
  .btn-wa { position: relative; }
  .btn-wa::before {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 100px;
    border: 2px solid var(--green);
    opacity: 0;
    animation: waPulse 2.5s 2s infinite;
  }
  @keyframes waPulse {
    0%   { opacity: 0.6; transform: scale(1); }
    100% { opacity: 0;   transform: scale(1.15); }
  }

  /* — Testi card entrance — */
  .testi-card { transform-origin: bottom center; }

  /* — Counter animation — */
  .stat-count { display: inline-block; }

  /* — Floating blobs in hero bg — */
  .hero-blob {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
    filter: blur(80px);
    opacity: 0.35;
    animation: blobMove 8s ease-in-out infinite alternate;
  }
  .hero-blob-1 { width: 400px; height: 400px; background: var(--green-light); top: -100px; right: -100px; animation-delay: 0s; }
  .hero-blob-2 { width: 260px; height: 260px; background: #fff7ed; bottom: 0; left: -80px; animation-delay: -3s; }
  @keyframes blobMove {
    from { transform: translate(0,0) scale(1); }
    to   { transform: translate(20px, 30px) scale(1.08); }
  }

  /* — CTA section animated rings — */
  .cta-ring {
    position: absolute;
    border-radius: 50%;
    border: 1.5px solid rgba(255,255,255,0.2);
    animation: ringExpand 3s ease-out infinite;
    pointer-events: none;
  }
  .cta-ring:nth-child(2) { animation-delay: 1s; }
  .cta-ring:nth-child(3) { animation-delay: 2s; }
  @keyframes ringExpand {
    0%   { width: 80px;  height: 80px;  opacity: 0.6; }
    100% { width: 400px; height: 400px; opacity: 0; }
  }
  .cta-rings-wrap {
    position: absolute;
    bottom: 40px; right: 60px;
    display: flex; align-items: center; justify-content: center;
    pointer-events: none;
    z-index: 0;
  }
  .cta-rings-wrap .cta-ring {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
  }

  /* — Scroll progress bar — */
  #progress-bar {
    position: fixed;
    top: 0; left: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--green), var(--orange));
    z-index: 200;
    width: 0%;
    transition: width 0.1s linear;
    border-radius: 0 2px 2px 0;
  }

  /* — old .fade-in compat — */
  .fade-in { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
  .fade-in.visible { opacity: 1; transform: translateY(0); }
</style>
</head>
<body>

<div id="progress-bar"></div>

<!-- ─── NAV ─── -->
<nav>
  <div class="nav-inner">
    <a href="#" class="logo" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;">
      <img src="{{ asset('images/logo1.png') }}" alt="Logo" style="height:36px;width:auto;">
      Buang Sampah<span>Jogja</span>
    </a>
    <ul class="nav-links">
      <li><a href="#layanan">Layanan</a></li>
      <li><a href="#keunggulan">Keunggulan</a></li>
      <li><a href="#harga">Harga</a></li>
    </ul>
    <a href="https://wa.me/6281234567890" class="btn-wa">
      <i class="fa-brands fa-whatsapp"></i> Order Sekarang
    </a>
  </div>
</nav>

<!-- ─── HERO ─── -->
<section style="padding:0; background: white; position:relative; overflow:hidden;">
  <div class="hero-blob hero-blob-1"></div>
  <div class="hero-blob hero-blob-2"></div>
  <div class="hero">
    <div class="hero-content">
      <div class="hero-badge">
        <div class="hero-badge-dot"></div>
        Tersedia di Seluruh DIY
      </div>
      <h1>Sampah Beres, <em>Hidup Tenang.</em></h1>
      <p>Jasa angkut sampah profesional untuk rumah, renovasi, dan bisnis. Cepat, bersih, dan harga jelas—sekali pesan langsung tuntas.</p>
      <div class="hero-actions">
        <a href="https://wa.me/6281234567890" class="btn-primary">
          <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i>
          Hubungi Admin
        </a>
        <a href="#harga" class="btn-outline">
          Cek Estimasi Harga <i class="fa-solid fa-arrow-down" style="font-size:12px;"></i>
        </a>
      </div>
      <div class="hero-stats">
        <div class="stat-chip">
          <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
          <div>
            <strong><span class="stat-count" data-target="4.9" data-decimal="1">0</span> / 5</strong>
            <span>Rating pelanggan</span>
          </div>
        </div>
        <div class="stat-chip">
          <div class="stat-icon"><i class="fa-solid fa-bolt"></i></div>
          <div>
            <strong><span class="stat-count" data-target="2" data-suffix=" Jam">0</span></strong>
            <span>Estimasi respon</span>
          </div>
        </div>
        <div class="stat-chip">
          <div class="stat-icon"><i class="fa-solid fa-shield-halved"></i></div>
          <div>
            <strong>Harga Pasti</strong>
            <span>Tanpa biaya tersembunyi</span>
          </div>
        </div>
      </div>
    </div>
    <div class="hero-image">
      <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=700&q=80" alt="Tim Jogja Resik">
    </div>
  </div>
</section>

<!-- ─── SERVICES ─── -->
<section id="layanan">
  <div class="container">
    <div class="section-header" data-reveal="fade">
      <div>
        <p class="section-label">Layanan</p>
        <h2 class="section-title">Semua Jenis Sampah,<br>Kami Tangani</h2>
      </div>
      <p class="section-sub">Armada dan tim yang tepat untuk setiap kebutuhan pengangkutan sampah Anda.</p>
    </div>
    <div class="services-grid fade-in" data-stagger>
      <div class="service-card">
        <div class="service-icon"><i class="fa-solid fa-house-user"></i></div>
        <h3>Sampah Rumah Tangga</h3>
        <p>Pengangkutan rutin atau satu kali untuk area hunian. Bersih sampai ke sudut terdalam.</p>
      </div>
      <div class="service-card">
        <div class="service-icon"><i class="fa-solid fa-hammer"></i></div>
        <h3>Sisa Renovasi</h3>
        <p>Puing bangunan, keramik, besi, kayu bekas—kami angkut semua tanpa sisa.</p>
      </div>
      <div class="service-card">
        <div class="service-icon"><i class="fa-solid fa-couch"></i></div>
        <h3>Barang Bekas</h3>
        <p>Furnitur lama, perabot tak terpakai, dan barang bekas lainnya cepat terangkut.</p>
      </div>
      <div class="service-card">
        <div class="service-icon"><i class="fa-solid fa-building"></i></div>
        <h3>Sampah Bisnis</h3>
        <p>Solusi rutin untuk restoran, toko, kantor, dan kawasan komersial.</p>
      </div>
    </div>
  </div>
</section>

<!-- ─── WHY ─── -->
<section id="keunggulan">
  <div class="container">
    <div data-reveal="fade-left">
      <p class="section-label">Keunggulan</p>
      <h2 class="section-title">Bukan Sekadar<br>Tukang Angkut</h2>
      <p class="section-sub" style="margin-bottom:0; line-height:1.8;">Kami adalah tim profesional yang terlatih, berpengalaman, dan berkomitmen penuh terhadap kebersihan lingkungan Yogyakarta.</p>
    </div>
    <div class="why-features fade-in" data-stagger>
      <div class="feature-item">
        <i class="fa-solid fa-bolt"></i>
        <span>Respon Cepat</span>
      </div>
      <div class="feature-item">
        <i class="fa-solid fa-tag"></i>
        <span>Harga Transparan</span>
      </div>
      <div class="feature-item">
        <i class="fa-solid fa-user-tie"></i>
        <span>Tim Berpengalaman</span>
      </div>
      <div class="feature-item">
        <i class="fa-solid fa-truck"></i>
        <span>Armada Lengkap</span>
      </div>
    </div>
  </div>
</section>

<!-- ─── STEPS ─── -->
<div class="steps-section">
  <h2 class="section-title" data-reveal="blur">4 Langkah Mudah</h2>
  <div class="steps-grid fade-in" data-stagger>
    <div class="step-item">
      <p class="step-num">01</p>
    <div class="step-circle"><i class="fa-solid fa-camera"></i></div>
      <h4>Kirim Foto</h4>
      <p>Foto area via WhatsApp untuk estimasi awal</p>
    </div>
    <div class="step-item">
      <p class="step-num">02</p>
      <div class="step-circle"><i class="fa-solid fa-handshake"></i></div>
      <h4>Deal Harga</h4>
      <p>Harga disepakati, tidak ada biaya kejutan</p>
    </div>
    <div class="step-item">
      <p class="step-num">03</p>
      <div class="step-circle"><i class="fa-solid fa-truck-fast"></i></div>
      <h4>Tim Datang</h4>
      <p>Jadwal fleksibel, datang tepat waktu</p>
    </div>
    <div class="step-item">
      <p class="step-num">04</p>
      <div class="step-circle"><i class="fa-solid fa-circle-check"></i></div>
      <h4>Selesai Bersih</h4>
      <p>Area bersih kembali, pekerjaan tuntas</p>
    </div>
  </div>
</div>

<!-- ─── PRICING ─── -->
<section id="harga">
  <div class="container">
    <div style="text-align:center" data-reveal="fade">
      <p class="section-label">Harga</p>
      <h2 class="section-title">Pilih Armada Anda</h2>
      <p class="section-sub" style="margin:0 auto;">Sesuaikan jenis kendaraan dengan volume sampah yang perlu diangkut.</p>
    </div>
    <div class="pricing-grid fade-in" data-stagger>
      <div class="price-card">
        <div class="vehicle-icon"><i class="fa-solid fa-truck-pickup"></i></div>
        <h3>Unit Pickup</h3>
        <p class="subtitle">Ideal untuk sampah rumah tangga & ringan</p>
        <div class="price-amount">Rp 250.000</div>
        <p class="price-per">per trip · estimasi 1–3 jam</p>
        <ul class="price-features">
          <li><i class="fa-solid fa-check"></i> Kapasitas 1–2 m³</li>
          <li><i class="fa-solid fa-check"></i> 2 orang tenaga kerja</li>
          <li><i class="fa-solid fa-check"></i> Cocok untuk sampah organik & ringan</li>
        </ul>
        <a href="https://wa.me/6281234567890" class="btn-price">Pilih Unit Pickup</a>
      </div>
      <div class="price-card featured">
        <div class="price-badge">POPULER</div>
        <div class="vehicle-icon"><i class="fa-solid fa-truck-moving"></i></div>
        <h3>Unit Truk Dam</h3>
        <p class="subtitle">Terbaik untuk renovasi & proyek besar</p>
        <div class="price-amount">Rp 350.000</div>
        <p class="price-per">per trip · estimasi 2–5 jam</p>
        <ul class="price-features">
          <li><i class="fa-solid fa-check"></i> Kapasitas 4–6 m³</li>
          <li><i class="fa-solid fa-check"></i> 3–4 orang tenaga kerja</li>
          <li><i class="fa-solid fa-check"></i> Cocok untuk puing & material berat</li>
        </ul>
        <a href="https://wa.me/6281234567890" class="btn-price">Pesan Truk Dam</a>
      </div>
    </div>
  </div>
</section>

<!-- ─── TESTIMONIALS ─── -->
<section>
  <div class="container">
    <div data-reveal="fade">
      <p class="section-label">Testimoni</p>
      <h2 class="section-title">Kata Mereka</h2>
    </div>
    <div class="testi-grid fade-in" data-stagger>
      <div class="testi-card">
        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
        <blockquote>"Pelayanannya sangat profesional. Tim datang tepat waktu dan area pengangkutan dibersihkan kembali setelah selesai. Sangat puas."</blockquote>
        <div class="testi-author">
          <div class="testi-avatar">S</div>
          <div>
            <div class="testi-name">Ibu Sari</div>
            <div class="testi-loc">Sleman, DIY</div>
          </div>
        </div>
      </div>
      <div class="testi-card">
        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
        <blockquote>"Respon admin sangat cepat di WA. Harga juga transparan dari awal, tidak ada biaya tambahan yang mengejutkan. Rekomen banget!"</blockquote>
        <div class="testi-author">
          <div class="testi-avatar">D</div>
          <div>
            <div class="testi-name">Bapak Doni</div>
            <div class="testi-loc">Bantul, DIY</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── CTA ─── -->
<div class="cta-section">
  <div class="cta-rings-wrap">
    <div class="cta-ring"></div>
    <div class="cta-ring"></div>
    <div class="cta-ring"></div>
  </div>
  <h2 data-reveal="blur">Area Penuh Sampah?<br>Kami Bereskan Sekarang.</h2>
  <p>Tim kami siap 7 hari seminggu. Hubungi via WhatsApp untuk estimasi gratis.</p>
  <a href="https://wa.me/6281234567890" class="btn-cta">
    <i class="fa-brands fa-whatsapp" style="font-size:22px;"></i>
    Chat Admin Sekarang
  </a>
</div>

<<<<<<< HEAD
<!-- ─── FOOTER ─── -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div>
        <div class="footer-logo" style="display:flex;align-items:center;gap:10px;">
          <img src="{{ asset('images/logo1.png') }}" alt="Logo" style="height:32px;width:auto;">
          Buang Sampah<span>Jogja</span>
        </div>
        <p class="footer-desc">Layanan pengangkutan sampah profesional yang berkomitmen menciptakan Yogyakarta yang lebih bersih dan nyaman untuk semua.</p>
      </div>
      <div>
        <p class="footer-heading">Cakupan Area</p>
        <ul class="footer-list">
          <li>Kodya Yogyakarta</li>
          <li>Kabupaten Sleman</li>
          <li>Kabupaten Bantul</li>
        </ul>
      </div>
      <div>
        <p class="footer-heading">Ikuti Kami</p>
        <div class="social-links">
          <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
<<<<<<< HEAD
      </div>
    </div>
    <div class="footer-bottom">&copy; 2026 Jogja Resik · All Rights Reserved</div>
  </div>
</footer>
=======
    </section>

    <section id="cara-pesan" class="border-t border-slate-200/70 bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Cara Pesan</h2>
                <p class="mt-3 text-slate-600">Cukup 4 langkah singkat. Mayoritas order selesai via WhatsApp.</p>
            </div>

            <div class="mt-12 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                    @php
                        $steps = [
                            'Kirim foto sampah',
                            'Dapat estimasi harga',
                            'Tim datang',
                            'Sampah diangkut',
                        ];
                    @endphp

                    @foreach ($steps as $idx => $text)
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-orange-500 text-sm font-extrabold text-white">
                                {{ $idx + 1 }}
                            </div>
                            <div class="text-sm font-semibold text-slate-900">{{ $text }}</div>
                        </div>

                        @if ($idx !== count($steps) - 1)
                            <div class="hidden md:block h-px flex-1 bg-slate-200"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-orange-600 transition">
                    <span class="mr-2" aria-hidden="true"></span> Mulai Pesan via WhatsApp
                </a>
            </div>
        </div>
    </section>

    <section id="harga" class="border-t border-slate-200/70 bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Estimasi Harga</h2>
                <p class="mt-3 text-slate-600">Harga mulai dari. Final menyesuaikan volume, akses lokasi, dan jenis sampah.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-green-700">Paket</div>
                            <h3 class="font-heading mt-2 text-xl font-extrabold text-slate-900">Pickup</h3>
                            <p class="mt-2 text-sm text-slate-600">Cocok untuk volume kecil–menengah.</p>
                        </div>
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-green-700">
                            <i class="fa-solid fa-truck-pickup" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="mt-6 flex items-end gap-2">
                        <div class="text-3xl font-extrabold text-slate-900">Rp 250.000</div>
                        <div class="text-sm text-slate-500">mulai</div>
                    </div>
                    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                       class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-green-600 px-6 py-3 text-sm font-bold text-white hover:bg-green-700 transition">
                        Tanya Estimasi
                    </a>
                </div>

                <div class="relative rounded-2xl border-2 border-orange-500 bg-white p-7 shadow-sm">
                    <div class="absolute -top-3 right-6 rounded-full bg-orange-500 px-3 py-1 text-xs font-extrabold text-white">
                        Recommended
                    </div>
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-orange-600">Paket</div>
                            <h3 class="font-heading mt-2 text-xl font-extrabold text-slate-900">Truk Dam</h3>
                            <p class="mt-2 text-sm text-slate-600">Cocok untuk volume besar / proyek.</p>
                        </div>
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                            <i class="fa-solid fa-truck" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="mt-6 flex items-end gap-2">
                        <div class="text-3xl font-extrabold text-slate-900">Rp 350.000</div>
                        <div class="text-sm text-slate-500">mulai</div>
                    </div>
                    <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                       class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white hover:bg-orange-600 transition">
                        Pesan Truk Dam
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="border-t border-slate-200/70 bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Testimoni</h2>
                <p class="mt-3 text-slate-600">Beberapa review singkat dari pelanggan.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <div class="flex items-center gap-1 text-yellow-400" aria-label="Rating 5 bintang">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                        @endfor
                    </div>
                    <p class="mt-4 text-sm text-slate-700">
                        “Timnya cepat dan bersih kerjanya.”
                    </p>
                    <div class="mt-4 text-xs font-semibold text-slate-500">— Pelanggan Jogja</div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <div class="flex items-center gap-1 text-yellow-400" aria-label="Rating 5 bintang">
                        @for ($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                        @endfor
                    </div>
                    <p class="mt-4 text-sm text-slate-700">
                        “Harga murah dan respon cepat...”
                    </p>
                    <div class="mt-4 text-xs font-semibold text-slate-500">— Pelanggan Bantul</div>
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200/70 bg-white-500 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between gap-6 rounded-2xl bg-white/10 p-8 text-black-500 md:flex-row md:items-center">
                <div>
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight">
                        Sampah Banyak? Biar team Jogja Resik yang Angkut
                    </h2>
                    <p class="mt-2 text-black/80">Klik WhatsApp, kirim foto, kami hitungkan estimasinya.</p>
                </div>
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex w-full items-center justify-center rounded-xl bg-white px-7 py-4 text-base font-extrabold text-green-700 shadow-sm hover:bg-green-50 transition md:w-auto">
                    WhatsApp Sekarang
                </a>
            </div>
        </div>
    </section>

    <section id="kontak" class="bg-green-900 py-14 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logoclient.png') }}" alt="Logo Jogja Resik" class="h-10 w-auto object-contain">
                        <div class="font-heading text-xl font-extrabold">Jogja Resik</div>
                    </div>
                    <p class="mt-4 text-sm text-white/70">
                        Jasa angkut sampah untuk rumah, kos, renovasi, dan usaha. Cepat, rapi, dan transparan.
                    </p>
                </div>

                <div class="md:col-span-1">
                    <div class="text-sm font-bold text-white/90">Area layanan</div>
                    <ul class="mt-3 space-y-2 text-sm text-white/70">
                        <li>Jogja</li>
                        <li>Bantul</li>
                        <li>Sleman</li>
                    </ul>
                </div>

                <div class="md:col-span-1">
                    <div class="text-sm font-bold text-white/90">Hubungi</div>
                    <div class="mt-4 flex flex-col gap-3">
                        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-white hover:bg-white/15 transition">
                            <i class="fa-brands fa-whatsapp text-lg text-emerald-300" aria-hidden="true"></i>
                            WhatsApp
                        </a>
                        <a href="https://instagram.com/{{ $igHandle }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-white hover:bg-white/15 transition">
                            <i class="fa-brands fa-instagram text-lg text-pink-300" aria-hidden="true"></i>
                            Instagram
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-10 border-t border-white/10 pt-8 text-xs text-white/60">
                &copy; {{ date('Y') }} Jogja Resik. All rights reserved.
            </div>
        </div>
    </section>
</main>
>>>>>>> e47207fdfb492a6d95975db04954511563437b8d

<script>
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

  /* inject ripple keyframe */
  const styleEl = document.createElement('style');
  styleEl.textContent = `
    @keyframes rippleAnim {
      to { transform: translate(-50%,-50%) scale(30); opacity: 0; }
    }
  `;
  document.head.appendChild(styleEl);
</script>

</body>
</html>