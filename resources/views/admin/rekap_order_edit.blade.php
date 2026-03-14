@extends('admin.layout.app')

@section('title', 'Lengkapi Rekap')

@section('header_custom')
<header class="flex h-20 items-center justify-between border-b border-slate-200/60 bg-white/80 px-6 backdrop-blur-md z-30">
    <div class="flex items-center gap-4">
        <button id="openSidebar" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900 lg:hidden">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.rekap_order') }}" class="text-slate-400 hover:text-slate-700 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="font-heading text-2xl font-bold text-slate-900 hidden sm:block">Edit Data Rekap</h1>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-sm font-medium text-slate-600 border border-slate-100">
            <i class="fa-regular fa-calendar text-slate-400"></i>
            {{ date('d M Y') }}
        </div>
    </div>
</header>
@endsection

@section('extra_css')
<style>
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

    /* ... style lainnya ... */
    
    .rounded-l-none {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .rounded-r-none {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
</style>
@endsection

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-heading text-2xl font-bold text-slate-900 sm:hidden">Lengkapi Rekap</h1>
        <span class="px-3 py-1 {{ $order->status_pelanggan == 'Pelanggan' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} font-bold rounded-full text-xs">
            {{ $order->status_pelanggan }}
        </span>
    </div>

    <div class="max-w-4xl mx-auto w-full">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
            <form action="{{ route('admin.rekap_order.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- INFO PELANGGAN --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-slate-50 p-5 rounded-xl border border-slate-100">
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Nama Pelanggan</p>
                        <p class="font-bold text-slate-800 text-lg">{{ $order->nama_pelanggan }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">No WhatsApp</p>
                        <p class="font-bold text-slate-800 text-lg"><i class="fa-brands fa-whatsapp text-green-500 mr-1.5"></i>+{{ $order->no_wa }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1 uppercase tracking-wider">Penjadwalan</p>
                        <p class="font-bold text-slate-800 text-lg"><i class="fa-regular fa-calendar text-blue-500 mr-1.5"></i>{{ \Carbon\Carbon::parse($order->penjadwalan)->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- DROPDOWN LAYANAN SESUAI GAMBAR --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Layanan</label>
                        <select name="jenis_layanan" id="jenis_layanan_select" class="form-input" required onchange="toggleLayananLainnya()">
                            <option value="Buang Puing" {{ $order->jenis_layanan == 'Buang Puing' ? 'selected' : '' }}>Buang Puing</option>
                            <option value="Buang Sampah" {{ $order->jenis_layanan == 'Buang Sampah' ? 'selected' : '' }}>Buang Sampah</option>
                            <option value="Urug Puing" {{ $order->jenis_layanan == 'Urug Puing' ? 'selected' : '' }}>Urug Puing</option>
                            <option value="Urug Tanah" {{ $order->jenis_layanan == 'Urug Tanah' ? 'selected' : '' }}>Urug Tanah</option>
                            <option value="Material" {{ $order->jenis_layanan == 'Material' || $order->jenis_layanan == 'Kirim Material' ? 'selected' : '' }}>Kirim Material</option>
                            <option value="Lainnya" {{ !in_array($order->jenis_layanan, ['Buang Puing','Buang Sampah','Urug Puing','Urug Tanah','Material','Kirim Material']) ? 'selected' : '' }}>Lainnya...</option>
                        </select>

                        {{-- Input Tambahan jika pilih Lainnya --}}
                        <div id="container_layanan_lainnya" class="mt-3 {{ in_array($order->jenis_layanan, ['Buang Puing','Buang Sampah','Urug Puing','Urug Tanah','Material','Kirim Material']) ? 'hidden' : '' }}">
                            <label class="block text-[11px] font-bold text-slate-700 mb-1 uppercase">Tulis Layanan </label>
                            <input type="text" 
                                name="jenis_layanan_kustom" 
                                id="jenis_layanan_kustom" 
                                value="{{ !in_array($order->jenis_layanan, ['Buang Puing','Buang Sampah','Urug Puing','Urug Tanah','Material','Kirim Material']) ? $order->jenis_layanan : '' }}" 
                                placeholder="Contoh: Bongkar Pasang Paving" 
                                class="form-input border-orange-200 bg-orange-50/30 focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>
                    

                    {{-- ARMADA --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Armada</label>
                        <select name="armada" class="form-input">
                            <option value="Pickup" {{ $order->armada == 'Pickup' ? 'selected' : '' }}>Unit Pickup</option>
                            <option value="Truk Dump" {{ $order->armada == 'Truk Dump' ? 'selected' : '' }}>Truk Dump</option>
                        </select>
                    </div>

                    {{-- INFORMASI SOPIR & PLAT --}}
                    <div class="md:col-span-2 py-2"><hr class="border-slate-100"></div>
                    
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Sopir</label>
                        <input type="text" name="nama_sopir" value="{{ $order->nama_sopir }}" placeholder="Masukkan nama sopir" class="form-input">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">No. WA Sopir</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-100 text-slate-500 font-bold text-sm">+62</span>
                            <input type="tel" name="no_wa_sopir" value="{{ ltrim($order->no_wa_sopir, '62') }}" oninput="this.value = this.value.replace(/\D/g, '').replace(/^0+/, '')" placeholder="812xxxx" class="form-input rounded-l-none">
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Plat Armada</label>
                        <input type="text" name="plat_armada" value="{{ $order->plat_armada }}" placeholder="Ex: AB 1234 XX" class="form-input uppercase">
                    </div>

                    <div class="md:col-span-2 mt-4 p-4 bg-orange-50 border border-orange-200 rounded-xl">
                        <label class="block text-sm font-bold text-orange-700 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            Alasan Perubahan (WAJIB DIISI JIKA GANTI SOPIR/ARMADA)
                        </label>
                        <input type="text" 
                            name="alasan_perubahan" 
                            id="alasan_perubahan"
                            placeholder="Contoh: Sopir utama sakit / Truk sedang servis" 
                            class="form-input border-orange-300 bg-white focus:border-orange-500 focus:ring-orange-200">
                        <p class="text-[10px] text-orange-500 mt-2 italic">*Ketikkan alasan kenapa sopir/armada harus diganti agar tercatat di History Perubahan Owner.</p>
                    </div>

                    {{-- INFORMASI LOKASI & ALAMAT --}}
                    <div class="md:col-span-2 py-2"><hr class="border-slate-100"></div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap Pelanggan</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-400">
                                <i class="fa-solid fa-map-location-dot"></i>
                            </span>
                            <textarea name="alamat" rows="2" placeholder="Masukkan alamat lengkap..." class="form-input rounded-l-none">{{ $order->alamat }}</textarea>
                        </div>
                    </div>

                    <div class="md:col-span-2 mt-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Link Google Maps (Sharelok)</label>
                        <div class="relative flex">
                            <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-400 rounded-r-none">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input type="url" name="link_maps" value="{{ $order->link_maps }}" placeholder="Paste link maps baru di sini..." class="form-input rounded-l-none">
                        </div>
                    </div>

                    <div class="md:col-span-2 py-2"><hr class="border-slate-100"></div>

                    {{-- PERHITUNGAN --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Rit</label>
                        <input type="number" id="jumlah_rit" name="jumlah_rit" value="{{ $order->jumlah_rit }}" class="form-input hitung-otomatis">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Harga Per Rit (Rp)</label>
                        <input type="number" id="harga_per_rit" name="harga_per_rit" value="{{ $order->harga_per_rit }}" class="form-input hitung-otomatis">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">HPP Per Rit (Modal - Rp)</label>
                        <input type="number" id="hpp_per_rit" name="hpp_per_rit" value="{{ $order->hpp_per_rit }}" class="form-input hitung-otomatis">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Khusus</label>
                        <textarea name="catatan" rows="2" class="form-input">{{ $order->catatan }}</textarea>
                    </div>
                </div>

                {{-- ESTIMASI HASIL --}}
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-8">
                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5">
                        <p class="text-blue-600 font-semibold text-sm mb-1 uppercase tracking-wider">Estimasi Pendapatan</p>
                        <h3 class="text-3xl font-bold text-blue-900" id="hasil_pendapatan">Rp 0</h3>
                    </div>
                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5">
                        <p class="text-emerald-600 font-semibold text-sm mb-1 uppercase tracking-wider">Estimasi Laba Bersih</p>
                        <h3 class="text-3xl font-bold text-emerald-900" id="hasil_keuntungan">Rp 0</h3>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('admin.rekap_order') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</a>
                    <button type="submit" class="px-8 py-2.5 bg-[#00C264] text-white rounded-xl text-sm font-bold shadow-lg hover:bg-[#009B4E] transition">Simpan Rekap</button>
                </div>
            </form>
            @if($order->logs->count() > 0)
            <div class="mt-12">
                <h3 class="font-heading text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-orange-500"></i>
                    History Perubahan Data
                </h3>
                <div class="space-y-4">
                    @foreach($order->logs as $log)
                    <div class="bg-slate-50 border-l-4 border-orange-400 p-4 rounded-r-xl shadow-sm">
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-bold text-slate-800">{{ $log->perubahan }}</span>
                            <span class="text-[10px] font-medium text-slate-500 bg-slate-200 px-2 py-0.5 rounded-full">
                                {{ $log->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 mb-2">{{ $log->detail }}</p>
                        <div class="text-xs text-slate-500 italic">
                            <strong>Alasan:</strong> {{ $log->alasan }} | <strong>Oleh:</strong> {{ $log->admin_name }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('extra_js')
<script>
    const inputRit = document.getElementById('jumlah_rit');
    const inputHarga = document.getElementById('harga_per_rit');
    const inputHpp = document.getElementById('hpp_per_rit');
    const txtPendapatan = document.getElementById('hasil_pendapatan');
    const txtKeuntungan = document.getElementById('hasil_keuntungan');

    function hitungOtomatis() {
        const rit = parseFloat(inputRit.value) || 0;
        const harga = parseFloat(inputHarga.value) || 0;
        const hpp = parseFloat(inputHpp.value) || 0;
        const pendapatan = rit * harga;
        const keuntungan = pendapatan - (rit * hpp);

        txtPendapatan.innerText = 'Rp ' + pendapatan.toLocaleString('id-ID');
        txtKeuntungan.innerText = 'Rp ' + keuntungan.toLocaleString('id-ID');
    }

    hitungOtomatis();
    document.querySelectorAll('.hitung-otomatis').forEach(input => {
        input.addEventListener('input', hitungOtomatis);
    });

    function toggleLayananLainnya() {
        const selectLayanan = document.getElementById('jenis_layanan_select');
        const containerLainnya = document.getElementById('container_layanan_lainnya');
        const inputKustom = document.getElementById('jenis_layanan_kustom');

        if (selectLayanan.value === 'Lainnya') {
            containerLainnya.classList.remove('hidden');
            inputKustom.setAttribute('required', 'required');
            inputKustom.focus();
        } else {
            containerLainnya.classList.add('hidden');
            inputKustom.removeAttribute('required');
            inputKustom.value = ''; // Reset isinya jika pindah ke opsi standar
        }
    }

    // Jalankan saat halaman dimuat untuk memastikan kondisi awal benar
    window.onload = toggleLayananLainnya;
</script>
@endsection