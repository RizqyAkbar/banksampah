@extends('admin.layout.app')

@section('title', 'Create Invoice')
@section('header_title', 'Penagihan (Invoice)')

@section('extra_css')
    <style>
        /* Style khusus preview invoice saat print */
        @media print {
            body * { visibility: hidden; }
            #printableArea, #printableArea * { visibility: visible; }
            #printableArea { position: absolute; left: 0; top: 0; width: 100%; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
@endsection

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Daftar Invoice</h1>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-900">Daftar Order Pelanggan</h2>
            <p class="text-xs text-slate-500 mt-1">Kelola penagihan dan pengiriman invoice ke pelanggan.</p>
        </div>

        <div class="overflow-x-auto" x-data="invoiceModal()">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan & Alamat</th>
                        <th class="px-6 py-4 font-semibold">Layanan</th>
                        <th class="px-6 py-4 font-semibold">Total Tagihan</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $item->nama_pelanggan }}</p>
                            <p class="text-xs text-slate-400 truncate max-w-[180px]">{{ $item->alamat }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800">{{ $item->jenis_layanan }}</p>
                            <p class="text-xs text-slate-500">{{ $item->jumlah_rit }} Rit | {{ $item->armada }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-green-600">Rp {{ number_format($item->jumlah_pendapatan, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openModal({{ $item->id }})" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-900 hover:text-white transition-all">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                                <a href="{{ route('admin.invoice.print', $item->id) }}" target="_blank" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
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
                    <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400 italic">Belum ada data tagihan.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <template x-if="isModalOpen">
                <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition x-cloak>
                    <div @click.away="isModalOpen = false" class="bg-white rounded-[2rem] shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                        <div class="px-6 py-4 border-b flex justify-between items-center bg-slate-50">
                            <h3 class="font-bold text-slate-800 font-heading">Preview Invoice</h3>
                            <button @click="isModalOpen = false" class="text-slate-400 hover:text-red-500 text-2xl">&times;</button>
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
                                    <tr class="border-b-2 border-slate-100 text-slate-400 uppercase">
                                        <th class="py-2">Layanan</th>
                                        <th class="py-2 text-center">Rit</th>
                                        <th class="py-2 text-right">Harga</th>
                                        <th class="py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-slate-50">
                                        <td class="py-4">
                                            <p class="font-bold text-slate-800" x-text="invoiceData.data?.jenis_layanan"></p>
                                            <p class="text-[10px] text-slate-400" x-text="'Unit: ' + invoiceData.data?.armada"></p>
                                        </td>
                                        <td class="py-4 text-center text-slate-800 font-medium" x-text="invoiceData.data?.jumlah_rit"></td>
                                        <td class="py-4 text-right text-slate-800" x-text="formatRupiah(invoiceData.data?.harga_per_rit)"></td>
                                        <td class="py-4 text-right font-bold text-slate-900" x-text="formatRupiah(invoiceData.data?.jumlah_pendapatan)"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-8 text-right bg-slate-50 p-6 rounded-2xl">
                                <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-widest">Total Pembayaran</p>
                                <p class="text-3xl font-bold text-green-600" x-text="formatRupiah(invoiceData.data?.jumlah_pendapatan)"></p>
                            </div>
                        </div>
                        <div class="p-6 border-t bg-slate-50 flex gap-3 justify-end no-print">
                            <button @click="isModalOpen = false" class="px-6 py-2.5 text-sm font-bold text-slate-400 hover:bg-slate-200 rounded-xl transition">Tutup</button>
                            <button @click="window.print()" class="px-6 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg flex items-center gap-2 hover:bg-slate-800">
                                <i class="fa-solid fa-print"></i> Print Invoice
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endsection

@section('extra_js')
    <script>
        function invoiceModal() {
            return {
                isModalOpen: false,
                invoiceData: {},
                async openModal(id) {
                    try {
                        let url = "{{ route('admin.order.detail', ':id') }}";
                        url = url.replace(':id', id);
                        const response = await fetch(url);
                        if (!response.ok) throw new Error('Network error');
                        const data = await response.json();
                        this.invoiceData = data;
                        this.isModalOpen = true;
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat detail!', heightAuto: false });
                    }
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number || 0);
                }
            }
        }

        // Logic Hapus
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
@endsection