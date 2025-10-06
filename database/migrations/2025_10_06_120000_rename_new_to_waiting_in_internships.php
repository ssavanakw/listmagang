<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('internship_registrations')
            ->where('internship_status', 'new')
            ->update(['internship_status' => 'waiting']);
    }

    public function down(): void
    {
        DB::table('internship_registrations')
            ->where('internship_status', 'waiting')
            ->update(['internship_status' => 'new']);
    }
};
