<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intern_assessments', function (Blueprint $table) {
            // Informasi perusahaan
            $table->string('company_name')->nullable()->after('rata_rata');
            $table->text('company_address')->nullable()->after('company_name');
            $table->string('company_logo_path')->nullable()->after('company_address');

            // Informasi tanda tangan & pejabat
            $table->string('signature_name')->nullable()->after('company_logo_path');
            $table->string('signature_position')->nullable()->after('signature_name');
            $table->string('signature_image_path')->nullable()->after('signature_position');
        });
    }

    public function down(): void
    {
        Schema::table('intern_assessments', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'company_address',
                'company_logo_path',
                'signature_name',
                'signature_position',
                'signature_image_path',
            ]);
        });
    }
};
