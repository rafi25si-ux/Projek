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
        Schema::create('fasilitas_studio', function (Blueprint $table) {
            $table->foreignId('studio_id')->constrained('studios')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');
            $table->primary(['studio_id', 'fasilitas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_studio');
    }
};
