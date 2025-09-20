<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel lama (versi per-tahun) bila ada
        Schema::dropIfExists('certificate_counters');

        // Buat tabel baru: counter reset per-bulan
        Schema::create('certificate_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->index();   // 2025
            $table->unsignedTinyInteger('month')->index();   // 1-12
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();

            $table->unique(['year', 'month']); // 1 baris per kombinasi tahun-bulan
        });
    }

    public function down(): void
    {
        // Kembali ke versi per-tahun jika di-rollback
        Schema::dropIfExists('certificate_counters');

        Schema::create('certificate_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
            $table->unique(['year']);
        });
    }
};
