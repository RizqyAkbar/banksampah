<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juragan Urug - Jasa Urug & Bangunan Profesional</title>
    <link rel="icon" type="image/png" href="{{ asset('images/juragan_urug.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/juragan_urug.png') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-grid {
            background-image:
                radial-gradient(circle at 1px 1px, rgba(15, 23, 42, .08) 1px, transparent 0);
            background-size: 28px 28px;
        }
        .mask-radial-hero {
            -webkit-mask-image: radial-gradient(60% 60% at 50% 20%, #000 0%, transparent 70%);
            mask-image: radial-gradient(60% 60% at 50% 20%, #000 0%, transparent 70%);
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased">

    <div id="top"></div>
    <a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-xl focus:bg-slate-900 focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-white" href="#kontak">
        Lewati ke kontak
    </a>

    <nav class="fixed top-0 z-50 w-full border-b border-slate-200/70 bg-white/80 backdrop-blur">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="#top" class="flex items-center gap-2">
                    <img
                        src="{{ asset('images/juragan_urug.png') }}"
                        alt="Logo Juragan Urug"
                        class="h-9 w-auto max-w-[140px] object-contain"
                        loading="eager"
                        decoding="async"
                    >
                    <span class="text-lg font-extrabold tracking-tight text-slate-900">
                        Juragan <span class="text-amber-500">Urug</span>
                    </span>
                </a>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#top" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Beranda</a>
                    <a href="#layanan" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Layanan</a>
                    <a href="#katalog" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Katalog</a>
                    <a href="#kontak" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Kontak</a>
                </div>

                <div class="flex items-center gap-3">
                    <a href="#kontak" class="hidden sm:inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition">
                        Konsultasi
                    </a>
                    <a href="#kontak" class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-slate-950 shadow-sm hover:bg-amber-400 transition">
                        Pesan Sekarang
                    </a>

                    <button type="button" class="md:hidden inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2 text-slate-700 shadow-sm hover:bg-slate-50 transition"
                            aria-controls="mobile-menu" aria-expanded="false" data-mobile-trigger>
                        <span class="sr-only">Buka menu</span>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="md:hidden hidden border-t border-slate-200 bg-white" data-mobile-menu>
            <div class="mx-auto max-w-7xl px-4 py-3 space-y-1">
                <a href="#top" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Beranda</a>
                <a href="#layanan" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Layanan</a>
                <a href="#katalog" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Katalog</a>
                <a href="#kontak" class="block rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Kontak</a>
            </div>
        </div>
    </nav>

    <main>
        <section class="relative overflow-hidden pt-28 pb-16">
            <div class="absolute inset-0 -z-10">
                <div class="absolute inset-0 bg-gradient-to-b from-white via-amber-50/50 to-white"></div>
                <div class="absolute inset-0 bg-grid mask-radial-hero"></div>
            </div>

                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-stretch">
                
                <div class="flex h-full flex-col justify-between gap-8">
                    <div>
                        <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl leading-[1.08]">
                            <span class="text-amber-500">Jasa Urug</span> Puing Bangunan & Tanah <span class="text-amber-500">Termurah</span> di Jogja!
                        </h1>
                        <p class="mt-4 text-base text-slate-600 sm:text-lg max-w-xl">
                            Lebih dari 10 tahun kami hadir memberikan layanan terbaik dengan armada yang lengkap. Kami adalah spesialis terpercaya untuk urusan pembersihan lahan, pembuangan puing, pengurukan tanah, serta penyediaan material dan alat berat.
                        </p>

                        <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <a href="#katalog" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition">
                                Lihat Katalog
                            </a>
                            <a href="#kontak" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-800 shadow-sm hover:bg-slate-50 transition">
                                Minta Estimasi
                            </a>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3 max-w-xl lg:mt-0">
                        <div class="rounded-2xl border border-slate-200 bg-white/70 px-8 py-7 flex flex-col justify-center">
                            <div class="text-3xl font-extrabold text-slate-900 text-center">254</div>
                            <div class="text-xs text-center text-slate-600 mt-1">Proyek Dikerjakan</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/70 px-8 py-7 flex flex-col justify-center">
                            <div class="text-3xl font-extrabold text-slate-900 text-center">55</div>
                            <div class="text-xs text-center text-slate-600 mt-1">Armada</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white/70 px-8 py-7 flex flex-col justify-center">
                            <div class="text-3xl font-extrabold text-slate-900 text-center">120</div>
                            <div class="text-xs text-center text-slate-600 mt-1">Tenaga Profesional</div>
                        </div>
                    </div>
                </div>

        <div class="relative h-full">
            <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-gradient-to-tr from-amber-200/70 via-white to-slate-200/60 blur-2xl"></div>
            
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm h-full">
                <img src="images/truk.png" alt="Alat berat untuk penyiapan lahan" class="h-full w-full object-cover min-h-[320px] sm:min-h-[420px]">
            </div>
        </div>
        
    </div>
</div>
        </section>

        <section id="layanan" class="py-16 bg-slate-50 border-t border-slate-200/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Layanan utama</h2>
                    <p class="mt-3 text-slate-600">Juragan Urug Jogja menyediakan layanan jasa buang puing , jasa buang tanah dan sampah proyek, pengurukan tanah, sewa alat berat dan material</p>
                </div>

                <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div class="group rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 6h18"></path>
                                    <path d="M6 6l1 14h10l1-14"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">Buang puing</span>
                        </div>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-900">Buang Puing Bangunan</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Membuang puing bekas bongkaran bangunan dan sisa sampah proyek sampai lokasi bersih.
                            Armada: truk dump dan pickup.
                        </p>
                    </div>

                    <div class="group rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 21h18"></path>
                                    <path d="M7 21V9l5-4 5 4v12"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">Urug lahan</span>
                        </div>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-900">Urug Tanah & Puing</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Pengurukan tanah atau puing yang cepat padat untuk lahan bangunan Anda.
                            Pilihan urug: puing bangunan, tanah murni, tanah merah Godean, tanah gunung.
                        </p>
                    </div>

                    <div class="group rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 3h18v4H3z"></path>
                                    <path d="M5 7v14h14V7"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">Material</span>
                        </div>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-900">Supplier Material</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Menyediakan berbagai kebutuhan material bangunan:
                            pasir Progo &amp; Merapi, batu pondasi, split, batu bata, dan material lainnya.
                        </p>
                    </div>

                    <div class="group rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 19h16"></path>
                                    <path d="M6 19V9a2 2 0 0 1 2-2h4"></path>
                                    <path d="M12 7l6 6"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">Alat berat</span>
                        </div>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-900">Sewa Alat Berat</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Penyewaan alat berat untuk mempermudah dan mempercepat pengerjaan proyek.
                            Armada: eskavator berbagai ukuran dan buldozer.
                        </p>
                    </div>

                    <div class="group rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 6l3-3h12l3 3v12H3z"></path>
                                    <path d="M9 22V12h6v10"></path>
                                </svg>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">Pembongkaran</span>
                        </div>
                        <h3 class="mt-5 text-lg font-extrabold text-slate-900">Pembongkaran Rumah</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Jasa pembongkaran rumah sekaligus pembuangan puing sisa bongkaran.
                            Bisa sistem per truk maupun borongan sesuai kesepakatan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="katalog" class="py-16 bg-white border-t border-slate-200/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div class="max-w-2xl">
                        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Katalog material & jasa</h2>
                        <p class="mt-3 text-slate-600">Harga mulai dari, untuk gambaran cepat. Untuk volume besar, kami buatkan penawaran sesuai kebutuhan.</p>
                    </div>
                    <a href="#kontak" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-800 shadow-sm hover:bg-slate-50 transition">
                        Butuh jumlah besar? Hubungi kami
                        <span class="ml-2" aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                        <div class="h-40 bg-gradient-to-br from-amber-100 via-white to-slate-100 flex items-center justify-center">
                            <svg class="h-10 w-10 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 21h18"></path>
                                <path d="M7 21V9l5-4 5 4v12"></path>
                            </svg>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Urug Tanah Armada Pickup</h3>
                                    <p class="text-xs text-slate-500 mt-1">Per armada pickup, cocok untuk skala kecil–menengah.</p>
                                </div>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">Mulai</span>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-lg font-extrabold text-slate-900">Rp200,000.00</span>
                                <a href="#kontak" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-extrabold text-white hover:bg-slate-800 transition">Pilih</a>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                        <div class="h-40 bg-gradient-to-br from-amber-100 via-white to-slate-100 flex items-center justify-center">
                            <svg class="h-10 w-10 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 21h18"></path>
                                <path d="M7 21V9l5-4 5 4v12"></path>
                                <path d="M9 21v-6h6v6"></path>
                            </svg>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Urug Puing Armada Pickup</h3>
                                    <p class="text-xs text-slate-500 mt-1">Per armada pickup, untuk urug puing bekas bongkaran.</p>
                                </div>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">Mulai</span>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-lg font-extrabold text-slate-900">Rp150,000.00</span>
                                <a href="#kontak" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-extrabold text-white hover:bg-slate-800 transition">Pilih</a>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                        <div class="h-40 bg-gradient-to-br from-amber-100 via-white to-slate-100 flex items-center justify-center">
                            <svg class="h-10 w-10 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 6h18"></path>
                                <path d="M6 6l1 14h10l1-14"></path>
                            </svg>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Bis Beton Sumur Resapan</h3>
                                    <p class="text-xs text-slate-500 mt-1">Per unit / biji, untuk sumur resapan dan sanitasi.</p>
                                </div>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">Mulai</span>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-lg font-extrabold text-slate-900">Rp250,000.00</span>
                                <a href="#kontak" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-extrabold text-white hover:bg-slate-800 transition">Pilih</a>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition overflow-hidden">
                        <div class="h-40 bg-gradient-to-br from-amber-100 via-white to-slate-100 flex items-center justify-center">
                            <svg class="h-10 w-10 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 19h16"></path>
                                <path d="M6 19V9a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v10"></path>
                                <path d="M9 10h6"></path>
                            </svg>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900">Urug Tanah <br> Galian</h3>
                                    <p class="text-xs text-slate-500 mt-1">Per truk dump, cocok untuk volume urug besar.</p>
                                </div>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">Mulai</span>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-lg font-extrabold text-slate-900">Rp85,000.00</span>
                                <a href="#kontak" class="rounded-xl bg-slate-900 px-4 py-2 text-xs font-extrabold text-white hover:bg-slate-800 transition">Pilih</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 rounded-3xl border border-slate-200 bg-slate-50 p-6 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-xs font-bold text-amber-700">Estimasi cepat</div>
                            <div class="mt-1 text-lg font-extrabold text-slate-900">Kirim lokasi & kebutuhan, kami hitungkan.</div>
                            <div class="mt-2 text-sm text-slate-600">Cukup sebutkan jenis layanan, perkiraan luas/volume, dan akses jalan.</div>
                        </div>
                        <a href="#kontak" class="inline-flex items-center justify-center rounded-2xl bg-amber-500 px-6 py-3 text-sm font-extrabold text-slate-950 hover:bg-amber-400 transition">
                            Minta Estimasi Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-slate-50 border-t border-slate-200/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">
                        Kenapa Harus <span class="text-amber-500">Juragan Urug</span> Jogja?
                    </h2>
                    <p class="mt-3 text-slate-600">
                        Empat alasan utama kenapa banyak pelanggan memilih kami untuk urusan urug, buang puing, dan material.
                    </p>
                </div>

                <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 6v6l3 3"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-extrabold text-slate-900">Hemat Waktu</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Pekerjaan ditangani secara profesional oleh tenaga berpengalaman sehingga proses jadi lebih cepat dan efektif.
                        </p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M6 20h12"></path>
                                <path d="M9 20V10a3 3 0 1 1 6 0v10"></path>
                                <path d="M5 11h14"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-extrabold text-slate-900">Hemat Tenaga</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Tim kami yang mengurus pembersihan dan pembuangan ke lokasi yang tepat, Anda bisa fokus ke tahapan pembangunan lainnya.
                        </p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="7" r="3"></circle>
                                <path d="M4 21c1.5-4 3.5-6 8-6s6.5 2 8 6"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-extrabold text-slate-900">Tenaga Ahli</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Tenaga profesional dan area pembuangan resmi membuat Anda tinggal terima beres—alur kerja dan perizinan kami yang atur.
                        </p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm hover:shadow-md transition">
                        <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4" y="5" width="16" height="14" rx="2"></rect>
                                <path d="M9 10h6"></path>
                                <path d="M9 14h3"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-base font-extrabold text-slate-900">Harga Bersahabat</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Harga yang bersahabat membuat Anda bisa memanfaatkan layanan kami tanpa mengorbankan kualitas pekerjaan.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="artikel" class="py-16 bg-white border-t border-slate-200/70">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Artikel &amp; tips seputar material</h2>
                        <p class="mt-3 text-sm text-slate-600 max-w-xl">
                            Update singkat seputar pasir, material, dan solusi praktis untuk urusan lahan serta sampah proyek.
                        </p>
                    </div>
                    <a href="#kontak" class="mt-4 sm:mt-0 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-800 shadow-sm hover:bg-slate-50 transition">
                        Konsultasi topik ini
                        <span class="ml-2" aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <article class="flex h-full flex-col rounded-3xl border border-slate-200 bg-slate-50/60 p-6 shadow-sm hover:shadow-md transition">
                        <div class="text-xs font-semibold uppercase tracking-wide text-amber-700">March 4, 2026</div>
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">
                            Pasir Merapi Kali vs Pasir Merapi Biasa: Perbedaan, Kelebihan &amp;
                        </h3>
                        <p class="mt-3 text-sm text-slate-600 flex-1">
                            Pasir merupakan material utama dalam setiap proyek bangunan. Salah satu yang paling banyak digunakan oleh...
                        </p>
                        <a href="#" class="mt-4 inline-flex items-center text-sm font-semibold text-amber-700 hover:text-amber-800">
                            Baca selengkapnya
                            <span class="ml-1" aria-hidden="true">&rarr;</span>
                        </a>
                    </article>

                    <article class="flex h-full flex-col rounded-3xl border border-slate-200 bg-slate-50/60 p-6 shadow-sm hover:shadow-md transition">
                        <div class="text-xs font-semibold uppercase tracking-wide text-amber-700">January 16, 2026</div>
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">
                            Solusi Praktis Atasi Sampah Proyek Yang Menumpuk; Resiko Keselamatan
                        </h3>
                        <p class="mt-3 text-sm text-slate-600 flex-1">
                            Mengabaikan pengelolaan sampah proyek bukan hanya merusak estetika lokasi kerja, tetapi juga menciptakan risiko hukum...
                        </p>
                        <a href="#" class="mt-4 inline-flex items-center text-sm font-semibold text-amber-700 hover:text-amber-800">
                            Baca selengkapnya
                            <span class="ml-1" aria-hidden="true">&rarr;</span>
                        </a>
                    </article>

                    <article class="flex h-full flex-col rounded-3xl border border-slate-200 bg-slate-50/60 p-6 shadow-sm hover:shadow-md transition">
                        <div class="text-xs font-semibold uppercase tracking-wide text-amber-700">December 13, 2025</div>
                        <h3 class="mt-3 text-base font-extrabold text-slate-900">
                            Pasir Progo: Pasir Bangunan Premium dengan Kualitas Terpercaya untuk Berbagai
                        </h3>
                        <p class="mt-3 text-sm text-slate-600 flex-1">
                            Dalam dunia konstruksi, kualitas pasir sangat menentukan kekuatan dan keawetan bangunan. Salah satu jenis pasir...
                        </p>
                        <a href="#" class="mt-4 inline-flex items-center text-sm font-semibold text-amber-700 hover:text-amber-800">
                            Baca selengkapnya
                            <span class="ml-1" aria-hidden="true">&rarr;</span>
                        </a>
                    </article>
                </div>
            </div>
        </section>

        <section id="kontak" class="relative py-16 text-white">
            <div class="absolute inset-0 -z-10 bg-slate-950"></div>
            <div class="absolute inset-0 -z-10 bg-gradient-to-b from-amber-500/10 via-transparent to-transparent"></div>
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-12">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold text-amber-200">
                            Hubungi kami
                        </div>
                        <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-4xl">Siap mulai proyek Anda?</h2>
                        <p class="mt-4 text-sm text-white/70 sm:text-base max-w-xl">
                            Punya pertanyaan soal estimasi biaya, pilihan material, atau jadwal pengerjaan? Kirim detail singkat dan langsung kami bantu.
                        </p>

                        <div class="mt-8 space-y-3">
                            <div class="flex items-start gap-3 text-sm text-white/80">
                                <div class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-amber-300">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <path d="M3 7l9 6 9-6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold">info@jasaurugjogja.com</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-white/80">
                                <div class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-emerald-300">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold">0899-4646-224</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-white/80">
                                <div class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-pink-300">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="4" y="4" width="16" height="16" rx="5"></rect>
                                        <circle cx="12" cy="12" r="3.5"></circle>
                                        <circle cx="17" cy="7" r="1"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold">@jasaurugjogja</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-white/80">
                                <div class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-blue-300">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M22 12a10 10 0 1 0-11.5 9.87v-7h-2v-2.87h2V9.5c0-2 1.2-3.12 3-3.12.87 0 1.78.16 1.78.16v1.96h-1c-1 0-1.31.63-1.31 1.28v1.47h2.23L15 14.87h-1.8v7A10 10 0 0 0 22 12z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold">Juragan Urug</span>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-white/80">
                                <div class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-amber-300">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 10c0 6-9 12-9 12S3 16 3 10a9 9 0 1 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-semibold">Jl. AS-Samawaat, Kersan, Tirtonirmolo, Kec. Kasihan, Kab. Bantul DIY 55181</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="https://wa.me/628994646224" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center justify-center rounded-2xl bg-emerald-500 px-6 py-3 text-sm font-extrabold text-white shadow-sm hover:bg-emerald-400 transition">
                                <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                Chat WhatsApp
                            </a>
                            <a href="#katalog" class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/5 px-6 py-3 text-sm font-extrabold text-white hover:bg-white/10 transition">
                                Lihat harga mulai dari
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-white/10 bg-white/5 p-7 sm:p-8 shadow-sm">
                        <h3 class="text-lg font-extrabold text-white">Lokasi kami di Google Maps</h3>
                        <p class="mt-2 text-sm text-white/70">
                            Area layanan utama di Jogja dan sekitarnya. Anda bisa membuka peta penuh untuk navigasi ke lokasi kami.
                        </p>

                        <div class="mt-5 overflow-hidden rounded-2xl border border-white/10 bg-slate-900/40">
                            <div class="relative pt-[56.25%]">
                                <iframe
                                    class="absolute inset-0 h-full w-full"
                                    src="https://www.google.com/maps?q=Jl.+AS-Samawaat,+Kersan,+Tirtonirmolo,+Kasihan,+Bantul+DIY+55181&output=embed"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                ></iframe>
                            </div>
                        </div>

                        <a href="https://www.google.com/maps/search/?api=1&query=Jl.+AS-Samawaat,+Kersan,+Tirtonirmolo,+Kasihan,+Bantul+DIY+55181"
                           target="_blank" rel="noopener noreferrer"
                           class="mt-4 inline-flex items-center text-xs font-semibold text-amber-200 hover:text-amber-100">
                            Buka di Google Maps
                            <span class="ml-1" aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <footer class="border-t border-slate-200/70 bg-white py-10">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <img
                                src="{{ asset('images/juragan_urug.png') }}"
                                alt="Logo Juragan Urug"
                                class="h-8 w-auto max-w-[140px] object-contain"
                                loading="lazy"
                                decoding="async"
                            >
                            <span class="text-base font-extrabold text-slate-900">Juragan <span class="text-amber-500">Urug</span></span>
                        </div>
                        <p class="mt-2 text-sm text-slate-600">Partner andalan untuk penyiapan lahan proyek Anda.</p>
                    </div>
                    <div class="text-sm text-slate-500">
                        &copy; {{ date('Y') }} Hak Cipta Juragan Urug Jogja | Produksi 2022.
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <script>
        (function () {
            const trigger = document.querySelector('[data-mobile-trigger]');
            const menu = document.querySelector('[data-mobile-menu]');
            if (trigger && menu) {
                trigger.addEventListener('click', function () {
                    const isHidden = menu.classList.contains('hidden');
                    menu.classList.toggle('hidden', !isHidden);
                    trigger.setAttribute('aria-expanded', String(isHidden));
                });
                menu.querySelectorAll('a[href^="#"]').forEach((a) => {
                    a.addEventListener('click', function () {
                        menu.classList.add('hidden');
                        trigger.setAttribute('aria-expanded', 'false');
                    });
                });
            }

            const form = document.getElementById('quick-contact-form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const name = (document.getElementById('qc-name') || {}).value || '';
                    const service = (document.getElementById('qc-service') || {}).value || '';
                    const message = (document.getElementById('qc-message') || {}).value || '';

                    const phone = '628994646224';
                    const text = [
                        'Halo Juragan Urug, saya ingin konsultasi.',
                        '',
                        `Nama: ${name}`.trim(),
                        `Layanan: ${service}`.trim(),
                        message ? `Detail: ${message}` : null
                    ].filter(Boolean).join('\n');

                    const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
                    window.open(url, '_blank', 'noopener,noreferrer');
                });
            }
        })();
    </script>

</body>
</html>