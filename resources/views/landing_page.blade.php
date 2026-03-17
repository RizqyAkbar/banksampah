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

    <style>
        /* CSS REVISI CAROUSEL ACTIVITY & DOTS */
        .activity-section {
            padding: 80px 0;
            background: #f8fafc;
            overflow: hidden;
        }

        .activity-container {
            position: relative;
            width: 100%;
            margin-top: 40px;
        }

        .activity-viewport {
            overflow: hidden;
            width: 100%;
            border-radius: 16px;
        }

        .activity-track {
            display: flex;
            gap: 20px; /* Jarak antar foto */
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
            will-change: transform;
            touch-action: pan-y; /* Membantu swipe di mobile */
        }

        .activity-item {
            /* Desktop Default: 4 item */
            flex: 0 0 calc(25% - 15px);
            min-width: calc(25% - 15px);
            border-radius: 16px;
            overflow: hidden;
            height: 320px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            background: white;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .activity-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s ease;
        }

        .activity-item:hover img {
            transform: scale(1.1);
        }

        /* Pagination Dots */
        .activity-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 30px;
            flex-wrap: wrap; /* Agar dots tidak berantakan jika banyak */
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dot.active {
            background: #10b981;
            width: 24px;
            border-radius: 4px;
        }

        /* RESPONSIVE REVISI */
        @media (max-width: 1024px) {
            .activity-item {
                flex: 0 0 calc(50% - 10px); /* 2 item di Tablet */
                min-width: calc(50% - 10px);
            }
        }

        @media (max-width: 640px) {
            .activity-section {
                padding: 60px 0;
            }
            
            /* REVISI: Tambahan margin agar teks dan foto tidak menempel di tepi layar */
            #activity .container {
                padding-left: 24px;
                padding-right: 24px;
            }

            .activity-item {
                flex: 0 0 100%; /* REVISI: Tampilan 1 foto full di Mobile */
                min-width: 100%;
                height: 280px; /* Ukuran lebih proporsional untuk 1 foto */
            }
        }
    </style>
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
      <li><a href="#activity">Activity</a></li>
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
          <li><a href="#activity">Activity</a></li>
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
          <i class="fa-solid fa-tag" style="font-size:12px; margin-right: 5px;"></i> Cek Estimasi Harga
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

<section id="activity" class="activity-section">
  <div class="container">
    <div class="section-header" data-reveal="fade">
      <div>
        <p class="section-label">Gallery</p>
        <h2 class="section-title">Activity & Dokumentasi</h2>
      </div>
      <p class="section-sub">Dokumentasi tim kami saat membersihkan dan mengangkut sampah di berbagai lokasi.</p>
    </div>
    
    <div class="activity-container">
      <div class="activity-viewport" id="activityViewport">
        <div class="activity-track" id="activityTrack">
          @for ($i = 1; $i <= 20; $i++)
          <div class="activity-item">
            <img src="{{ asset('images/'.$i.'.jpeg') }}" alt="Dokumentasi {{ $i }}" loading="lazy">
          </div>
          @endfor
        </div>
      </div>
      <div class="activity-dots" id="activityDots"></div>
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
        // --- MOBILE MENU ---
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
                    icon.classList.replace('fa-bars', 'fa-xmark');
                } else {
                    icon.classList.replace('fa-xmark', 'fa-bars');
                }
            });
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.remove('active');
                    mainNav.classList.remove('menu-open'); 
                    hamburgerBtn.querySelector('i').classList.replace('fa-xmark', 'fa-bars');
                });
            });
        }

        // --- CAROUSEL ACTIVITY LOGIC & SWIPE ---
        const track = document.getElementById('activityTrack');
        const dotsContainer = document.getElementById('activityDots');
        const items = document.querySelectorAll('.activity-item');
        let index = 0;
        let autoPlayInterval;

        function getVisibleItems() {
            if (window.innerWidth > 1024) return 4;
            if (window.innerWidth > 640) return 2;
            return 1; // 1 foto penuh di Mobile
        }

        function createDots() {
            dotsContainer.innerHTML = '';
            const visibleItems = getVisibleItems();
            const dotCount = items.length - visibleItems + 1;
            
            for (let i = 0; i < dotCount; i++) {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }
        }

        function updateDots() {
            const dots = document.querySelectorAll('.dot');
            dots.forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
        }

        function goToSlide(n) {
            index = n;
            const visibleItems = getVisibleItems();
            
            // Logika looping
            if (index > items.length - visibleItems) index = 0;
            if (index < 0) index = items.length - visibleItems;
            
            const gap = 20; // Selalu 20px gap agar konsisten saat transisi
            const itemWidth = items[0].offsetWidth + gap;
            
            track.style.transform = `translateX(-${index * itemWidth}px)`;
            updateDots();
        }

        function nextSlide() {
            const visibleItems = getVisibleItems();
            if (index < items.length - visibleItems) {
                index++;
            } else {
                index = 0;
            }
            goToSlide(index);
        }

        function startAutoPlay() {
            // Hindari interval ganda
            clearInterval(autoPlayInterval);
            autoPlayInterval = setInterval(nextSlide, 4000);
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // Init
        createDots();
        startAutoPlay();

        // Fitur Geser Jari (Swipe) untuk Layar Sentuh/Mobile
        let startX = 0;
        let endX = 0;
        
        track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            stopAutoPlay();
        }, {passive: true});
        
        track.addEventListener('touchmove', (e) => {
            endX = e.touches[0].clientX;
        }, {passive: true});
        
        track.addEventListener('touchend', () => {
            if (startX - endX > 50) {
                // Usap ke Kiri (Next)
                nextSlide();
            } else if (endX - startX > 50) {
                // Usap ke Kanan (Prev)
                index--;
                goToSlide(index);
            }
            startAutoPlay();
        });

        // Pause on Hover (Desktop)
        const container = document.querySelector('.activity-container');
        container.addEventListener('mouseenter', stopAutoPlay);
        container.addEventListener('mouseleave', startAutoPlay);

        // Update on resize window
        window.addEventListener('resize', () => {
            index = 0;
            createDots();
            goToSlide(0);
        });
    });
</script>

</body>
</html>