<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->nama_pelanggan }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            @page { size: A4; margin: 0; }
            body { margin: 1.5cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="bg-white p-10">
    <div class="flex justify-between items-start border-b-2 border-slate-100 pb-8 mb-8">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="h-16 w-auto">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 uppercase tracking-tighter">Juragan<span class="text-green-600">Urug</span></h1>
                <p class="text-xs text-slate-500 font-medium">Jasa Buang Puing & Pengurukan Tanah</p>
            </div>
        </div>
        <div class="text-right">
            <h2 class="text-xl font-bold text-slate-800">INVOICE</h2>
            {{-- Logika: Pakai no_invoice dari DB, kalau kosong baru generate otomatis --}}
            <p class="text-sm text-slate-500">
                {{ $order->no_invoice ?? 'INV-' . $order->created_at->format('Ymd') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT) }}
            </p>
            <p class="text-sm text-slate-500 mt-1">{{ $order->created_at->format('d F Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-10 mb-10">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Ditagihkan Kepada:</p>
            <h3 class="text-lg font-bold text-slate-900">{{ $order->nama_pelanggan }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ $order->alamat }}</p>
            <p class="text-sm text-slate-600">{{ $order->no_wa }}</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Metode Pembayaran:</p>
            <p class="text-sm text-slate-900 font-bold">Transfer Bank / Cash</p>
            <p class="text-xs text-slate-500 italic mt-1">*Pembayaran dilakukan setelah pengerjaan selesai.</p>
        </div>
    </div>

    <table class="w-full text-left mb-10">
        <thead>
            <tr class="bg-slate-900 text-white">
                <th class="px-4 py-3 rounded-l-lg">Deskripsi Layanan</th>
                <th class="px-4 py-3 text-center">Volume</th>
                <th class="px-4 py-3 text-right">Harga Satuan</th>
                <th class="px-4 py-3 text-right rounded-r-lg">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <tr>
                <td class="px-4 py-6">
                    <p class="font-bold text-slate-800">{{ $order->jenis_layanan }}</p>
                    <p class="text-xs text-slate-400 mt-1 uppercase">Unit: {{ $order->armada }}</p>
                </td>
                <td class="px-4 py-6 text-center font-medium text-slate-700">{{ $order->jumlah_rit }} Rit</td>
                <td class="px-4 py-6 text-right text-slate-700">Rp {{ number_format($order->harga_per_rit, 0, ',', '.') }}</td>
                <td class="px-4 py-6 text-right font-bold text-slate-900">Rp {{ number_format($order->jumlah_pendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td class="px-4 py-6 text-right font-bold text-slate-500 uppercase tracking-wider text-xs">Total Tagihan</td>
                <td class="px-4 py-6 text-right text-2xl font-bold text-green-600">Rp {{ number_format($order->jumlah_pendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-20 flex justify-between items-end border-t border-slate-100 pt-8">
        <div>
            <p class="text-xs text-slate-400 font-medium mb-1">Catatan Tambahan:</p>
            <p class="text-xs text-slate-500 italic w-80">"{{ $order->catatan ?? 'Terima kasih telah menggunakan jasa kami. Kepuasan Anda adalah prioritas kami.' }}"</p>
        </div>
        <div class="text-center w-48">
            <p class="text-xs text-slate-400 mb-12 italic">Hormat Kami,</p>
            <p class="text-sm font-bold text-slate-900 border-t border-slate-900 pt-2 uppercase">Juragan Urug Admin</p>
        </div>
    </div>

    <div class="fixed bottom-8 right-8 no-print flex gap-3">
        <button onclick="tutupTabDanKembali()" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-full text-sm font-bold transition flex items-center justify-center">
            Kembali
        </button>
        
        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg transition flex items-center gap-2">
            <i class="fa-solid fa-download"></i> Simpan Sebagai PDF
        </button>
    </div>

    <script>
        function tutupTabDanKembali() {
            // Perintah untuk menutup tab saat ini
            window.close();
            
            // Cadangan: Jika window.close() diblokir browser (biasanya karena tab dibuka manual), 
            // maka arahkan kembali ke page create invoice agar tidak bingung
            setTimeout(function() {
                window.location.href = "{{ route('admin.invoice_index') }}";
            }, 300);
        }
    </script>
</body>
</html>