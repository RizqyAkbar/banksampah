<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika nama file sudah jamak, tapi aman didefinisikan)
    protected $table = 'order_logs';

    // Kolom yang boleh diisi melalui OrderLog::create([...])
    protected $fillable = [
        'order_id',
        'perubahan',
        'detail',
        'alasan',
        'admin_name'
    ];

    /**
     * Relasi ke model Order
     * Setiap log ini dimiliki oleh satu Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}