@extends('admin.layout.app')

@section('title', 'Rekap Order')
@section('header_title', 'Data Rekap Order')

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Rekap Order</h1>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-900">Daftar Order Aktif</h2>
            <p class="text-xs text-slate-500 italic">*Order yang dibatalkan akan masuk ke menu Monitoring Cancel</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-center w-16">No</th>
                        <th class="px-6 py-4 font-semibold">Tgl Penjadwalan</th>
                        <th class="px-6 py-4 font-semibold">Pelanggan & Lokasi</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold">Detail Armada & Layanan</th>
                        <th class="px-6 py-4 font-semibold">Pendapatan</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders->whereIn('status_order', ['Aktif', null, '']) as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-500 text-center">{{ $loop->iteration }}</td>
                        
                        <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->penjadwalan)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-bold text-slate-900 leading-tight">{{ $item->nama_pelanggan }}</p>
                                    <a href="https://wa.me/{{ $item->no_wa }}" target="_blank" class="text-xs text-green-600 hover:text-green-700 font-medium flex items-center gap-1 mt-1">
                                        <i class="fa-brands fa-whatsapp"></i> +{{ $item->no_wa }}
                                    </a>
                                    {{-- Menampilkan Alamat Singkat/Catatan dari Input Order --}}
                                    @if($item->alamat)
                                        <p class="text-[10px] text-slate-400 mt-1 line-clamp-1"><i class="fa-solid fa-map-pin mr-1"></i>{{ $item->alamat }}</p>
                                    @endif
                                </div>
                                @if($item->link_maps)
                                <a href="{{ $item->link_maps }}" target="_blank" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Buka Google Maps">
                                    <i class="fa-solid fa-location-dot text-sm"></i>
                                </a>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($item->status_pelanggan == 'Pelanggan' || $item->status_pelanggan == 'Pelanggan Lama')
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700 border border-blue-100 uppercase tracking-wider">
                                    {{ $item->status_pelanggan }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-bold text-green-700 border border-green-100 uppercase tracking-wider">
                                    Baru
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <p class="font-bold text-slate-800 leading-tight">
                                    {{ $item->jenis_layanan }} 
                                    <span class="text-slate-500 font-normal">({{ $item->jumlah_rit ?? 0 }} Rit)</span>
                                </p>
                                
                                <div class="flex flex-wrap gap-x-3 gap-y-1 mt-0.5">
                                    <span class="text-[11px] text-slate-500 flex items-center gap-1.5">
                                        <i class="fa-solid fa-truck-pickup w-3 text-center text-slate-400"></i> 
                                        {{ $item->armada }} 
                                        @if($item->plat_armada) 
                                            <span class="bg-slate-100 px-1.5 py-0.5 rounded text-[10px] font-bold text-slate-700 border border-slate-200 uppercase tracking-tighter">{{ $item->plat_armada }}</span> 
                                        @endif
                                    </span>
                                    
                                    @if($item->nama_sopir)
                                    <span class="text-[11px] text-slate-500 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-tie w-3 text-center text-slate-400"></i> 
                                        {{ $item->nama_sopir }}
                                        
                                        {{-- --- TOMBOL INDIKATOR HISTORY BARU --- --}}
                                        @if($item->logs->count() > 0)
                                            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-orange-100 text-orange-600 animate-pulse" title="Sopir/Armada pernah diganti">
                                                <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                                            </span>
                                        @endif
                                        {{-- ------------------------------------- --}}

                                        @if($item->no_wa_sopir)
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_wa_sopir) }}" target="_blank" class="text-green-600 hover:underline font-bold" title="Hubungi Sopir">
                                                (WA)
                                            </a>
                                        @endif
                                    </span>
                                    @endif
                                </div>

                                {{-- Menampilkan Catatan Khusus jika ada --}}
                                @if($item->catatan)
                                    <p class="text-[10px] text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-100 w-fit mt-1 italic">
                                        *{{ $item->catatan }}
                                    </p>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($item->jumlah_pendapatan, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" 
                                    onclick="confirmCancel('{{ $item->id }}', '{{ $item->nama_pelanggan }}')"
                                    class="inline-flex items-center justify-center rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white w-8 h-8 transition-colors shadow-sm" 
                                    title="Batalkan Pesanan">
                                    <i class="fa-solid fa-ban text-sm"></i>
                                </button>

                                <a href="{{ route('admin.rekap_order.edit', $item->id) }}" class="inline-flex items-center justify-center rounded-lg bg-green-50 text-green-700 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-colors shadow-sm">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit
                                </a>

                                <form action="{{ route('admin.rekap_order.destroy', $item->id) }}" method="POST" class="m-0 p-0 form-hapus">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-hapus inline-flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white w-8 h-8 transition-colors shadow-sm">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data order aktif untuk ditampilkan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Form Tersembunyi untuk Proses Cancel --}}
    <form id="form-cancel-order" method="POST" style="display: none;">
        @csrf @method('PUT')
        <input type="hidden" name="alasan_cancel" id="input-alasan-cancel">
    </form>
@endsection

@section('extra_js')
    <script>
        // Logika SweetAlert Tetap Sama (Cancel, Delete, Success Toast)
        function confirmCancel(orderId, customerName) {
            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: `Alasan pembatalan order ${customerName}:`,
                input: 'textarea',
                inputPlaceholder: 'Contoh: Customer cancel sepihak / Lokasi tidak terjangkau...',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316', 
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali',
                reverseButtons: true,
                heightAuto: false, 
                inputValidator: (value) => {
                    if (!value) { return 'Alasan wajib diisi!' }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-cancel-order');
                    form.action = `/admin/order/${orderId}/cancel`;
                    document.getElementById('input-alasan-cancel').value = result.value;
                    form.submit();
                }
            });
        }

        document.addEventListener('click', function (e) {
            if (e.target.closest('.btn-hapus')) {
                const button = e.target.closest('.btn-hapus');
                const form = button.closest('.form-hapus');
                Swal.fire({
                    title: 'Hapus Permanen?',
                    text: "Data akan dihapus selamanya!",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Hapus!',
                    reverseButtons: true,
                    heightAuto: false
                }).then((result) => { if (result.isConfirmed) form.submit(); });
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}',
                timer: 3000, toast: true, position: 'top-end', showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-lg border border-green-100' }
            });
        @endif
    </script>
@endsection