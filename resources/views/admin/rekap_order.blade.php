@extends('admin.layout.app')

@section('title', 'Rekap Order')
@section('header_title', 'Data Rekap Order')

@section('content')
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
@endsection

@section('extra_js')
    <script>
        // SCRIPT SWEETALERT KONFIRMASI HAPUS
        document.addEventListener('click', function (e) {
            if (e.target.closest('.btn-hapus')) {
                const button = e.target.closest('.btn-hapus');
                const form = button.closest('.form-hapus');
                
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
                    heightAuto: false, 
                    customClass: {
                        confirmButton: 'rounded-lg px-4 py-2 text-sm font-bold',
                        cancelButton: 'rounded-lg px-4 py-2 text-sm font-bold text-slate-700'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });

        // SweetAlert Toast Notification untuk pesan sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                customClass: {
                    popup: 'rounded-2xl shadow-lg border border-green-100',
                    title: 'font-heading font-bold text-slate-900',
                }
            });
        @endif
    </script>
@endsection