<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_pelanggan')->default('Baru'); // Baru / Lama
            $table->integer('jumlah_rit')->default(0);
            $table->integer('harga_per_rit')->default(0);
            $table->integer('hpp_per_rit')->default(0);
            $table->integer('jumlah_pendapatan')->default(0);
            $table->integer('jumlah_keuntungan')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status_pelanggan', 'jumlah_rit', 'harga_per_rit', 'hpp_per_rit', 'jumlah_pendapatan', 'jumlah_keuntungan']);
        });
    }
};
