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
                    <p class="text-sm text-slate-500">Masukkan informasi pelanggan dan detail layanan dengan lengkap.</p>
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
                        <input type="tel" id="no_wa" name="no_wa" placeholder="Ex: 081234567890" class="form-input" required>
                        <p class="mt-1.5 text-xs text-slate-400"><i class="fa-solid fa-circle-info mr-1"></i>Jika nomor sama, sistem akan menggabungkan ke 1 rekapan otomatis.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap lokasi..." class="form-input" required></textarea>
                    </div>

                    <div class="md:col-span-2 py-2">
                        <hr class="border-slate-100">
                    </div>

                    <div class="md:col-span-1">
                        <label for="penjadwalan" class="mb-2 block text-sm font-semibold text-slate-700">Penjadwalan (Tanggal) <span class="text-red-500">*</span></label>
                        <input type="date" id="penjadwalan" name="penjadwalan" class="form-input" required>
                    </div>

                    <div class="md:col-span-1">
                        <label for="jenis_layanan" class="mb-2 block text-sm font-semibold text-slate-700">Jenis Layanan <span class="text-red-500">*</span></label>
                        <div class="relative mb-2">
                            <select id="jenis_layanan" name="jenis_layanan" class="form-input appearance-none pr-10" required onchange="toggleCustomLayanan(this)">
                                <option value="" disabled selected hidden>Pilih Jenis Layanan</option>
                                <option value="Buang Puing">Buang Puing</option>
                                <option value="Buang Sampah">Buang Sampah</option>
                                <option value="Urug Puing">Urug Puing</option>
                                <option value="Urug Tanah">Urug Tanah</option>
                                <option value="Material">Kirim Material</option>
                                <option value="Lainnya">Lainnya... (Tulis Sendiri)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                        
                        <div id="input_layanan_custom_container" class="hidden">
                            <input type="text" id="jenis_layanan_custom" name="jenis_layanan_custom" placeholder="Ketik layanan kustom di sini..." class="form-input border-dashed border-slate-300 focus:border-green-500">
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <label for="armada" class="mb-2 block text-sm font-semibold text-slate-700">Armada <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="armada" name="armada" class="form-input appearance-none pr-10" required>
                                <option value="" disabled selected hidden>Pilih Armada</option>
                                <option value="Pickup">Unit Pickup</option>
                                <option value="Truk Dump">Truk Dump</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <i class="fa-solid fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="catatan" class="mb-2 block text-sm font-semibold text-slate-700">Catatan Khusus <span class="text-slate-400 font-normal ml-1">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" rows="2" placeholder="Ex: Request diambil sebelum jam 3 sore..." class="form-input"></textarea>
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
        // Logika input kustom untuk "Lainnya"
        function toggleCustomLayanan(selectElement) {
            const customContainer = document.getElementById('input_layanan_custom_container');
            const customInput = document.getElementById('jenis_layanan_custom');
            
            if (selectElement.value === 'Lainnya') {
                customContainer.classList.remove('hidden');
                customInput.setAttribute('required', 'required');
                customInput.focus();
            } else {
                customContainer.classList.add('hidden');
                customInput.removeAttribute('required');
                customInput.value = '';
            }
        }

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