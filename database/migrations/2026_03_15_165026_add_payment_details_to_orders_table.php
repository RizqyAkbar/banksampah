<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek satu per satu, jika kolom belum ada baru dibuat
            if (!Schema::hasColumn('orders', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable()->after('status_pembayaran');
            }
            if (!Schema::hasColumn('orders', 'catatan_pembayaran')) {
                $table->text('catatan_pembayaran')->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('orders', 'tanggal_pelunasan')) {
                $table->timestamp('tanggal_pelunasan')->nullable()->after('catatan_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran', 'catatan_pembayaran', 'tanggal_pelunasan']);
        });
    }
};