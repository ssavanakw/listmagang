<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            // Hapus relasi ke user
            if (Schema::hasColumn('downloads', 'user_id')) {
                $table->dropColumn('user_id');
            }

            // Pastikan kolom code ada dan dibuat unik
            if (!Schema::hasColumn('downloads', 'code')) {
                $table->string('code')->nullable()->after('id');
            }

            $table->unique('code');
        });
    }

    public function down(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->dropColumn('code');
        });
    }
};
