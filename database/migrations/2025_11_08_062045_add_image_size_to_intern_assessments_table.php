<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intern_assessments', function (Blueprint $table) {
            // Tambahan kolom untuk ukuran logo dan tanda tangan
            $table->integer('logo_width')->nullable()->after('signature_image_path');
            $table->integer('logo_height')->nullable()->after('logo_width');
            $table->integer('sig_width')->nullable()->after('logo_height');
            $table->integer('sig_height')->nullable()->after('sig_width');
        });
    }

    public function down(): void
    {
        Schema::table('intern_assessments', function (Blueprint $table) {
            $table->dropColumn(['logo_width', 'logo_height', 'sig_width', 'sig_height']);
        });
    }
};
