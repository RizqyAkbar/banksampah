<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogja Resik - Jasa Angkut Sampah</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" referrerpolicy="no-referrer">

    <style>
        body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif; }
        .font-heading { font-family: Poppins, Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif; }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased">
@php
    // Ganti ke nomor WhatsApp bisnis Anda (format internasional tanpa +)
    $waNumber = '6281234567890';
    $waText = rawurlencode('Halo Jogja Resik, saya ingin pesan jasa angkut sampah. Bisa dibantu?');
    $waLink = "https://wa.me/{$waNumber}?text={$waText}";
    $igHandle = 'jogjaresik';
@endphp

<a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-xl focus:bg-green-900 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white" href="#kontak">
    Lewati ke kontak
</a>

<header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="#top" class="flex items-center gap-3">
                <img src="{{ asset('images/logoclient.png') }}" alt="Logo Jogja Resik" class="h-10 w-auto object-contain">
                <span class="font-heading text-lg font-extrabold tracking-tight text-slate-900">
                    Jogja <span class="text-green-600">Resik</span>
                </span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a class="hover:text-slate-900 transition" href="#layanan">Layanan</a>
                <a class="hover:text-slate-900 transition" href="#keunggulan">Keunggulan</a>
                <a class="hover:text-slate-900 transition" href="#cara-pesan">Cara Pesan</a>
                <a class="hover:text-slate-900 transition" href="#harga">Harga</a>
                <a class="hover:text-slate-900 transition" href="#testimoni">Testimoni</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                   class="hidden sm:inline-flex items-center justify-center rounded-xl bg-orange-500 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-orange-600 transition">
                    <span class="mr-2" aria-hidden="true"></span> Pesan via WhatsApp
                </a>
                <button type="button"
                        class="md:hidden inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white p-2 text-slate-700 shadow-sm hover:bg-slate-50 transition"
                        aria-controls="mobile-menu" aria-expanded="false" data-mobile-trigger>
                    <span class="sr-only">Buka menu</span>
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="md:hidden hidden border-t border-slate-200 bg-white" data-mobile-menu>
        <div class="mx-auto max-w-7xl px-4 py-3 space-y-1 text-sm font-semibold text-slate-700">
            <a href="#layanan" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Layanan</a>
            <a href="#keunggulan" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Keunggulan</a>
            <a href="#cara-pesan" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Cara Pesan</a>
            <a href="#harga" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Harga</a>
            <a href="#testimoni" class="block rounded-lg px-3 py-2 hover:bg-slate-50">Testimoni</a>
            <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
               class="mt-2 flex items-center justify-center rounded-xl bg-orange-500 px-4 py-2 font-bold text-white hover:bg-orange-600 transition">
                <span class="mr-2" aria-hidden="true"></span> Pesan via WhatsApp
            </a>
        </div>
    </div>
</header>

<main id="top" class="pt-20">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-green-100 blur-3xl opacity-70"></div>
            <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-orange-100 blur-3xl opacity-70"></div>
            <div class="absolute inset-0 bg-white"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-green-200 bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        <i class="fa-solid fa-shield-heart" aria-hidden="true"></i>
                        Cepat datang • langsung beres
                    </div>

                    <h1 class="font-heading mt-5 text-4xl font-extrabold leading-[1.1] tracking-tight text-slate-900 sm:text-5xl">
                        Jasa Angkut Sampah Profesional di Jogja
                    </h1>
                    <p class="mt-4 max-w-xl text-base text-slate-600 sm:text-lg">
                        Angkut sampah rumah, kos, renovasi bangunan, dan usaha. Cepat datang, langsung beres.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center justify-center rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white shadow-sm hover:bg-orange-600 transition">
                            <span class="mr-2" aria-hidden="true"></span> Pesan via WhatsApp
                        </a>
                        <a href="#harga"
                           class="inline-flex items-center justify-center rounded-xl border border-green-200 bg-white px-6 py-3 text-sm font-bold text-green-700 hover:border-orange-500 hover:text-orange-600 transition">
                            Lihat Estimasi Harga
                        </a>
                    </div>

                    <div class="mt-10 grid grid-cols-1 gap-3 sm:grid-cols-3 max-w-xl">
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-sm font-semibold text-slate-900">Respon cepat</div>
                            <div class="mt-1 text-xs text-slate-600">Konfirmasi & jadwal lebih sat-set.</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-sm font-semibold text-slate-900">Harga jelas</div>
                            <div class="mt-1 text-xs text-slate-600">Estimasi transparan dari awal.</div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="text-sm font-semibold text-slate-900">Bersih rapi</div>
                            <div class="mt-1 text-xs text-slate-600">Angkut sampai area bersih.</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-gradient-to-tr from-green-200/60 via-white to-orange-200/50 blur-2xl"></div>
                    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-sm">
                        <img
                            src="https://images.unsplash.com/photo-1603386329225-868f9b1ee6b6?auto=format&fit=crop&w=1400&q=80"
                            alt="Ilustrasi tim / truk angkut sampah"
                            class="h-[320px] w-full object-cover sm:h-[420px]"
                            loading="lazy"
                            decoding="async"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="border-t border-slate-200/70 bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Layanan</h2>
                <p class="mt-3 text-slate-600">Pilih layanan sesuai kebutuhan. Kami bantu bereskan cepat dan rapi.</p>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-6 md:grid-cols-4">
                <div class="rounded-xl border border-green-200 bg-white p-6 shadow-sm transition hover:border-orange-500 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700">
                        <span class="text-xl" aria-hidden="true">♻️</span>
                    </div>
                    <h3 class="font-heading mt-4 text-lg font-bold text-slate-900">Sampah Rumah</h3>
                    <p class="mt-2 text-sm text-slate-600">Sampah harian, pindahan, dan bersih-bersih rumah.</p>
                </div>

                <div class="rounded-xl border border-green-200 bg-white p-6 shadow-sm transition hover:border-orange-500 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700">
                        <span class="text-xl" aria-hidden="true">🪵</span>
                    </div>
                    <h3 class="font-heading mt-4 text-lg font-bold text-slate-900">Sampah Renovasi</h3>
                    <p class="mt-2 text-sm text-slate-600">Sisa bongkaran, puing, dan material renovasi.</p>
                </div>

                <div class="rounded-xl border border-green-200 bg-white p-6 shadow-sm transition hover:border-orange-500 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700">
                        <span class="text-xl" aria-hidden="true">🛋</span>
                    </div>
                    <h3 class="font-heading mt-4 text-lg font-bold text-slate-900">Barang Bekas</h3>
                    <p class="mt-2 text-sm text-slate-600">Kasur, lemari, kursi, dan barang besar lainnya.</p>
                </div>

                <div class="rounded-xl border border-green-200 bg-white p-6 shadow-sm transition hover:border-orange-500 hover:shadow-md">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 text-green-700">
                        <span class="text-xl" aria-hidden="true">🏪</span>
                    </div>
                    <h3 class="font-heading mt-4 text-lg font-bold text-slate-900">Sampah Usaha</h3>
                    <p class="mt-2 text-sm text-slate-600">Sampah toko, kantor, gudang, dan tempat usaha.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="keunggulan" class="border-t border-slate-200/70 bg-green-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight text-slate-900">Keunggulan Jogja Resik</h2>
                    <p class="mt-3 text-slate-600">Didesain untuk praktis: komunikasi jelas, pengerjaan cepat, dan hasil rapi.</p>
                </div>
                <div class="rounded-2xl border border-green-200 bg-white p-7 shadow-sm">
                    <ul class="space-y-4 text-sm text-slate-700">
                        @foreach (['Respon cepat', 'Harga transparan', 'Tim profesional', 'Angkut sampai bersih'] as $item)
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-orange-100 text-orange-600">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                </span>
                                <span class="font-medium">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
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

    <section class="border-t border-slate-200/70 bg-orange-500 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start justify-between gap-6 rounded-2xl bg-white/10 p-8 text-white md:flex-row md:items-center">
                <div>
                    <h2 class="font-heading text-3xl font-extrabold tracking-tight">
                        Sampah Banyak? Biar team Jogja Resik yang Angkut
                    </h2>
                    <p class="mt-2 text-white/80">Klik WhatsApp, kirim foto, kami hitungkan estimasinya.</p>
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
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-green-600 text-white">
                            <i class="fa-solid fa-leaf" aria-hidden="true"></i>
                        </span>
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
    })();
</script>
</body>
</html>

