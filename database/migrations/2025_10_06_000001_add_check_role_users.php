<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan constraint hanya jika belum ada
        if (Schema::hasTable('users')) {
            try {
                DB::statement("
                    ALTER TABLE users
                    ADD CONSTRAINT chk_users_role
                    CHECK (role IN ('admin','user','pemagang'))
                ");
            } catch (\Throwable $e) {
                // Abaikan jika database tidak mendukung CHECK constraint (mis. MariaDB lawas)
                info('Skip CHECK constraint: '.$e->getMessage());
            }
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE users DROP CONSTRAINT chk_users_role");
        } catch (\Throwable $e) {
            info('Skip DROP CHECK constraint: '.$e->getMessage());
        }
    }
};
