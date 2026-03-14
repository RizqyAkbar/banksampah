@extends('admin.layout.app')

@section('title', 'Input Order')
@section('header_title', 'Input Order Baru')

@section('extra_css')
    <style>
        /* Custom Input Styles khusus untuk halaman ini */
        .form-input {
            width: 100%;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #0f172a;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #00C264;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 194, 100, 0.1);
        }
        .rounded-l-none {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }
    </style>
@endsection

@section('content')
    <h1 class="font-heading text-2xl font-bold text-slate-900 mb-6 sm:hidden">Input Order</h1>

    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl border border-slate-100 bg-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            
            <div class="border-b border-slate-100 bg-slate-50/50 p-6 flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <i class="fa-solid fa-file-pen text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Detail Pemesanan</h2>
                    <p class="text-sm text-slate-500">Masukkan informasi pelanggan dan detail lokasi dengan lengkap.</p>
                </div>
            </div>

            <form action="{{ route('admin.input_order.store') }}" method="POST" class="p-6 sm:p-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="md:col-span-1">
                        <label for="nama_pelanggan" class="mb-2 block text-sm font-semibold text-slate-700">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" placeholder="Ex: Bapak Budi Santoso" class="form-input" required>
                    </div>

                    <div class="md:col-span-1">
                        <label for="no_wa" class="mb-2 block text-sm font-semibold text-slate-700">No. WhatsApp <span class="text-red-500">*</span></label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 font-bold text-sm">
                                +62
                            </span>
                            <input type="tel" id="no_wa" name="no_wa" placeholder="81234567890" class="form-input rounded-l-none" oninput="formatPhoneNumber(this)" required>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap lokasi..." class="form-input" required></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="link_maps" class="mb-2 block text-sm font-semibold text-slate-700">Link Google Maps (Sharelok) <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-400">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input type="url" id="link_maps" name="link_maps" placeholder="Paste link maps di sini..." class="form-input rounded-l-none">
                        </div>
                    </div>

                    <div class="md:col-span-2 py-2">
                        <hr class="border-slate-100">
                    </div>

                    <div class="md:col-span-1">
                        <label for="penjadwalan" class="mb-2 block text-sm font-semibold text-slate-700">Penjadwalan (Tanggal) <span class="text-red-500">*</span></label>
                        <input type="date" id="penjadwalan" name="penjadwalan" class="form-input" required>
                    </div>

                    <div class="md:col-span-1">
                        <label for="catatan" class="mb-2 block text-sm font-semibold text-slate-700">Catatan Khusus <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" rows="3" placeholder="Ex: Request diambil sebelum jam 3 sore..." class="form-input"></textarea>
                    </div>

                </div>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="reset" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 transition-colors">
                        Bersihkan Form
                    </button>
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-[#00C264] text-white text-sm font-semibold hover:bg-[#009B4E] shadow-[0_8px_20px_rgba(0,194,100,0.2)] transition-all transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Order
                    </button>
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

        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}',
                timer: 3000, toast: true, position: 'top-end', showConfirmButton: false,
                customClass: { popup: 'rounded-2xl shadow-lg border border-green-100', title: 'font-heading font-bold text-slate-900' }
            });
        @endif
    </script>
@endsection