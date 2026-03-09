<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buang Sampah Jogja — Jasa Angkut Sampah</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,600&family=Nunito:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    
</head>
<body>

<div id="progress-bar"></div>

<nav id="main-nav">
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

    <div class="nav-actions">
        <a href="https://wa.me/6281234567890" class="btn-wa">
          <i class="fa-brands fa-whatsapp"></i> <span class="wa-text">Order Sekarang</span>
        </a>
        <button id="hamburger-btn" class="btn-hamburger" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
  </div>

  <div id="mobile-menu" class="mobile-menu">
      <ul class="mobile-nav-links">
          <li><a href="#layanan">Layanan</a></li>
          <li><a href="#keunggulan">Keunggulan</a></li>
          <li><a href="#harga">Harga</a></li>
      </ul>
  </div>
</nav>

<section style="padding:0; background: white; position:relative; overflow:hidden;">
  <div class="hero-blob hero-blob-1"></div>
  <div class="hero-blob hero-blob-2"></div>
  <div class="hero container">
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
      <img src="{{ asset('images/ilustrasi.png') }}" alt="Tim Jogja Resik">
    </div>
  </div>
</section>

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
      </div>
    </div>
    <div class="footer-bottom">&copy; 2026 Buang Sampah Jogja · All Rights Reserved</div>
  </div>
</footer>

<script src="{{ asset('js/script.js') }}" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainNav = document.getElementById('main-nav');
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-nav-links a');

        if (hamburgerBtn && mobileMenu) {
            hamburgerBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('active');
                
                mainNav.classList.toggle('menu-open');
                
                const icon = hamburgerBtn.querySelector('i');
                if(mobileMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                } else {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            });

            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                    mainNav.classList.remove('menu-open'); 
                    const icon = hamburgerBtn.querySelector('i');
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                });
            });
        }
    });
</script>

</body>
</html>