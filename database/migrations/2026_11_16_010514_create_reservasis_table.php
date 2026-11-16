<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete();
            $table->string('kode')->unique();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('total_harga');
            $table->enum('status', [
                'Menunggu pembayaran',
                'Menunggu verifikasi',
                'Dikonfirmasi',
                'Selesai',
                'Dibatalkan'
            ])->default('Menunggu pembayaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
