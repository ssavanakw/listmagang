<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skl_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 100);
            $table->string('company_address', 255);
            $table->string('company_city', 100);
            $table->string('leader_name', 100);
            $table->string('leader_title', 100);
            $table->string('logo_path')->nullable();
            $table->string('stamp_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skl_settings');
    }
};
