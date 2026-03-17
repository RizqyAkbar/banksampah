@extends('admin.layout.app')

@section('title', 'Master Data Layanan')
@section('header_title', 'Kelola Master Layanan')

@section('extra_css')
<style>
    .form-input {
        width: 100%; border-radius: 0.75rem; border: 1px solid #e2e8f0;
        background-color: #f8fafc; padding: 0.75rem 1rem; font-size: 0.875rem;
        color: #0f172a; transition: all 0.2s;
    }
    .form-input:focus {
        outline: none; border-color: #00C264; background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 194, 100, 0.1);
    }
    .input-group-custom { display: flex; align-items: stretch; width: 100%; }
    .input-group-text-custom {
        display: flex; align-items: center; padding: 0 1rem;
        font-size: 0.875rem; font-weight: 700; color: #64748b;
        background-color: #f1f5f9; border: 1px solid #e2e8f0;
        border-right: none; border-top-left-radius: 0.75rem; border-bottom-left-radius: 0.75rem;
    }
    .form-input-grouped { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }

    /* REVISI: Penyesuaian ukuran input dan pop-up khusus Mobile */
    @media (max-width: 640px) {
        .form-input { padding: 0.6rem 0.75rem; font-size: 0.8rem; }
        .input-group-text-custom { padding: 0 0.5rem; font-size: 0.7rem; }
        
        .swal2-popup.swal2-toast {
            padding: 0.5rem 0.75rem !important;
            width: auto !important;
            max-width: 90vw !important;
        }
        .swal2-toast .swal2-title {
            font-size: 0.85rem !important;
            margin-bottom: 0.1rem !important;
        }
        .swal2-toast .swal2-html-container {
            font-size: 0.75rem !important;
            margin: 0 !important;
        }
        .swal2-toast .swal2-icon {
            transform: scale(0.6) !important;
            margin: 0 0.5rem 0 0 !important;
        }
        .swal2-container.swal2-top-end {
            top: 10px !important;
            right: 10px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-2 sm:mb-6 sm:hidden">Kelola Data Layanan</h1>

    {{-- CARD 1: FORM TAMBAH LAYANAN --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-50 bg-slate-50/50">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-layer-group text-green-600"></i> Tambah Layanan & Armada
            </h2>
        </div>
        <form action="{{ route('admin.master_layanan.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf
            {{-- REVISI: Gap diperkecil di mobile --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-end">
                <div class="md:col-span-3">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Nama Layanan</label>
                    <input type="text" name="nama_layanan" placeholder="Ex: Buang Puing" class="form-input" required>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Jenis Armada</label>
                    <input type="text" name="armada" placeholder="Ex: Becak / Truk Engkel" class="form-input" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">HPP (Modal)</label>
                    <div class="input-group-custom">
                        <span class="input-group-text-custom">Rp</span>
                        <input type="number" name="harga_dasar" placeholder="0" class="form-input form-input-grouped" required>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Harga Jual</label>
                    <div class="input-group-custom">
                        <span class="input-group-text-custom">Rp</span>
                        <input type="number" name="harga_jual" placeholder="0" class="form-input form-input-grouped" required>
                    </div>
                </div>
                <div class="md:col-span-2 pt-2 md:pt-0">
                    <button type="submit" class="w-full bg-[#00C264] hover:bg-[#009B4E] text-white py-2 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- CARD 2: TABEL DAFTAR LAYANAN --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Database Master Layanan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        {{-- REVISI: Tambahkan whitespace-nowrap --}}
                        <th class="px-6 py-4 text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 whitespace-nowrap">Layanan</th>
                        <th class="px-6 py-4 whitespace-nowrap">Armada</th>
                        <th class="px-6 py-4 whitespace-nowrap">Harga Dasar</th>
                        <th class="px-6 py-4 whitespace-nowrap">Harga Jual</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($services as $index => $s)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">{{ $s->nama_layanan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-bold uppercase border border-slate-200">
                                {{ $s->armada ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-600 whitespace-nowrap">Rp {{ number_format($s->harga_dasar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 whitespace-nowrap">Rp {{ number_format($s->harga_jual, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="editService({{ json_encode($s) }})" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.master_layanan.destroy', $s->id) }}" method="POST" class="form-hapus m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-hapus p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data layanan terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEditService" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl max-w-md w-full overflow-hidden animate-in zoom-in duration-200">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Data Layanan</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="formEditService" method="POST" class="p-4 sm:p-6 space-y-3 sm:space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Nama Layanan</label>
                <input type="text" name="nama_layanan" id="edit_nama" class="form-input" required>
            </div>
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Jenis Armada</label>
                <input type="text" name="armada" id="edit_armada" class="form-input" required>
            </div>
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Harga Dasar (HPP)</label>
                <div class="input-group-custom">
                    <span class="input-group-text-custom">Rp</span>
                    <input type="number" name="harga_dasar" id="edit_dasar" class="form-input form-input-grouped" required>
                </div>
            </div>
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Harga Jual</label>
                <div class="input-group-custom">
                    <span class="input-group-text-custom">Rp</span>
                    <input type="number" name="harga_jual" id="edit_jual" class="form-input form-input-grouped" required>
                </div>
            </div>
            <div class="pt-3 sm:pt-4 flex gap-2 sm:gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 sm:py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="flex-[1.5] px-4 py-2 sm:py-2.5 bg-blue-600 text-white rounded-xl text-xs sm:text-sm font-bold hover:bg-blue-700 shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra_js')
<script>
    function editService(service) {
        const modal = document.getElementById('modalEditService');
        const form = document.getElementById('formEditService');
        
        form.action = `/admin/master-layanan/${service.id}`;
        document.getElementById('edit_nama').value = service.nama_layanan;
        document.getElementById('edit_armada').value = service.armada;
        document.getElementById('edit_dasar').value = Math.round(service.harga_dasar);
        document.getElementById('edit_jual').value = Math.round(service.harga_jual);
        
        modal.classList.remove('hidden');
    }

    function closeModal() { document.getElementById('modalEditService').classList.add('hidden'); }

    @if(session('success'))
        Swal.fire({
            icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}',
            timer: 3000, toast: true, position: 'top-end', showConfirmButton: false,
            customClass: { popup: 'rounded-2xl shadow-lg border border-green-100', title: 'font-heading font-bold text-slate-900' }
        });
    @endif

    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-hapus')) {
            const form = e.target.closest('.form-hapus');
            Swal.fire({
                title: 'Hapus Layanan?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { 
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-lg px-4 py-2 text-sm font-bold',
                    cancelButton: 'rounded-lg px-4 py-2 text-sm font-bold text-slate-700'
                }
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        }
    });
</script>
@endsection