<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Order - Admin Juragan Urug</title>

    <link rel="icon" href="{{ asset('images/logoclient.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        .font-heading { font-family: 'Poppins', sans-serif; }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
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
                <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">Data Rekap Order</h1>
            </div>
        </header>

        <div class="flex flex-col flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Rekap Order</h1>

            <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-900">Daftar Order Pelanggan</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                                <th class="px-6 py-4 font-semibold">Tgl Penjadwalan</th>
                                <th class="px-6 py-4 font-semibold">Pelanggan</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Layanan & Rit</th>
                                <th class="px-6 py-4 font-semibold">Pendapatan</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            
                            @forelse($orders as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-slate-500 text-center">
                                    {{ $loop->iteration }}
                                </td>
                                
                                <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->penjadwalan)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-900">{{ $item->nama_pelanggan }}</p>
                                    <p class="text-xs text-slate-400"><i class="fa-brands fa-whatsapp text-green-500 mr-1"></i>{{ $item->no_wa }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->status_pelanggan == 'Pelanggan Lama')
                                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-100">Lama</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 border border-green-100">Baru</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $item->jenis_layanan }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->armada }} | <strong>{{ $item->jumlah_rit }} Rit</strong></p>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">
                                    Rp {{ number_format($item->jumlah_pendapatan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.rekap_order.destroy', $item->id) }}" method="POST" class="m-0 p-0 form-hapus">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-hapus inline-flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white w-8 h-8 transition-colors" title="Hapus Order">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.rekap_order.edit', $item->id) }}" class="inline-flex items-center justify-center rounded-lg bg-green-50 text-green-700 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-colors">
                                            <i class="fa-solid fa-pen-to-square mr-1.5"></i> Lengkapi Rekap
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                    Belum ada data order yang masuk.
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
        // SCRIPT SIDEBAR
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

        // SCRIPT SWEETALERT KONFIRMASI HAPUS
        const deleteButtons = document.querySelectorAll('.btn-hapus');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Mengambil form terdekat dari tombol yang diklik
                const form = this.closest('.form-hapus');
                
                Swal.fire({
                    title: 'Hapus Order?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#cbd5e1', 
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, 
                    
                    // TAMBAHKAN BARIS INI: Mencegah body terangkat
                    heightAuto: false, 
                    
                    customClass: {
                        confirmButton: 'rounded-lg px-4 py-2 text-sm font-bold',
                        cancelButton: 'rounded-lg px-4 py-2 text-sm font-bold text-slate-700'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    </script>
    
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