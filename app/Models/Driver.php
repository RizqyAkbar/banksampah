<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    // Tambahkan baris di bawah ini
    protected $fillable = [
        'nama_sopir',
        'no_wa_sopir',
        'plat_armada',
    ];
}
