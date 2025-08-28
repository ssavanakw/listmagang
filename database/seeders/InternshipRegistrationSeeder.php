<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternshipRegistration;

class InternshipRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // 100 data dengan status acak (ditentukan di Factory)
        InternshipRegistration::factory()->count(100)->create();
    }
}
