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
        Schema::table('internship_registrations', function (Blueprint $table) {
            $table->string('internship_status', 20)
                  ->default('new') // atau 'pending' kalau kamu mau
                  ->index()
                  ->after('social_media_instagram');
        });
    }
    public function down(): void
    {
        Schema::table('internship_registrations', function (Blueprint $table) {
            $table->dropColumn('internship_status');
        });
    }
};
