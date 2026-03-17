<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Menggunakan fillable (Whitelist) jauh lebih aman daripada guarded
    protected $fillable = [
        'order_id',
        'jenis_layanan',
        'armada',
        'nama_sopir',
        'no_wa_sopir',
        'plat_armada',
        'jumlah_rit',
        'harga_per_rit',
        'hpp_per_rit',
        'subtotal_pendapatan',
        'subtotal_keuntungan',
        'status_item',   // Kolom baru untuk fitur cancel per item
        'alasan_cancel', // Kolom baru untuk fitur cancel per item
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}