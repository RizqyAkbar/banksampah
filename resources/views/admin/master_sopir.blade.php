@extends('admin.layout.app')

@section('title', 'Master Data Sopir')
@section('header_title', 'Kelola Master Sopir')

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
    .rounded-l-none { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }

    @media (max-width: 640px) {
        .form-input { padding: 0.6rem 0.75rem; font-size: 0.8rem; }
        
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
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-2 sm:mb-6 sm:hidden">Kelola Data Sopir</h1>
    
    {{-- CARD 1: FORM TAMBAH SOPIR --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-50 bg-slate-50/50">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-green-600"></i>
                Tambah Sopir Baru
            </h2>
        </div>
        <form id="formTambah" action="{{ route('admin.master_sopir.store') }}" method="POST" class="p-4 sm:p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 sm:gap-4 items-end">
                <div class="md:col-span-4">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Nama Sopir</label>
                    <input type="text" name="nama_sopir" placeholder="Ex: Bambang" class="form-input" required>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">No. WhatsApp</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 sm:px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 text-xs sm:text-sm font-bold">+62</span>
                        <input type="tel" name="no_wa_sopir" placeholder="8123..." class="form-input rounded-l-none" oninput="formatPhoneNumber(this)" required>
                    </div>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Plat Armada Default <span class="text-slate-400 font-normal ml-1 normal-case">(Opsional)</span></label>
                    <input type="text" name="plat_armada" placeholder="AB 1234 XX" class="form-input uppercase">
                </div>
                <div class="md:col-span-2 pt-2 md:pt-0">
                    <button type="submit" class="w-full bg-[#00C264] hover:bg-[#009B4E] text-white py-2 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- FITUR BARU: FORM FILTER & EXPORT --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-4 sm:p-6 shadow-sm mb-6">
        <form action="{{ route('admin.master_sopir.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] sm:text-xs font-bold text-slate-400 uppercase mb-1.5">Pencarian</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, wa, atau plat..." class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-green-400 focus:ring-2 focus:ring-green-400/20 transition-all">
            </div>
            
            {{-- Tombol Filter --}}
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.master_sopir.index') }}" class="h-11 px-5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition shadow-sm flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-arrows-rotate"></i> Reset
                </a>
                <button type="submit" class="h-11 px-5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                <button type="submit" name="export" value="excel" class="h-11 px-5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition shadow-lg flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-wider w-full sm:w-auto">
                    <i class="fa-solid fa-file-excel"></i> Export
                </button>
            </div>
        </form>
    </div>

    {{-- CARD 2: TABEL DAFTAR SOPIR --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-50 bg-slate-50/50">
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Database Master Sopir</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-center w-16 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 whitespace-nowrap">Nama Sopir</th>
                        <th class="px-6 py-4 whitespace-nowrap">Kontak WA</th>
                        <th class="px-6 py-4 whitespace-nowrap">Plat Nomor</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($drivers as $index => $d)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ ($drivers->currentPage() - 1) * $drivers->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">{{ $d->nama_sopir }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="https://wa.me/{{ $d->no_wa_sopir }}" target="_blank" class="text-green-600 hover:underline font-medium">
                                <i class="fa-brands fa-whatsapp mr-1"></i>+{{ $d->no_wa_sopir }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-slate-100 px-2 py-1 rounded text-[11px] font-bold text-slate-700 border border-slate-200 uppercase">{{ $d->plat_armada ?: '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="editDriver({{ json_encode($d) }})" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('admin.master_sopir.destroy', $d->id) }}" method="POST" class="form-hapus m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-hapus p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Belum ada data sopir terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- REVISI: Pagination --}}
        <div class="p-6 border-t border-slate-100 bg-slate-50/30">
            {{ $drivers->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="fixed inset-0 z-[60] hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl max-w-md w-full overflow-hidden animate-in zoom-in duration-200">
        <div class="p-4 sm:p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-base sm:text-lg font-bold text-slate-900">Edit Data Sopir</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="formEdit" method="POST" class="p-4 sm:p-6 space-y-3 sm:space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Nama Sopir</label>
                <input type="text" name="nama_sopir" id="edit_nama" class="form-input" required>
            </div>
            
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">No. WhatsApp</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 sm:px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 text-xs sm:text-sm font-bold">+62</span>
                    <input type="tel" name="no_wa_sopir" id="edit_wa" class="form-input rounded-l-none" oninput="formatPhoneNumber(this)" required>
                </div>
            </div>

            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-slate-500 uppercase mb-1 sm:mb-1.5">Plat Armada <span class="text-slate-400 font-normal ml-1 normal-case">(Opsional)</span></label>
                <input type="text" name="plat_armada" id="edit_plat" class="form-input uppercase">
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
    function formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, ''); 
        if (value.startsWith('0')) value = value.substring(1);
        if (value.startsWith('62')) value = value.substring(2);
        input.value = value;
    }

    function editDriver(driver) {
        const modal = document.getElementById('modalEdit');
        const form = document.getElementById('formEdit');
        
        form.action = `/admin/master-sopir/${driver.id}`;
        document.getElementById('edit_nama').value = driver.nama_sopir;
        
        let waNumber = driver.no_wa_sopir.toString();
        if (waNumber.startsWith('62')) {
            waNumber = waNumber.substring(2);
        }
        document.getElementById('edit_wa').value = waNumber;
        document.getElementById('edit_plat').value = driver.plat_armada || '';
        
        modal.classList.remove('hidden');
    }

    document.getElementById('formTambah').onsubmit = function() {
        const inputWa = document.querySelector('#formTambah input[name="no_wa_sopir"]');
        let value = inputWa.value.replace(/\D/g, ''); 
        if (value.startsWith('0')) value = value.substring(1);
        if (value.startsWith('62')) value = value.substring(2);
        inputWa.value = '62' + value;
    };

    document.getElementById('formEdit').onsubmit = function() {
        const inputWa = document.getElementById('edit_wa');
        let value = inputWa.value.replace(/\D/g, ''); 
        if (value.startsWith('0')) value = value.substring(1);
        if (value.startsWith('62')) value = value.substring(2);
        inputWa.value = '62' + value;
    };

    function closeModal() {
        document.getElementById('modalEdit').classList.add('hidden');
    }

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
                title: 'Hapus Sopir?',
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
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }
    });
</script>
@endsection