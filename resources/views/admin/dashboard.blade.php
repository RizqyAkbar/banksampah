<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Juragan Urug</title>

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
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
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
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl bg-green-50 px-4 py-3.5 text-sm font-semibold text-green-700 transition-colors">
                <i class="fa-solid fa-chart-pie w-5 text-center text-lg"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.input_order') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-cart-plus w-5 text-center text-lg text-slate-400 group-hover:text-green-500 transition-colors"></i>
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
            <div class="flex items-center gap-3 rounded-xl p-3 transition-colors hover:bg-slate-50">
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
                <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">Dashboard Overview</h1>
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

        <div class="flex flex-col flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            
            <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Dashboard</h1>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 mb-8">
                
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pendapatan (Bulan Ini)</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600">
                            <i class="fa-solid fa-sack-dollar text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold text-slate-900">Rp {{ number_format($stats->total_pendapatan ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Omzet kotor masuk</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Keuntungan (Bulan Ini)</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                            <i class="fa-solid fa-chart-line text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-xl font-bold text-emerald-600">Rp {{ number_format($stats->total_keuntungan ?? 0, 0, ',', '.') }}</h3>
                        <p class="text-xs text-slate-400 mt-1">Laba bersih (Murni)</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pelanggan Baru</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <i class="fa-solid fa-user-plus text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-900">{{ $pelangganBaru }} <span class="text-sm font-normal text-slate-500">Orang</span></h3>
                        <p class="text-xs text-slate-400 mt-1">Bulan ini</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Pelanggan</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                            <i class="fa-solid fa-users text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-slate-900">{{ $totalPelanggan }} <span class="text-sm font-normal text-slate-500">Orang</span></h3>
                        <p class="text-xs text-slate-400 mt-1">Keseluruhan (Unik)</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Belum Lengkap</p>
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600">
                            <i class="fa-solid fa-file-circle-exclamation text-lg"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <h3 class="text-2xl font-bold text-red-600">{{ $belumBayar }} <span class="text-sm font-normal text-red-400">Order</span></h3>
                        <p class="text-xs text-red-400 mt-1">Butuh rekap data</p>
                    </div>
                </div>

            </div>

            <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-100 p-6 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                        </div>
                        <h2 class="text-lg font-bold text-slate-900">Jadwal Hari Ini <span class="text-xs font-normal text-slate-400 ml-1">(Realtime)</span></h2>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold bg-slate-50 text-slate-600 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-arrows-rotate mr-2"></i>Refresh
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-y border-slate-100">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Pelanggan</th>
                                <th class="px-6 py-4 font-semibold">Layanan / Armada</th>
                                <th class="px-6 py-4 font-semibold">Lokasi</th>
                                <th class="px-6 py-4 font-semibold">Status Data</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($jadwalHariIni as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $order->nama_pelanggan }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->no_wa }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $order->jenis_layanan }}</p>
                                    <p class="text-xs text-slate-500">{{ $order->armada }}</p>
                                </td>
                                <td class="px-6 py-4 truncate max-w-[200px]">{{ $order->alamat }}</td>
                                <td class="px-6 py-4">
                                    @if($order->jumlah_pendapatan > 0)
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100">
                                            <i class="fa-solid fa-check mr-1.5"></i> Rekap Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700 border border-orange-100">
                                            <i class="fa-solid fa-clock mr-1.5"></i> Tunggu Rekap
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('admin.rekap_order.edit', $order->id) }}" class="text-slate-400 hover:text-green-600" title="Lengkapi Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">
                                    Tidak ada jadwal pesanan untuk hari ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <footer class="mt-auto pt-10 text-center text-sm font-medium text-slate-400 pb-4">
                &copy; {{ date('Y') }} Juragan Urug. Dashboard Admin.
            </footer>

        </div>
    </main>

    <script>
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
</body>
</html>