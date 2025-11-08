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
            $table->string('fullname');
            $table->string('nim_or_nis')->nullable();
            $table->string('study_program')->nullable();
            $table->string('div')->nullable();
            $table->json('aspek_penilaian')->nullable();
            $table->float('rata_rata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intern_assessments');
    }
};
