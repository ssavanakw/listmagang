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
        Schema::table('skl_settings', function (Blueprint $table) {
            $table->text('activity_description')->nullable()->after('leader_title');
            $table->text('participant_achievement')->nullable()->after('activity_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skl_settings', function (Blueprint $table) {
            $table->dropColumn('activity_description');
            $table->dropColumn('participant_achievement');
        });
    }
};
