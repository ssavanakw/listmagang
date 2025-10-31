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
        if (!Schema::hasColumn('downloads', 'has_downloaded')) {
            Schema::table('downloads', function (Blueprint $table) {
                $table->boolean('has_downloaded')->default(false);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            if (Schema::hasColumn('downloads', 'has_downloaded')) {
                $table->dropColumn('has_downloaded');
            }
        });
    }
};
