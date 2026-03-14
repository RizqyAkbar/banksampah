<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('orders', function (Blueprint $table) {
            // Cek dulu, kalau belum ada baru buat kolomnya
            if (!Schema::hasColumn('orders', 'link_maps')) {
                $table->string('link_maps')->nullable();
            }
            if (!Schema::hasColumn('orders', 'nama_sopir')) {
                $table->string('nama_sopir')->nullable();
            }
            if (!Schema::hasColumn('orders', 'no_wa_sopir')) {
                $table->string('no_wa_sopir')->nullable();
            }
            if (!Schema::hasColumn('orders', 'plat_armada')) {
                $table->string('plat_armada')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['nama_sopir', 'no_wa_sopir', 'plat_armada', 'link_maps']);
        });
    }
};
