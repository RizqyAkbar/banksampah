<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel orders (Cascade: Jika order dihapus, item ikut hapus)
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Detail Layanan (Revisi 1 Multiple Order)
            $table->string('jenis_layanan'); 
            $table->string('armada');
            $table->string('nama_sopir')->nullable();
            $table->string('no_wa_sopir')->nullable();
            $table->string('plat_armada')->nullable();
            
            // Kalkulasi Ritase
            $table->integer('jumlah_rit')->default(0);
            $table->decimal('harga_per_rit', 15, 2)->default(0);
            $table->decimal('hpp_per_rit', 15, 2)->default(0);
            $table->decimal('subtotal_pendapatan', 15, 2)->default(0);
            $table->decimal('subtotal_keuntungan', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
