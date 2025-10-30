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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // Name of the user
            $table->string('user_id');         // Unique user identifier (e.g., user ID or code)
            $table->string('angkatan')->nullable();  // Year or cohort
            $table->string('instansi')->nullable();  // Institution name
            $table->string('brand')->nullable();     // Brand of the download
            $table->boolean('has_downloaded')->default(false);  // Status of download
            $table->timestamp('downloaded_at')->nullable();   // Timestamp when the file was downloaded
            $table->timestamps();  // Laravel timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
