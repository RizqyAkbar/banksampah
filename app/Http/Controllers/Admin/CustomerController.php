<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Fitur Pencarian (Berdasarkan Nama, WA, atau Alamat)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'LIKE', "%$search%")
                  ->orWhere('no_wa', 'LIKE', "%$search%")
                  ->orWhere('alamat', 'LIKE', "%$search%");
            });
        }

        // Fitur Filter Status
        if ($request->filled('status')) {
            $query->where('status_pelanggan', $request->status);
        }

        // Urutkan dari yang terbaru diupdate
        $customers = $query->orderBy('updated_at', 'desc')->paginate(10);
        $customers->appends($request->all());

        return view('admin.customer_index', compact('customers'));
    }

    // FUNGSI BARU: API Pencarian Customer untuk fitur Auto-Fill
    public function search(Request $request)
    {
        $request->validate(['no_wa' => 'required']);

        // Bersihkan input nomor WA agar konsisten (pakai awalan 62)
        $nomorBersih = ltrim($request->no_wa, '0');
        $noWaCari = '62' . $nomorBersih;

        $customer = Customer::where('no_wa', $noWaCari)->first();

        if ($customer) {
            return response()->json([
                'success' => true,
                'data' => $customer
            ]);
        }

        return response()->json(['success' => false]);
    }

    // FUNGSI BARU: API Pencarian Customer untuk Modal Recall
    public function apiSearch(Request $request)
    {
        $keyword = $request->q;

        if (empty($keyword)) {
            return response()->json(['data' => []]);
        }

        $customers = Customer::where('nama_pelanggan', 'LIKE', "%{$keyword}%")
            ->orWhere('no_wa', 'LIKE', "%{$keyword}%")
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json(['data' => $customers]);
    }
}