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
        Schema::create('document_downloads', function (Blueprint $table) {
            $table->id();

            // Siapa yang download
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Terkait pendaftaran magang mana (opsional)
            $table->foreignId('internship_registration_id')
                  ->nullable()
                  ->constrained('internship_registrations')
                  ->nullOnDelete();

            // Jenis dokumen: SKL / LOA (bisa tambah lain di masa depan)
            $table->string('doc_type', 20); // 'SKL' | 'LOA' | ...

            // Informasi file
            $table->string('file_path')->nullable(); // path relatif di storage
            $table->string('file_url')->nullable();  // URL publik (asset)

            // Jejak & status
            $table->timestamp('downloaded_at')->useCurrent();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status', 20)->default('success'); // success|failed
            $table->text('error_message')->nullable();

            $table->timestamps();

            // Index untuk reporting
            $table->index(['doc_type', 'downloaded_at']);
            $table->index(['user_id', 'doc_type']);
            $table->index('internship_registration_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_downloads');
    }
};
