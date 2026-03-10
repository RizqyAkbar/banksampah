<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Rekap - Admin Juragan Urug</title>

    <link rel="icon" href="{{ asset('images/logoclient.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        .font-heading { font-family: 'Poppins', sans-serif; }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Custom Input Styles */
        .form-input {
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #0f172a;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #00C264;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 194, 100, 0.1);
        }
        
        /* Tambahan Class untuk Input dengan Ikon di Kiri */
        .form-input-icon-left {
            padding-left: 2.75rem !important; /* Memaksa padding kiri agar teks tidak numpuk dengan ikon */
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800 antialiased">

    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm transition-opacity lg:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white border-r border-slate-100 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-transform duration-300 ease-in-out -translate-x-full lg:static lg:translate-x-0">
        
        <div class="flex h-20 items-center justify-between px-6 border-b border-slate-50">
            <a href="#" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50">
                    <img src="{{ asset('images/logoclient.png') }}" alt="Logo" class="h-6 w-auto object-contain">
                </div>
                <span class="font-heading text-xl font-bold text-slate-900 tracking-tight">Juragan<span class="text-green-600">Urug</span></span>
            </a>
            <button id="closeSidebar" class="p-2 text-slate-400 hover:text-red-500 lg:hidden">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1.5">
            <p class="px-4 pb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Menu Utama</p>
            
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-chart-pie w-5 text-center text-lg text-slate-400 group-hover:text-green-500 transition-colors"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.input_order') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-cart-plus w-5 text-center text-lg text-slate-400 group-hover:text-green-500 transition-colors"></i>
                Input Order
            </a>

            <a href="{{ route('admin.rekap_order') }}" class="flex items-center gap-3 rounded-xl bg-green-50 px-4 py-3.5 text-sm font-semibold text-green-700 transition-colors">
                <i class="fa-solid fa-list-ul w-5 text-center text-lg"></i>
                Rekap Order
            </a>

            <p class="px-4 pb-2 pt-6 text-xs font-bold uppercase tracking-wider text-slate-400">Keuangan</p>

            <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-lg text-slate-400 group-hover:text-green-500 transition-colors"></i>
                Create Invoice
            </a>

            <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-wallet w-5 text-center text-lg text-slate-400 group-hover:text-green-500 transition-colors"></i>
                Payment
            </a>
        </nav>

        <div class="border-t border-slate-100 p-4">
            <div class="flex items-center gap-3 rounded-xl p-3 transition-colors hover:bg-slate-50 cursor-pointer">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-600 font-bold">
                    A
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="truncate text-sm font-bold text-slate-900">Admin Juragan Urug</p>
                    <p class="truncate text-xs text-slate-500">admin@juraganurug.com</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer" title="Keluar">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="flex flex-1 flex-col overflow-hidden relative">
        
        <header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/80 px-6 backdrop-blur-md z-30">
            <div class="flex items-center gap-4">
                <button id="openSidebar" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900 lg:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.rekap_order') }}" class="text-slate-400 hover:text-slate-700 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">Edit Data Rekap</h1>
                </div>
            </div>
        </header>

        <div class="flex flex-col flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="font-heading text-2xl font-bold text-slate-900 sm:hidden">Lengkapi Rekap Order</h1>
                <span class="px-3 py-1 bg-{{ $order->status_pelanggan == 'Pelanggan Lama' ? 'blue' : 'green' }}-100 text-{{ $order->status_pelanggan == 'Pelanggan Lama' ? 'blue' : 'green' }}-700 font-bold rounded-full text-xs">
                    {{ $order->status_pelanggan }}
                </span>
            </div>

            <div class="max-w-4xl mx-auto w-full">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
                    <form action="{{ route('admin.rekap_order.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-slate-50 p-5 rounded-xl border border-slate-100">
                            <div>
                                <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Nama Pelanggan</p>
                                <p class="font-bold text-slate-800 text-lg">{{ $order->nama_pelanggan }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">No WhatsApp</p>
                                <p class="font-bold text-slate-800 text-lg"><i class="fa-brands fa-whatsapp text-green-500 mr-1.5"></i>{{ $order->no_wa }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Penjadwalan</p>
                                <p class="font-bold text-slate-800 text-lg"><i class="fa-regular fa-calendar text-blue-500 mr-1.5"></i>{{ \Carbon\Carbon::parse($order->penjadwalan)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Layanan</label>
                                <input type="text" name="jenis_layanan" value="{{ $order->jenis_layanan }}" class="form-input" placeholder="Ex: Buang Puing, Urug Tanah">
                                <p class="text-xs text-slate-400 mt-1"><i class="fa-solid fa-circle-info mr-1"></i>Gunakan koma (,) untuk menambahkan layanan lebih dari satu.</p>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Armada</label>
                                <div class="relative">
                                    <select name="armada" class="form-input appearance-none pr-10">
                                        <option value="Pickup" {{ $order->armada == 'Pickup' ? 'selected' : '' }}>Unit Pickup</option>
                                        <option value="Truk Dump" {{ $order->armada == 'Truk Dump' ? 'selected' : '' }}>Truk Dump</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <i class="fa-solid fa-chevron-down text-sm"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2 py-2"><hr class="border-slate-100"></div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Rit</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                        <i class="fa-solid fa-truck-ramp-box"></i>
                                    </div>
                                    <input type="number" id="jumlah_rit" name="jumlah_rit" value="{{ $order->jumlah_rit }}" class="form-input form-input-icon-left hitung-otomatis" required min="0">
                                </div>
                            </div>

                            <div class="hidden md:block"></div> 

                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Per Rit (Rp)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold">
                                        Rp
                                    </div>
                                    <input type="number" id="harga_per_rit" name="harga_per_rit" value="{{ $order->harga_per_rit }}" class="form-input form-input-icon-left hitung-otomatis" required min="0">
                                </div>
                            </div>

                            <div class="md:col-span-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">HPP Per Rit (Modal - Rp)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400 font-bold">
                                        Rp
                                    </div>
                                    <input type="number" id="hpp_per_rit" name="hpp_per_rit" value="{{ $order->hpp_per_rit }}" class="form-input form-input-icon-left hitung-otomatis" required min="0">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Khusus</label>
                                <textarea name="catatan" rows="2" class="form-input">{{ $order->catatan }}</textarea>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-8">
                            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5">
                                <p class="text-blue-600 font-semibold text-sm mb-1 uppercase tracking-wider">Estimasi Pendapatan</p>
                                <h3 class="text-3xl font-bold text-blue-900" id="hasil_pendapatan">Rp 0</h3>
                                <p class="text-xs text-slate-500 mt-2">(Jumlah Rit &times; Harga per Rit)</p>
                            </div>
                            <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5">
                                <p class="text-emerald-600 font-semibold text-sm mb-1 uppercase tracking-wider">Estimasi Laba Bersih</p>
                                <h3 class="text-3xl font-bold text-emerald-900" id="hasil_keuntungan">Rp 0</h3>
                                <p class="text-xs text-slate-500 mt-2">(Pendapatan &minus; Total HPP)</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                            <a href="{{ route('admin.rekap_order') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 text-center hover:bg-slate-50 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-[#00C264] hover:bg-[#009B4E] text-white rounded-xl text-sm font-bold shadow-[0_8px_20px_rgba(0,194,100,0.2)] transition-all transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Rekap
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <footer class="mt-auto pt-10 text-center text-sm font-medium text-slate-400 pb-4">
                &copy; {{ date('Y') }} Juragan Urug. Dashboard Admin.
            </footer>
        </div>
    </main>

    <script>
        // SCRIPT SIDEBAR MOBILE
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        openSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        });

        closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // SCRIPT HITUNG OTOMATIS RIT x HARGA
        const inputRit = document.getElementById('jumlah_rit');
        const inputHarga = document.getElementById('harga_per_rit');
        const inputHpp = document.getElementById('hpp_per_rit');
        const txtPendapatan = document.getElementById('hasil_pendapatan');
        const txtKeuntungan = document.getElementById('hasil_keuntungan');

        function hitungOtomatis() {
            const rit = parseFloat(inputRit.value) || 0;
            const harga = parseFloat(inputHarga.value) || 0;
            const hpp = parseFloat(inputHpp.value) || 0;

            const pendapatan = rit * harga;
            const keuntungan = pendapatan - (rit * hpp);

            // Format angka ke mata uang Rupiah
            txtPendapatan.innerText = 'Rp ' + pendapatan.toLocaleString('id-ID');
            txtKeuntungan.innerText = 'Rp ' + keuntungan.toLocaleString('id-ID');
        }

        // Panggil fungsi saat halaman pertama kali dimuat (menampilkan nilai lama jika ada)
        hitungOtomatis();

        // Panggil fungsi secara realtime setiap kali admin mengetik/mengubah angka
        document.querySelectorAll('.hitung-otomatis').forEach(input => {
            input.addEventListener('input', hitungOtomatis);
        });
    </script>
</body>
</html>