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
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentExport;
use App\Exports\LaporanExport;

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

    public function exportExcel(Request $request)
    {
        $request->validate([
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
        ]);

        $namaFile = 'Rekap_Order_' . $request->tgl_mulai . '_to_' . $request->tgl_selesai . '.xlsx';

        return Excel::download(new OrderExport($request->tgl_mulai, $request->tgl_selesai), $namaFile);
    }

    public function exportPaymentExcel(Request $request)
    {
        $request->validate([
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
        ]);

        return Excel::download(
            new PaymentExport($request->tgl_mulai, $request->tgl_selesai),
            'Laporan_Pembayaran_' . $request->tgl_mulai . '_to_' . $request->tgl_selesai . '.xlsx'
        );
    }

    public function exportLaporan(Request $request)
    {
        $tgl = date('d-m-Y');
        return Excel::download(new LaporanExport($request), "Laporan_Performa_Jasa_{$tgl}.xlsx");
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
        // --- LOGIKA EXPORT EXCEL LANGSUNG MENGGUNAKAN ORDEREXPORT ---
        if ($request->has('export') && $request->export == 'excel') {
            // Berikan default range jika user mengekspor tanpa memfilter tanggal
            // Karena ini penjadwalan (bisa masa depan), kita set batas atas ke 5 tahun ke depan
            $tgl_mulai = $request->tgl_mulai ?: '2023-01-01'; 
            $tgl_selesai = $request->tgl_selesai ?: \Carbon\Carbon::now()->addYears(5)->format('Y-m-d');
            
            return Excel::download(
                new OrderExport($tgl_mulai, $tgl_selesai), 
                'Data_Rekap_Order_' . date('Ymd_His') . '.xlsx'
            );
        }

        // --- LOGIKA NORMAL (TAMPILAN WEB & PAGINATION) ---
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
    // --- FUNGSI TAMPILAN WEB INVOICE ---
    public function invoiceIndex(Request $request)
    {
        $query = Order::with(['items' => function ($query) {
            $query->where('status_item', 'Aktif');
        }])
        ->where('status_order', 'Aktif')
        ->where('total_pendapatan', '>', 0);

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

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);
        $orders->appends($request->all());

        return view('admin.invoice_index', compact('orders'));
    }

    // --- FUNGSI KHUSUS EXPORT EXCEL INVOICE ---
    public function exportInvoice(Request $request)
    {
        return Excel::download(
            new \App\Exports\InvoiceExport($request), 
            "Laporan_Tagihan_" . date('Ymd_His') . ".xlsx"
        );
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
        // --- LOGIKA EXPORT EXCEL LANGSUNG MENGGUNAKAN PAYMENTEXPORT ---
        if ($request->has('export') && $request->export == 'excel') {
            // Jika tanggal tidak diisi, berikan default range yang cukup luas (misal dari 2023)
            $tgl_mulai = $request->tgl_mulai ?: '2023-01-01'; 
            $tgl_selesai = $request->tgl_selesai ?: date('Y-m-d');
            
            return Excel::download(
                new PaymentExport($tgl_mulai, $tgl_selesai), 
                'Rekap_Pembayaran_' . date('Ymd_His') . '.xlsx'
            );
        }

        // --- LOGIKA NORMAL (TAMPILAN WEB & PAGINATION) ---
        $query = Order::where('total_pendapatan', '>', 0)->where('status_order', 'Aktif');

        if ($request->filter == 'lunas') {
            $query->where('status_pembayaran', 'Lunas');
        } elseif ($request->filter == 'belum_lunas') {
            $query->whereIn('status_pembayaran', ['Belum Lunas', 'Cicil']);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_invoice', 'LIKE', "%$search%")
                  ->orWhere('catatan_pembayaran', 'LIKE', "%$search%")
                  ->orWhere('metode_pembayaran', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('penjadwalan', [$request->tgl_mulai, $request->tgl_selesai]);
        }

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
        // --- LOGIKA EXPORT EXCEL ---
        // Jika tombol Export ditekan, lemparkan keseluruhan request ke LaporanExport
        if ($request->has('export') && $request->export == 'excel') {
            $tgl = date('d-m-Y');
            return Excel::download(new LaporanExport($request), "Laporan_Performa_Jasa_{$tgl}.xlsx");
        }

        // --- LOGIKA NORMAL (VIEW TABEL DI BROWSER) ---
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
            
        // Appends agar query filter tidak hilang saat pindah halaman (pagination)
        $orders->appends($request->all());

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

    // --- HALAMAN MONITORING CANCEL (TAMPILAN WEB) ---
    public function indexCancel(Request $request)
    {
        $query = OrderItem::with('order')->where('status_item', 'Batal');

        // PENCARIAN (Search)
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

        // FILTER TANGGAL 
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $start = $request->tgl_mulai . " 00:00:00";
            $end = $request->tgl_selesai . " 23:59:59";
            $query->whereBetween('updated_at', [$start, $end]);
        }

        $orders = $query->orderBy('updated_at', 'desc')->paginate(10);
        $orders->appends($request->all());

        return view('admin.rekap_cancel', compact('orders'));
    }

    // --- FUNGSI KHUSUS EXPORT EXCEL MONITORING CANCEL ---
    public function exportCancel(Request $request)
    {
        // Memanggil class CancelExport yang baru saja kita buat
        return Excel::download(
            new \App\Exports\CancelExport($request), 
            "Monitoring_Cancel_" . date('Ymd_His') . ".xlsx"
        );
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
