<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            if (!Schema::hasColumn('reservasis', 'kode_booking')) {
                $table->string('kode_booking')->nullable()->after('kode');
            }
            if (!Schema::hasColumn('reservasis', 'durasi_jam')) {
                $table->integer('durasi_jam')->default(1)->after('jam_selesai');
            }
            if (!Schema::hasColumn('reservasis', 'subtotal')) {
                $table->integer('subtotal')->default(0)->after('durasi_jam');
            }
            if (!Schema::hasColumn('reservasis', 'diskon')) {
                $table->integer('diskon')->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('reservasis', 'total_bayar')) {
                $table->integer('total_bayar')->default(0)->after('diskon');
            }
            if (!Schema::hasColumn('reservasis', 'catatan')) {
                $table->text('catatan')->nullable()->after('status');
            }
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayarans', 'metode_pembayaran')) {
                $table->string('metode_pembayaran', 50)->nullable()->after('reservasi_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropColumn(['kode_booking', 'durasi_jam', 'subtotal', 'diskon', 'total_bayar', 'catatan']);
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropColumn(['metode_pembayaran']);
        });
    }
};