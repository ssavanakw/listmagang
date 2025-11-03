<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            // Add 'code' column if it doesn't exist
            if (!Schema::hasColumn('downloads', 'code')) {
                $table->string('code')->unique()->after('name');
            }

            // Add 'model_url' column if it doesn't exist
            if (!Schema::hasColumn('downloads', 'model_url')) {
                $table->string('model_url')->nullable()->after('brand');
            }

            // Add 'user_id' column with foreign key if it doesn't exist
            if (!Schema::hasColumn('downloads', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('code');

                // Make sure foreign key doesn't already exist
                if (!Schema::hasColumn('downloads', 'user_id')) {
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('downloads', function (Blueprint $table) {
            // Drop foreign key first before column
            if (Schema::hasColumn('downloads', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('downloads', 'model_url')) {
                $table->dropColumn('model_url');
            }

            if (Schema::hasColumn('downloads', 'code')) {
                $table->dropUnique(['code']);
                $table->dropColumn('code');
            }
        });
    }
};
