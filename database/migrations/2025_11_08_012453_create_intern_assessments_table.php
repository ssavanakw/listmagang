<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intern_assessments', function (Blueprint $table) {
            $table->id();

            // Data peserta
            $table->string('fullname');
            $table->string('nim_or_nis')->nullable();
            $table->string('study_program')->nullable();
            $table->string('div')->nullable();

            // Penilaian
            $table->json('aspek_penilaian')->nullable();
            $table->float('rata_rata')->nullable();

            // Informasi perusahaan
            $table->string('company_name')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_logo_path')->nullable();

            // Informasi pejabat & tanda tangan
            $table->string('signature_name')->nullable();
            $table->string('signature_position')->nullable();
            $table->string('signature_image_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intern_assessments');
    }
};
