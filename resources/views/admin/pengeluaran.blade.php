@extends('admin.layout.app')

@section('title', 'Daftar Pengeluaran')
@section('header_title', 'Data Pengeluaran')

@section('content')
    {{-- REVISI: flex-col di mobile agar Judul dan Tombol tidak saling gencet --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h1 class="font-heading text-2xl font-bold text-slate-900">Daftar Pengeluaran</h1>
        <a href="{{ route('admin.pengeluaran.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-2.5 text-sm font-bold text-white shadow-[0_8px_20px_rgba(22,163,74,0.2)] hover:bg-green-700 transition-all active:scale-95">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Pengeluaran
        </a>
    </div>

    <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-900">Riwayat Pengeluaran</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                    <tr>
                        {{-- REVISI: Penambahan whitespace-nowrap & min-w agar tabel scrollable dan rapi di mobile --}}
                        <th class="px-6 py-4 font-semibold text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[120px]">Tanggal</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[120px]">Kategori</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[200px]">Keterangan</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap min-w-[150px]">Nominal</th>
                        <th class="px-6 py-4 font-semibold text-right whitespace-nowrap min-w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengeluarans as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-500 text-center">
                            {{ $loop->iteration }}
                        </td>
                        
                        <td class="px-6 py-4 font-medium text-slate-900 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-6 py-4">
                            @if($item->kategori == 'Operasional')
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 border border-blue-100 whitespace-nowrap">Operasional</span>
                            @elseif($item->kategori == 'Cashback')
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-100 whitespace-nowrap">Cashback</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 border border-slate-200 whitespace-nowrap">{{ $item->kategori }}</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-slate-600 italic line-clamp-2 min-w-[200px]">{{ $item->keterangan ?? '-' }}</p>
                        </td>

                        <td class="px-6 py-4 font-bold text-rose-600 whitespace-nowrap">
                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            {{-- REVISI: flex-nowrap agar tombol Edit & Hapus tidak turun baris di mobile --}}
                            <div class="flex flex-nowrap items-center justify-end gap-2">
                                {{-- FORM HAPUS --}}
                                <form action="{{ route('admin.pengeluaran.destroy', $item->id) }}" method="POST" class="m-0 p-0 form-hapus">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-hapus inline-flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white w-8 h-8 transition-colors shrink-0" title="Hapus Pengeluaran">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>

                                {{-- TOMBOL EDIT --}}
                                <a href="{{ route('admin.pengeluaran.edit', $item->id) }}" class="inline-flex items-center justify-center rounded-lg bg-green-50 text-green-700 px-3 py-1.5 text-xs font-bold hover:bg-green-600 hover:text-white transition-colors shrink-0">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                            Belum ada data pengeluaran.
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
                    title: 'Hapus Data?',
                    text: "Data pengeluaran ini akan dihapus permanen!",
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

        // SweetAlert Toast Notification
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