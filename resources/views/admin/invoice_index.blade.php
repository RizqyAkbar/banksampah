<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice - Admin Juragan Urug</title>

    <link rel="icon" href="{{ asset('images/logoclient.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        .font-heading { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
        
        @media print {
            body * { visibility: hidden; }
            #printableArea, #printableArea * { visibility: visible; }
            #printableArea { position: absolute; left: 0; top: 0; width: 100%; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800 antialiased" x-data="invoiceModal()">

    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm transition-opacity lg:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white border-r border-slate-100 shadow-[4px_0_24px_rgba(0,0,0,0.02)] transition-transform duration-300 ease-in-out -translate-x-full lg:static lg:translate-x-0">
        <div class="flex h-20 items-center justify-between px-6 border-b border-slate-50">
            <a href="#" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50">
                    <img src="{{ asset('images/logoclient.png') }}" alt="Logo" class="h-6 w-auto object-contain">
                </div>
                <span class="font-heading text-xl font-bold text-slate-900 tracking-tight">Juragan<span class="text-green-600">Urug</span></span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-1.5 text-sm">
            <p class="px-4 pb-2 text-xs font-bold uppercase tracking-wider text-slate-400">Menu Utama</p>
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 font-medium text-slate-600 hover:bg-slate-50">
                <i class="fa-solid fa-chart-pie w-5 text-center text-lg"></i> Dashboard
            </a>
            <a href="{{ route('admin.input_order') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 font-medium text-slate-600 hover:bg-slate-50">
                <i class="fa-solid fa-cart-plus w-5 text-center text-lg"></i> Input Order
            </a>
            <a href="{{ route('admin.rekap_order') }}" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 font-medium text-slate-600 hover:bg-slate-50">
                <i class="fa-solid fa-list-ul w-5 text-center text-lg"></i> Rekap Order
            </a>
            <p class="px-4 pb-2 pt-6 text-xs font-bold uppercase tracking-wider text-slate-400">Keuangan</p>
            <a href="{{ route('admin.invoice_index') }}" class="flex items-center gap-3 rounded-xl bg-green-50 px-4 py-3.5 text-sm font-semibold text-green-700">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-lg"></i> Create Invoice
            </a>
            <a href="#" class="group flex items-center gap-3 rounded-xl px-4 py-3.5 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-wallet w-5 text-center text-lg text-slate-400 group-hover:text-green-500 transition-colors"></i>
                Payment
            </a>
        </nav>

        <div class="border-t border-slate-100 p-4">
            <div class="flex items-center gap-3 rounded-xl p-3 transition-colors hover:bg-slate-50">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-600 font-bold">A</div>
                <div class="flex-1 overflow-hidden">
                    <p class="truncate text-sm font-bold text-slate-900">Admin Juragan Urug</p>
                    <p class="truncate text-xs text-slate-500">admin@juraganurug.com</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors cursor-pointer"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </form>
            </div>
        </div> </aside>

    <main class="flex flex-1 flex-col overflow-hidden relative">
        <header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/80 px-6 backdrop-blur-md z-30">
            <h1 class="font-heading text-2xl font-bold text-slate-900">Penagihan (Invoice)</h1>
        </header>

        <div class="flex flex-col flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-lg font-bold text-slate-900">Daftar Order Pelanggan</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-center">No</th>
                                <th class="px-6 py-4">Pelanggan & Alamat</th>
                                <th class="px-6 py-4">Layanan</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($orders as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-slate-900">
                                    <p class="font-bold">{{ $item->nama_pelanggan }}</p>
                                    <p class="text-xs text-slate-400 truncate max-w-[180px]">{{ $item->alamat }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium">{{ $item->jenis_layanan }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->jumlah_rit }} Rit | {{ $item->armada }}</p>
                                </td>
                                <td class="px-6 py-4 font-bold text-green-600">
                                    Rp {{ number_format($item->jumlah_pendapatan, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" @click="openModal({{ $item->id }})" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-900 hover:text-white transition-all">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </button>
                                        <a href="{{ route('admin.invoice.print', $item->id) }}" target="_blank" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all" title="Download PDF">
                                            <i class="fa-solid fa-file-pdf text-sm"></i>
                                        </a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_wa) }}?text=Halo%20{{ urlencode($item->nama_pelanggan) }},%20berikut%20invoice%20anda." target="_blank" class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white">
                                            <i class="fa-brands fa-whatsapp text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.rekap_order.destroy', $item->id) }}" method="POST" class="m-0 p-0 form-hapus">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-hapus p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data tagihan.</td></tr>
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

    <template x-if="isModalOpen">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition x-cloak>
            <div @click.away="isModalOpen = false" class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-800">Preview Invoice</h3>
                    <button @click="isModalOpen = false" class="text-slate-400 hover:text-red-500 text-xl font-bold">&times;</button>
                </div>
                <div id="printableArea" class="p-8 overflow-y-auto flex-1 bg-white">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 font-heading">INVOICE</h2>
                            <p class="text-slate-400 text-sm" x-text="invoiceData.no_invoice"></p>
                        </div>
                        <div class="text-right">
                            <h4 class="font-bold text-green-600 italic text-xl">JuraganUrug</h4>
                            <p class="text-[10px] text-slate-400" x-text="invoiceData.tanggal"></p>
                        </div>
                    </div>
                    <div class="mb-8">
                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-widest">Pelanggan:</p>
                        <p class="font-bold text-slate-800 text-lg" x-text="invoiceData.data?.nama_pelanggan"></p>
                        <p class="text-xs text-slate-500" x-text="invoiceData.data?.alamat"></p>
                    </div>
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b-2 border-slate-100">
                                <th class="py-2 text-slate-700">Layanan</th>
                                <th class="py-2 text-center text-slate-700">Rit</th>
                                <th class="py-2 text-right text-slate-700">Harga</th>
                                <th class="py-2 text-right text-slate-700">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-50">
                                <td class="py-4">
                                    <p class="font-bold text-slate-800" x-text="invoiceData.data?.jenis_layanan"></p>
                                    <p class="text-[10px] text-slate-400" x-text="'Armada: ' + invoiceData.data?.armada"></p>
                                </td>
                                <td class="py-4 text-center text-slate-800 font-medium" x-text="invoiceData.data?.jumlah_rit"></td>
                                <td class="py-4 text-right text-slate-800" x-text="formatRupiah(invoiceData.data?.harga_per_rit)"></td>
                                <td class="py-4 text-right font-bold text-slate-900" x-text="formatRupiah(invoiceData.data?.jumlah_pendapatan)"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-8 text-right bg-slate-50 p-4 rounded-xl">
                        <p class="text-xs text-slate-500 font-medium mb-1">Total Pembayaran:</p>
                        <p class="text-2xl font-bold text-green-600" x-text="formatRupiah(invoiceData.data?.jumlah_pendapatan)"></p>
                    </div>
                </div>
                <div class="p-6 border-t bg-slate-50 flex gap-3 justify-end">
                    <button @click="isModalOpen = false" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200 rounded-xl transition">Tutup</button>
                </div>
            </div>
        </div>
    </template>

    <script>
        function invoiceModal() {
            return {
                isModalOpen: false,
                invoiceData: {},
                async openModal(id) {
                    try {
                        // Gunakan placeholder :id agar Laravel tidak bingung saat render PHP
                        let url = "{{ route('admin.order.detail', ':id') }}";
                        url = url.replace(':id', id); // Ganti :id dengan ID asli pelanggan yang diklik

                        const response = await fetch(url);
                        if (!response.ok) throw new Error('Gagal memuat data');
                        
                        const data = await response.json();
                        this.invoiceData = data;
                        this.isModalOpen = true;
                    } catch (error) {
                        console.error('Fetch error:', error);
                        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat detail invoice!', heightAuto: false });
                    }
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number || 0);
                }
            }
        }

        // Script Hapus
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-hapus')) {
                const form = e.target.closest('.form-hapus');
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Data invoice ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Hapus!',
                    heightAuto: false,
                    reverseButtons: true
                }).then((result) => { if (result.isConfirmed) form.submit(); })
            }
        });
    </script>
</body>
</html>