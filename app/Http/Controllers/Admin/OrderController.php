<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengeluaran;
use App\Models\Service; // Ganti dengan nama model Master Layanan Anda
use Carbon\Carbon;

class OrderController extends Controller
{
    // 1. Menampilkan Form Input Order
    public function create()
    {
        // Ambil semua data layanan dari Master Layanan
        $masterLayanan = Service::orderBy('nama_layanan', 'asc')->get();

        // Kirim data tersebut ke view dengan nama variabel yang sama
        return view('admin.input_order', compact('masterLayanan'));
    }

    // 2. Menyimpan Input Order Awal (REVISI UNTUK OPSI LAINNYA)
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required',
            'no_wa'          => 'required',
            'alamat'         => 'required',
            'penjadwalan'    => 'required|date',
            'layanan'        => 'required|array',
            'armada'         => 'required|array',
        ]);

        $nomorBersih = ltrim($request->no_wa, '0');
        $noWaFinal = '62' . $nomorBersih;

        $tanggalFormat = date('Ymd');

        // Logika Nomor Invoice Unik
        $lastOrderToday = Order::whereDate('created_at', Carbon::today())->latest()->first();
        if ($lastOrderToday && $lastOrderToday->no_invoice) {
            $lastIncrement = (int) substr($lastOrderToday->no_invoice, -3);
            $nextIncrement = $lastIncrement + 1;
        } else {
            $nextIncrement = 1;
        }

        $nomorUrut = str_pad($nextIncrement, 3, '0', STR_PAD_LEFT);
        $noInvoiceFinal = "INV-$tanggalFormat-$nomorUrut";

        $jumlahOrder = Order::where('no_wa', $noWaFinal)->count();
        $statusPelanggan = ($jumlahOrder >= 2) ? 'Pelanggan' : 'Konsumen Baru';

        $order = Order::create([
            'nama_pelanggan'   => $request->nama_pelanggan,
            'no_wa'            => $noWaFinal,
            'alamat'           => $request->alamat,
            'penjadwalan'      => $request->penjadwalan,
            'catatan'          => $request->catatan,
            'status_pelanggan' => $statusPelanggan,
            'status_order'     => 'Aktif',
            'link_maps'        => $request->link_maps,
            'no_invoice'       => $noInvoiceFinal,
            'total_pendapatan' => 0,
            'total_keuntungan' => 0,
        ]);

        foreach ($request->layanan as $index => $jenisLayanan) {
            // Backup logic: Jika armada null dari request, coba cari di master
            $armadaFinal = $request->armada[$index];

            if (empty($armadaFinal)) {
                $service = Service::where('nama_layanan', $jenisLayanan)->first();
                $armadaFinal = $service ? $service->armada : 'Umum'; // Fallback ke 'Umum' jika benar-benar tidak ada
            }

            $namaJasaFinal = ($jenisLayanan === 'LAINNYA')
                ? ($request->layanan_manual[$index] ?? 'Jasa Tidak Diketahui')
                : $jenisLayanan;

            $hpp = $request->harga_dasar[$index] ?? 0;
            $harga = $request->harga_jual[$index] ?? 0;

            $order->items()->create([
                'jenis_layanan' => $namaJasaFinal,
                'armada'        => $armadaFinal, // Menggunakan variabel yang sudah dicek
                'harga_per_rit' => $harga,
                'hpp_per_rit'   => $hpp,
                'status_item'   => 'Aktif',
            ]);
        }

        return redirect()->back()->with('success', 'Order baru berhasil disimpan dengan nomor ' . $noInvoiceFinal);
    }

    // 3. Halaman REKAP ORDER (DENGAN FITUR SEARCH, FILTER TANGGAL & EXPORT)
    public function index(Request $request)
    {
        // Inisialisasi query
        $query = Order::with(['items' => function ($query) {
                $query->where('status_item', 'Aktif');
            }])
            ->where('status_order', 'Aktif')
            ->whereHas('items', function ($query) {
                $query->where('status_item', 'Aktif');
            });

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_wa', 'LIKE', "%$search%")
                  ->orWhere('alamat', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhereHas('items', function ($sq) use ($search) {
                      $sq->where('nama_sopir', 'LIKE', "%$search%")
                         ->orWhere('jenis_layanan', 'LIKE', "%$search%")
                         ->orWhere('plat_armada', 'LIKE', "%$search%");
                  });
            });
        }

        // --- LOGIKA FILTER TANGGAL (Date Range) ---
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // --- LOGIKA EXPORT EXCEL (CSV Format) ---
        // Jika tombol Export diklik (membawa request export=excel)
        if ($request->has('export') && $request->export == 'excel') {
            $ordersExport = $query->orderBy('penjadwalan', 'desc')->get();
            $filename = "Data_Rekap_Order_" . date('Ymd_His') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = [
                'No', 'No. Invoice', 'Tgl Penjadwalan', 'Nama Pelanggan', 
                'No. WhatsApp', 'Alamat', 'Pendapatan', 'Layanan & Armada'
            ];

            $callback = function() use($ordersExport, $columns) {
                $file = fopen('php://output', 'w');
                // Menambahkan BOM agar Microsoft Excel bisa membaca karakter UTF-8 dengan baik
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); 
                fputcsv($file, $columns); // Tulis baris Header

                foreach ($ordersExport as $index => $order) {
                    $layananDetail = [];
                    foreach($order->items as $item) {
                        // Kumpulkan detail layanan
                        $layananDetail[] = $item->jenis_layanan . " (" . $item->armada . " - " . $item->jumlah_rit . " Rit)";
                    }
                    $layananStr = implode(" | ", $layananDetail); // Digabung jadi 1 text

                    $row['No'] = $index + 1;
                    $row['No. Invoice'] = $order->no_invoice;
                    $row['Tgl Penjadwalan'] = \Carbon\Carbon::parse($order->penjadwalan)->format('Y-m-d');
                    $row['Nama Pelanggan'] = $order->nama_pelanggan;
                    $row['No. WhatsApp'] = "+" . $order->no_wa; // Tambah simbol + agar tidak jadi rumus di Excel
                    $row['Alamat'] = $order->alamat;
                    $row['Pendapatan'] = $order->total_pendapatan;
                    $row['Layanan & Armada'] = $layananStr;

                    // Tulis isi ke CSV
                    fputcsv($file, array(
                        $row['No'], $row['No. Invoice'], $row['Tgl Penjadwalan'], 
                        $row['Nama Pelanggan'], $row['No. WhatsApp'], $row['Alamat'], 
                        $row['Pendapatan'], $row['Layanan & Armada']
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- TAMPILAN VIEW NORMAL JIKA TIDAK DI EXPORT ---
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        $orders->appends($request->all()); // Mempertahankan parameter filter saat pindah page

        return view('admin.rekap_order', compact('orders'));
    }

    // 4. Halaman EDIT REKAP
    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.rekap_order_edit', compact('order'));
    }

    // 5. UPDATE REKAP (SINKRONISASI NAMA KOLOM)
    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $request->validate([
            'item_id' => 'required|array',
            'jumlah_rit' => 'required|array',
            'harga_per_rit' => 'required|array',
            'hpp_per_rit' => 'required|array',
            'alasan_perubahan' => 'nullable|string|max:255', // Alasan yang kamu tulis manual ditangkap di sini
        ]);

        $totalPendapatanOrder = 0;
        $totalKeuntunganOrder = 0;
        $perubahanKolektif = [];

        foreach ($request->item_id as $index => $itemId) {
            $item = OrderItem::findOrFail($itemId);

            $sopirLama = $item->nama_sopir;
            $platLama  = $item->plat_armada;
            $armadaLama = $item->armada;
            $layananLama = $item->jenis_layanan;

            // Logika Jasa Lainnya
            $jenisLayananInput = $request->jenis_layanan[$index];
            $jenisLayananFinal = ($jenisLayananInput === 'LAINNYA')
                ? ($request->layanan_manual[$index] ?? $item->jenis_layanan)
                : $jenisLayananInput;

            $isArmadaChanged = ($armadaLama != $request->armada[$index]);
            $isLayananChanged = ($layananLama != $jenisLayananFinal);
            $isPersonelChanged = ($sopirLama != $request->nama_sopir[$index]) || ($platLama != $request->plat_armada[$index]);

            $changes = [];

            // 1. Armada atau Layanan berubah? Catat!
            if ($isArmadaChanged || $isLayananChanged) {
                if ($isLayananChanged) $changes[] = "Jasa [$layananLama] -> [$jenisLayananFinal]";
                if ($isArmadaChanged) $changes[] = "Armada [$armadaLama] -> [{$request->armada[$index]}]";
            }

            // 2. Sopir berubah dan data lama tidak kosong? Catat!
            if ($isPersonelChanged && !empty($sopirLama)) {
                $changes[] = "Sopir [" . ($sopirLama ?: '-') . " (" . ($platLama ?: '-') . ")] -> [" . ($request->nama_sopir[$index] ?: '-') . " (" . ($request->plat_armada[$index] ?: '-') . ")]";
            }

            if (count($changes) > 0) {
                $labelRevisi = "REVISI OPERASIONAL";
                if ($isPersonelChanged && ($isArmadaChanged || $isLayananChanged)) $labelRevisi = "REVISI UNIT & PERSONEL";
                elseif ($isPersonelChanged) $labelRevisi = "REVISI PERSONEL";
                elseif ($isArmadaChanged || $isLayananChanged) $labelRevisi = "REVISI UNIT ARMADA";

                $detailLog = "Layanan #" . ($index + 1) . " [$labelRevisi]: " . implode(", ", $changes);
                $perubahanKolektif[] = $detailLog;
            }

            // --- REVISI LOGIKA WHATSAPP (CARA 1) ---
            $noWaInput = $request->no_wa_sopir[$index];
            $noWaFinal = null;

            if ($noWaInput) {
                $nomorBersih = ltrim($noWaInput, '0'); // Hapus angka 0 di depan
                if (!str_starts_with($nomorBersih, '62')) {
                    $noWaFinal = '62' . $nomorBersih;
                } else {
                    $noWaFinal = $nomorBersih;
                }
            }
            // ---------------------------------------

            // Update data item ke database
            $item->update([
                'jenis_layanan' => $jenisLayananFinal,
                'armada'        => $request->armada[$index],
                'nama_sopir'    => $request->nama_sopir[$index],
                'no_wa_sopir'   => $noWaFinal, // Menggunakan nomor yang sudah diperbaiki
                'plat_armada'   => $request->plat_armada[$index],
                'jumlah_rit'    => $request->jumlah_rit[$index] ?? 0,
                'harga_per_rit' => $request->harga_per_rit[$index] ?? 0,
                'hpp_per_rit'   => $request->hpp_per_rit[$index] ?? 0,
                'subtotal_pendapatan' => ($request->jumlah_rit[$index] ?? 0) * ($request->harga_per_rit[$index] ?? 0),
                'subtotal_keuntungan' => (($request->jumlah_rit[$index] ?? 0) * ($request->harga_per_rit[$index] ?? 0)) - (($request->jumlah_rit[$index] ?? 0) * ($request->hpp_per_rit[$index] ?? 0)),
            ]);

            $totalPendapatanOrder += $item->subtotal_pendapatan;
            $totalKeuntunganOrder += $item->subtotal_keuntungan;
        }

        // SIMPAN ALASAN MANUAL DARI FORM KE DATABASE
        if (count($perubahanKolektif) > 0) {
            foreach ($perubahanKolektif as $logItem) {
                OrderLog::create([
                    'order_id'   => $order->id,
                    'perubahan'  => 'Update Operasional',
                    'detail'     => $logItem,
                    'alasan'     => $request->alasan_perubahan ?: 'Perubahan data operasional', // Alasan manual masuk sini
                    'admin_name' => Auth::user()->name ?? 'Admin Juragan',
                ]);
            }
        }

        $order->update([
            'total_pendapatan' => $totalPendapatanOrder,
            'total_keuntungan' => $totalKeuntunganOrder,
            'alamat'           => $request->alamat,
            'link_maps'        => $request->link_maps,
            'catatan'          => $request->catatan,
        ]);

        return redirect()->route('admin.rekap_order')->with('success', 'Data Rekap Berhasil diperbarui!');
    }

    // REVISI: Penambahan logika search, filter tanggal, dan export excel untuk Invoice Index
    public function invoiceIndex(Request $request)
    {
        $query = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])
        ->where('status_order', 'Aktif')
        ->where('total_pendapatan', '>', 0); // Hanya yang sudah ada tagihannya

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_wa', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhereHas('items', function ($sq) use ($search) {
                      $sq->where('jenis_layanan', 'LIKE', "%$search%")
                         ->orWhere('armada', 'LIKE', "%$search%");
                  });
            });
        }

        // --- LOGIKA FILTER TANGGAL (Date Range) ---
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // --- LOGIKA EXPORT EXCEL (CSV Format) ---
        if ($request->has('export') && $request->export == 'excel') {
            $ordersExport = $query->orderBy('penjadwalan', 'desc')->get();
            $filename = "Daftar_Invoice_Pelanggan_" . date('Ymd_His') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = [
                'No', 'No. Invoice', 'Tanggal Penjadwalan', 'Nama Pelanggan', 
                'No. WhatsApp', 'Daftar Layanan & Detail', 'Total Tagihan'
            ];

            $callback = function() use($ordersExport, $columns) {
                $file = fopen('php://output', 'w');
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); 
                fputcsv($file, $columns); 

                foreach ($ordersExport as $index => $order) {
                    $layananDetail = [];
                    foreach($order->items->where('status_item', 'Aktif') as $item) {
                        $layananDetail[] = $item->jenis_layanan . " (" . $item->armada . " - " . $item->jumlah_rit . " Rit)";
                    }
                    $layananStr = implode(" | ", $layananDetail); 

                    $row['No'] = $index + 1;
                    $row['No. Invoice'] = $order->no_invoice;
                    $row['Tanggal Penjadwalan'] = \Carbon\Carbon::parse($order->penjadwalan)->format('Y-m-d');
                    $row['Nama Pelanggan'] = $order->nama_pelanggan;
                    $row['No. WhatsApp'] = "+" . $order->no_wa; 
                    $row['Daftar Layanan & Detail'] = $layananStr;
                    $row['Total Tagihan'] = $order->total_pendapatan;

                    fputcsv($file, array(
                        $row['No'], $row['No. Invoice'], $row['Tanggal Penjadwalan'], 
                        $row['Nama Pelanggan'], $row['No. WhatsApp'], $row['Daftar Layanan & Detail'], 
                        $row['Total Tagihan']
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- TAMPILAN VIEW NORMAL ---
        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);
        $orders->appends($request->all());

        return view('admin.invoice_index', compact('orders'));
    }

    // REVISI: Fungsi Detail untuk Modal Preview
    public function getDetail($id)
    {
        $order = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])->findOrFail($id);

        return response()->json([
            'data' => $order,
            'items' => $order->items,
            'tanggal' => $order->created_at->format('d M Y'),
            'no_invoice' => $order->no_invoice
        ]);
    }

    // REVISI: Fungsi Print Invoice (Sesuai Desain PDF)
    public function invoicePrint($id)
    {
        $order = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])->findOrFail($id);

        return view('admin.invoice_print', compact('order'));
    }

    // REVISI: Penambahan logika search, filter tanggal, dan export excel untuk Payment Index
    public function paymentIndex(Request $request)
    {
        // FILTER: Hanya ambil order yang total_pendapatan > 0 (Artinya sudah direkap harganya)
        $query = Order::where('total_pendapatan', '>', 0)->where('status_order', 'Aktif');

        // --- FILTER STATUS (Lunas / Belum Lunas / Cicil) ---
        if ($request->filter == 'lunas') {
            $query->where('status_pembayaran', 'Lunas');
        } elseif ($request->filter == 'belum_lunas') {
            $query->whereIn('status_pembayaran', ['Belum Lunas', 'Cicil']);
        }

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhere('catatan_pembayaran', 'LIKE', "%$search%")
                  ->orWhere('metode_pembayaran', 'LIKE', "%$search%");
            });
        }

        // --- LOGIKA FILTER TANGGAL (Date Range) ---
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            // Filter berdasarkan tanggal penjadwalan
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // --- LOGIKA EXPORT EXCEL (CSV Format) ---
        if ($request->has('export') && $request->export == 'excel') {
            $ordersExport = $query->orderBy('updated_at', 'desc')->get();
            $filename = "Rekap_Pembayaran_" . date('Ymd_His') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = [
                'No', 'Nama Pelanggan', 'No. Invoice / Ref', 'Status Pembayaran', 
                'Total Tagihan', 'Sudah Bayar', 'Sisa Hutang', 
                'Metode Pembayaran', 'Tanggal Lunas'
            ];

            $callback = function() use($ordersExport, $columns) {
                $file = fopen('php://output', 'w');
                // Menambahkan BOM agar Microsoft Excel bisa membaca karakter UTF-8 dengan baik
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); 
                fputcsv($file, $columns); 

                foreach ($ordersExport as $index => $order) {
                    $row['No'] = $index + 1;
                    $row['Nama Pelanggan'] = $order->nama_pelanggan;
                    $row['No. Invoice / Ref'] = $order->catatan_pembayaran ?? $order->no_invoice;
                    $row['Status Pembayaran'] = $order->status_pembayaran;
                    $row['Total Tagihan'] = $order->total_pendapatan;
                    $row['Sudah Bayar'] = $order->jumlah_dibayar;
                    $row['Sisa Hutang'] = $order->total_pendapatan - $order->jumlah_dibayar;
                    $row['Metode Pembayaran'] = $order->metode_pembayaran ?? '-';
                    $row['Tanggal Lunas'] = $order->tanggal_pelunasan ? \Carbon\Carbon::parse($order->tanggal_pelunasan)->format('Y-m-d') : '-';

                    fputcsv($file, array(
                        $row['No'], $row['Nama Pelanggan'], $row['No. Invoice / Ref'], 
                        $row['Status Pembayaran'], $row['Total Tagihan'], $row['Sudah Bayar'], 
                        $row['Sisa Hutang'], $row['Metode Pembayaran'], $row['Tanggal Lunas']
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- TAMPILAN VIEW NORMAL ---
        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);
        $orders->appends($request->all());

        return view('admin.payment_index', compact('orders'));
    }

    public function paymentUpdate(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate(['jumlah_dibayar' => 'required|numeric|min:0']);
        $totalTagihan = $order->total_pendapatan;
        $uangMasuk = $request->jumlah_dibayar;
        if ($uangMasuk >= $totalTagihan) {
            $statusBaru = 'Lunas';
            $tglLunas = $request->tanggal_pelunasan ?? now();
        } elseif ($uangMasuk > 0) {
            $statusBaru = 'Cicil';
            $tglLunas = null;
        } else {
            $statusBaru = 'Belum Lunas';
            $tglLunas = null;
        }
        $order->update(['jumlah_dibayar' => $uangMasuk, 'status_pembayaran' => $statusBaru, 'metode_pembayaran' => $request->metode_pembayaran, 'tanggal_pelunasan' => $tglLunas, 'catatan_pembayaran' => $request->catatan_pembayaran]);
        return redirect()->route('admin.payment_index')->with('success', 'Status: ' . $statusBaru);
    }

    public function laporan(Request $request)
    {
        // Kita gunakan query dari tabel OrderItem agar bisa memfilter per Jenis Jasa, Sopir, dan Plat
        $query = OrderItem::with('order')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_order', 'Aktif')
            ->where('order_items.status_item', 'Aktif')
            ->where('order_items.subtotal_pendapatan', '>', 0);

        // --- FILTERING ---
        if ($request->filled('nama')) {
            $query->where('orders.nama_pelanggan', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('sopir')) {
            $query->where('order_items.nama_sopir', 'like', '%' . $request->sopir . '%');
        }
        if ($request->filled('plat')) {
            $query->where('order_items.plat_armada', 'like', '%' . $request->plat . '%');
        }
        if ($request->filled('layanan')) {
            $query->where('order_items.jenis_layanan', $request->layanan);
        }
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('orders.penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        // --- HITUNG RINGKASAN ---
        $totalNominal = $query->sum('order_items.subtotal_pendapatan');
        $totalLabaKotor = $query->sum('order_items.subtotal_keuntungan');
        $totalLayanan = $query->count();

        // --- HITUNG PENGELUARAN & LABA BERSIH ---
        $queryPengeluaran = Pengeluaran::query();
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $queryPengeluaran->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_selesai]);
        }
        $totalPengeluaran = $queryPengeluaran->sum('nominal');
        $labaBersih = $totalLabaKotor - $totalPengeluaran;

        // --- DATA UNTUK TABEL & DROPDOWN ---
        $orders = $query->select('order_items.*', 'orders.penjadwalan', 'orders.nama_pelanggan', 'orders.no_wa')
            ->orderBy('orders.penjadwalan', 'desc')
            ->paginate(25);

        $daftarLayanan = Service::distinct()->pluck('nama_layanan');

        return view('admin.laporan', compact(
            'orders',
            'totalNominal',
            'totalLabaKotor',
            'totalLayanan',
            'totalPengeluaran',
            'labaBersih',
            'daftarLayanan'
        ));
    }

    public function getRealtimeDashboard()
    {
        // REVISI LOGIKA BADGE: Hanya hitung order AKTIF yang totalnya 0 DAN masih punya item AKTIF
        $badgeCount = Order::where('status_order', 'Aktif')
            ->where('total_pendapatan', 0)
            ->whereHas('items', function ($query) {
                $query->where('status_item', 'Aktif');
            })
            ->count();

        // Gunakan Carbon Jakarta agar sinkron dengan real life
        $today = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Pastikan memuat relasi items yang aktif saja agar tabel sinkron
        $jadwalHariIni = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])
            ->whereDate('penjadwalan', $today)
            ->where('status_order', 'Aktif')
            ->whereHas('items', function ($query) {
                $query->where('status_item', 'Aktif');
            })
            ->get();

        return response()->json([
            'badge' => $badgeCount,
            'html' => view('admin.partials.dashboard_table', compact('jadwalHariIni'))->render()
        ]);
    }

    // REVISI: Penambahan logika search, filter tanggal, dan export excel untuk Rekap Cancel
    public function indexCancel(Request $request)
    {
        $query = OrderItem::with('order')
            ->where('status_item', 'Batal');

        // --- LOGIKA PENCARIAN (Search) ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jenis_layanan', 'LIKE', "%$search%")
                  ->orWhere('alasan_cancel', 'LIKE', "%$search%")
                  ->orWhereHas('order', function ($sq) use ($search) {
                      $sq->where('nama_pelanggan', 'LIKE', "%$search%");
                  });
            });
        }

        // --- LOGIKA FILTER TANGGAL (Date Range berdasarkan tanggal Batal/updated_at) ---
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            // Karena menggunakan datetime, tambahkan jam agar mencakup full satu hari
            $start = $request->tgl_mulai . " 00:00:00";
            $end = $request->tgl_selesai . " 23:59:59";
            $query->whereBetween('updated_at', [$start, $end]);
        }

        // --- LOGIKA EXPORT EXCEL (CSV Format) ---
        if ($request->has('export') && $request->export == 'excel') {
            $ordersExport = $query->orderBy('updated_at', 'desc')->get();
            $filename = "Monitoring_Cancel_" . date('Ymd_His') . ".csv";
            
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $columns = [
                'No', 'Tgl Batal', 'Nama Pelanggan', 'Jenis Layanan', 'Armada', 
                'Alasan Pembatalan', 'Potensi Kehilangan Pendapatan'
            ];

            $callback = function() use($ordersExport, $columns) {
                $file = fopen('php://output', 'w');
                fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // BOM untuk UTF-8 Excel
                fputcsv($file, $columns); 

                foreach ($ordersExport as $index => $item) {
                    $row['No'] = $index + 1;
                    $row['Tgl Batal'] = \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d H:i');
                    $row['Nama Pelanggan'] = $item->order->nama_pelanggan ?? 'N/A';
                    $row['Jenis Layanan'] = $item->jenis_layanan;
                    $row['Armada'] = $item->armada;
                    $row['Alasan Pembatalan'] = $item->alasan_cancel ?? '-';
                    $row['Potensi Kehilangan Pendapatan'] = $item->subtotal_pendapatan;

                    fputcsv($file, array(
                        $row['No'], $row['Tgl Batal'], $row['Nama Pelanggan'], 
                        $row['Jenis Layanan'], $row['Armada'], $row['Alasan Pembatalan'], 
                        $row['Potensi Kehilangan Pendapatan']
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // --- TAMPILAN VIEW NORMAL ---
        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('admin.rekap_cancel', compact('orders'));
    }

    public function cancel(Request $request, $id)
    {
        Order::findOrFail($id)->update(['status_order' => 'Batal', 'alasan_cancel' => $request->alasan_cancel]);
        return back()->with('success', 'Order berhasil dibatalkan.');
    }

    public function restore($id)
    {
        Order::findOrFail($id)->update(['status_order' => 'Aktif', 'alasan_cancel' => null]);
        return redirect()->route('admin.rekap_order')->with('success', 'Order dikembalikan ke daftar aktif.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->logs()->delete();
        $order->delete();

        return redirect()->route('admin.rekap_order')->with('success', 'Data order telah dihapus secara permanen!');
    }

    public function cancelItem(Request $request, $id)
    {
        $item = OrderItem::findOrFail($id);
        $item->update([
            'status_item' => 'Batal',
            'alasan_cancel' => $request->alasan_cancel
        ]);

        // Hitung ulang total di tabel orders
        $this->recalculateOrder($item->order_id);

        return back()->with('success', 'Layanan berhasil dibatalkan.');
    }

    public function destroyItem($id)
    {
        $item = OrderItem::findOrFail($id);
        $orderId = $item->order_id;
        $item->delete();

        // Hitung ulang total di tabel orders
        $this->recalculateOrder($orderId);

        return back()->with('success', 'Layanan dihapus permanen.');
    }

    public function restoreItem($id)
    {
        $item = OrderItem::findOrFail($id);
        $item->update(['status_item' => 'Aktif', 'alasan_cancel' => null]);

        // Hitung ulang total di tabel orders
        $this->recalculateOrder($item->order_id);

        return back()->with('success', 'Layanan diaktifkan kembali.');
    }

    // Fungsi pembantu untuk update total pendapatan nota secara otomatis
    private function recalculateOrder($orderId)
    {
        $order = Order::with('items')->find($orderId);
        $totalPendapatan = $order->items->where('status_item', 'Aktif')->sum('subtotal_pendapatan');
        $totalKeuntungan = $order->items->where('status_item', 'Aktif')->sum('subtotal_keuntungan');

        $order->update([
            'total_pendapatan' => $totalPendapatan,
            'total_keuntungan' => $totalKeuntungan
        ]);
    }
}
