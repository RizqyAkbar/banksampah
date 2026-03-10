<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Order - Admin Juragan Urug</title>

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
        
        /* Custom Scrollbar untuk sidebar & main content */
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
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800 antialiased selection:bg-green-100 selection:text-green-900">

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

            <a href="{{ route('admin.input_order') }}" class="flex items-center gap-3 rounded-xl bg-green-50 px-4 py-3.5 text-sm font-semibold text-green-700 transition-colors">
                <i class="fa-solid fa-cart-plus w-5 text-center text-lg"></i>
                Input Order
            </a>

            <a href="{{ route('admin.rekap_order') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-list-ul w-5 text-center text-lg text-slate-400 group-hover:text-green-500 transition-colors"></i>
                Rekap Order
            </a>

            <p class="px-4 pb-2 pt-6 text-xs font-bold uppercase tracking-wider text-slate-400">Keuangan</p>

            <a href="{{ route('admin.invoice_index') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
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
                <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">Input Order Baru</h1>
            </div>

            <div class="flex items-center gap-4 sm:gap-6">
                <div class="hidden items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-600 sm:flex border border-slate-100">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    {{ date('d M Y') }}
                </div>
                <button class="relative rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                    <i class="fa-regular fa-bell text-xl"></i>
                    <span class="absolute right-1.5 top-1.5 flex h-2.5 w-2.5 rounded-full bg-orange-500 ring-2 ring-white"></span>
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            
            <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Input Order</h1>

            <div class="max-w-4xl mx-auto">
                <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    
                    <div class="border-b border-slate-100 bg-slate-50/50 p-6 flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600">
                            <i class="fa-solid fa-file-pen text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Detail Pemesanan</h2>
                            <p class="text-sm text-slate-500">Masukkan informasi pelanggan dan detail layanan dengan lengkap.</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.input_order.store') }}" method="POST" class="p-6 sm:p-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="md:col-span-1">
                                <label for="nama_pelanggan" class="mb-2 block text-sm font-semibold text-slate-700">Nama Pelanggan <span class="text-red-500">*</span></label>
                                <input type="text" id="nama_pelanggan" name="nama_pelanggan" placeholder="Ex: Bapak Budi Santoso" class="form-input" required>
                            </div>

                            <div class="md:col-span-1">
                                <label for="no_wa" class="mb-2 block text-sm font-semibold text-slate-700">No. WhatsApp <span class="text-red-500">*</span></label>
                                <input type="tel" id="no_wa" name="no_wa" placeholder="Ex: 081234567890" class="form-input" required>
                                <p class="mt-1.5 text-xs text-slate-400"><i class="fa-solid fa-circle-info mr-1"></i>Jika nomor sama, sistem akan menggabungkan ke 1 rekapan otomatis.</p>
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap penjemputan/pengiriman..." class="form-input" required></textarea>
                            </div>

                            <div class="md:col-span-2 py-2">
                                <hr class="border-slate-100">
                            </div>

                            <div class="md:col-span-1">
                                <label for="penjadwalan" class="mb-2 block text-sm font-semibold text-slate-700">Penjadwalan (Tanggal) <span class="text-red-500">*</span></label>
                                <input type="date" id="penjadwalan" name="penjadwalan" class="form-input" required>
                            </div>

                            <div class="md:col-span-1">
                                <label for="jenis_layanan" class="mb-2 block text-sm font-semibold text-slate-700">Jenis Layanan <span class="text-red-500">*</span></label>
                                
                                <div class="relative mb-2">
                                    <select id="jenis_layanan" name="jenis_layanan" class="form-input appearance-none pr-10" required onchange="toggleCustomLayanan(this)">
                                        <option value="" disabled selected hidden>Pilih Jenis Layanan</option>
                                        <option value="Buang Puing">Buang Puing</option>
                                        <option value="Buang Sampah">Buang Sampah</option>
                                        <option value="Urug Puing">Urug Puing</option>
                                        <option value="Urug Tanah">Urug Tanah</option>
                                        <option value="Material">Kirim Material</option>
                                        <option value="Lainnya">Lainnya... (Tulis Sendiri)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <i class="fa-solid fa-chevron-down text-sm"></i>
                                    </div>
                                </div>
                                
                                <div id="input_layanan_custom_container" class="hidden animate-fade-in-down">
                                    <input type="text" id="jenis_layanan_custom" name="jenis_layanan_custom" placeholder="Ketik layanan kustom di sini..." class="form-input border-dashed border-slate-300 focus:border-green-500">
                                </div>
                            </div>

                            <div class="md:col-span-1">
                                <label for="armada" class="mb-2 block text-sm font-semibold text-slate-700">Armada <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select id="armada" name="armada" class="form-input appearance-none pr-10" required>
                                        <option value="" disabled selected hidden>Pilih Armada</option>
                                        <option value="Pickup">Unit Pickup</option>
                                        <option value="Truk Dump">Truk Dump</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <i class="fa-solid fa-chevron-down text-sm"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="catatan" class="mb-2 block text-sm font-semibold text-slate-700">Catatan Khusus <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                                <textarea id="catatan" name="catatan" rows="2" placeholder="Ex: Request diambil sebelum jam 3 sore..." class="form-input"></textarea>
                            </div>

                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100">
                            <button type="reset" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                Bersihkan Form
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#00C264] text-white text-sm font-semibold hover:bg-[#009B4E] shadow-[0_8px_20px_rgba(0,194,100,0.2)] transition-all transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Order
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>

            <footer class="mt-10 text-center text-sm font-medium text-slate-400 pb-4">
                &copy; {{ date('Y') }} Juragan Urug. Dashboard Admin.
            </footer>

        </div>
    </main>

    <script>
        // Logika memunculkan input kustom untuk "Lainnya"
        function toggleCustomLayanan(selectElement) {
            const customContainer = document.getElementById('input_layanan_custom_container');
            const customInput = document.getElementById('jenis_layanan_custom');
            
            if (selectElement.value === 'Lainnya') {
                customContainer.classList.remove('hidden');
                customInput.setAttribute('required', 'required'); // Wajib diisi jika pilih lainnya
                customInput.focus(); // Langsung pindahkan kursor
            } else {
                customContainer.classList.add('hidden');
                customInput.removeAttribute('required');
                customInput.value = ''; // Bersihkan input kustom jika user mengganti pilihan
            }
        }

        // Script Sidebar Mobile Toggle
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }

        openSidebarBtn.addEventListener('click', openSidebar);
        closeSidebarBtn.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
    @endif

</body>
</html>

