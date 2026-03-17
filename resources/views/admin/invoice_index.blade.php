@extends('admin.layout.app')

@section('title', 'Create Invoice')
@section('header_title', 'Penagihan (Invoice)')

@section('extra_css')
    <style>
        @media print {
            body * { visibility: hidden; }
            #printableArea, #printableArea * { visibility: visible; }
            #printableArea { position: absolute; left: 0; top: 0; width: 100%; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
@endsection

@section('content')
<h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Invoice</h1>
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
                        <th class="px-6 py-4 font-semibold">Daftar Layanan</th>
                        <th class="px-6 py-4 font-semibold">Total Tagihan</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900 leading-tight">{{ $item->nama_pelanggan }}</p>
                            <p class="text-[11px] text-slate-400 mt-1 truncate max-w-[180px]">{{ $item->alamat }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2 max-w-[300px]">
                                @foreach($item->items->where('status_item', 'Aktif') as $service)
                                    <span class="inline-flex flex-col bg-slate-100 border border-slate-200 px-2.5 py-1.5 rounded-lg min-w-[120px]">
                                        <span class="text-[10px] font-bold text-slate-800 leading-none mb-1">{{ $service->jenis_layanan }}</span>
                                        <span class="text-[9px] text-slate-500 leading-none">{{ $service->jumlah_rit }} Rit | {{ $service->armada }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-green-600">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="openModal({{ $item->id }})" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </button>
                                
                                <a href="{{ route('admin.invoice.print', $item->id) }}" target="_blank" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-file-pdf text-sm"></i>
                                </a>

                                <button type="button" @click="downloadInvoice({{ $item->id }})" class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-print text-sm"></i>
                                </button>

                                @php
                                    $pesanWA = "Halo *" . $item->nama_pelanggan . "*,\n\n";
                                    $pesanWA .= "Berikut adalah rincian tagihan Anda dari *Juragan Urug*:\n";
                                    foreach($item->items->where('status_item', 'Aktif') as $srv) {
                                        $pesanWA .= "- " . $srv->jenis_layanan . " (" . $srv->jumlah_rit . " Rit): Rp " . number_format($srv->jumlah_rit * $srv->harga_per_rit, 0, ',', '.') . "\n";
                                    }
                                    $pesanWA .= "\n*Total Tagihan: Rp " . number_format($item->total_pendapatan, 0, ',', '.') . "*\n\n";
                                    $pesanWA .= "Terima kasih atas kepercayaan Anda.";
                                @endphp

                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_wa) }}?text={{ urlencode($pesanWA) }}" target="_blank" class="p-2 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                </a>

                                <form action="{{ route('admin.rekap_order.destroy', $item->id) }}" method="POST" class="m-0 p-0 form-hapus">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-hapus p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
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
                                    <h2 class="text-2xl font-bold text-slate-900 font-heading tracking-tighter">INVOICE</h2>
                                    <p class="text-slate-400 text-[10px]" x-text="'Generated on: ' + invoiceData.tanggal"></p>
                                </div>
                                <div class="text-right">
                                    <h4 class="font-bold text-green-600 italic text-xl leading-none">JuraganUrug</h4>
                                    <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest">Official Invoice</p>
                                </div>
                            </div>
                            <div class="mb-8">
                                <p class="text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-widest">Kepada Yth:</p>
                                <p class="font-bold text-slate-800 text-lg" x-text="invoiceData.data?.nama_pelanggan"></p>
                                <p class="text-xs text-slate-500" x-text="invoiceData.data?.alamat"></p>
                            </div>
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="border-b-2 border-slate-100 text-slate-400 uppercase">
                                    <tr>
                                        <th class="py-2">Layanan</th>
                                        <th class="py-2 text-center">Rit</th>
                                        <th class="py-2 text-right">Harga</th>
                                        <th class="py-2 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in invoiceData.items" :key="item.id">
                                        <tr class="border-b border-slate-50">
                                            <td class="py-4">
                                                <p class="font-bold text-slate-800" x-text="item.jenis_layanan"></p>
                                                <p class="text-[10px] text-slate-400" x-text="'Unit: ' + item.armada"></p>
                                            </td>
                                            <td class="py-4 text-center text-slate-800 font-medium" x-text="item.jumlah_rit"></td>
                                            <td class="py-4 text-right text-slate-800" x-text="formatRupiah(item.harga_per_rit)"></td>
                                            <td class="py-4 text-right font-bold text-slate-900" x-text="formatRupiah(item.jumlah_rit * item.harga_per_rit)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div class="mt-8 text-right bg-slate-50 p-6 rounded-2xl">
                                <p class="text-xs text-slate-500 font-medium mb-1 uppercase tracking-widest">Total Pembayaran</p>
                                <p class="text-3xl font-bold text-green-600" x-text="formatRupiah(invoiceData.data?.total_pendapatan)"></p>
                            </div>
                        </div>
                        <div class="p-6 border-t bg-slate-50 flex gap-3 justify-end no-print">
                            <button @click="isModalOpen = false" class="px-6 py-2.5 text-sm font-bold text-slate-400 hover:bg-slate-200 rounded-xl transition">Tutup</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->links() }}
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
                        const data = await response.json();
                        this.invoiceData = data;
                        this.isModalOpen = true;
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat detail!' });
                    }
                },
                
                downloadInvoice(id) {
                    Swal.fire({ 
                        title: 'Memproses PDF...', 
                        text: 'Mohon tunggu, ini membutuhkan waktu beberapa detik.',
                        allowOutsideClick: false, 
                        didOpen: () => { Swal.showLoading(); } 
                    });
                    
                    let url = "{{ route('admin.invoice.print', ':id') }}?mode=download";
                    url = url.replace(':id', id);
                    
                    // Bersihkan iframe lama
                    const oldIframe = document.getElementById('pdf-iframe');
                    if (oldIframe) oldIframe.remove();

                    // KUNCI PERBAIKAN: JANGAN pakai display: none. 
                    // Kita geser keluar layar agar browser tetap me-render isinya.
                    const iframe = document.createElement('iframe');
                    iframe.id = 'pdf-iframe';
                    iframe.style.position = 'fixed';
                    iframe.style.right = '-9999px';
                    iframe.style.bottom = '-9999px';
                    iframe.style.width = '1024px'; // Wajib ada ukuran
                    iframe.style.height = '100vh'; // Wajib ada ukuran
                    iframe.style.opacity = '0';
                    iframe.style.pointerEvents = 'none';
                    iframe.src = url;
                    
                    document.body.appendChild(iframe);

                    // Dengarkan pesan dari iframe jika download sudah selesai
                    const messageListener = function(event) {
                        if (event.data === 'download_complete') {
                            Swal.close();
                            window.removeEventListener('message', messageListener);
                            setTimeout(() => iframe.remove(), 1000);
                            
                            Swal.fire({
                                toast: true, position: 'top-end', icon: 'success',
                                title: 'Berhasil didownload!', showConfirmButton: false, timer: 3000
                            });
                        }
                    };
                    window.addEventListener('message', messageListener);
                    
                    // Fallback jika terjadi error terlalu lama
                    setTimeout(() => {
                        if (Swal.isVisible()) {
                            Swal.close();
                            window.removeEventListener('message', messageListener);
                            iframe.remove();
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Koneksi lambat, silakan coba lagi.' });
                        }
                    }, 12000);
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number || 0);
                }
            }
        }

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
                    reverseButtons: true,
                    heightAuto: false
                }).then((result) => { if (result.isConfirmed) form.submit(); })
            }
        });
    </script>
@endsection