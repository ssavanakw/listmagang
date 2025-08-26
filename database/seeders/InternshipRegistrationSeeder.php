<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternshipRegistration;
use Illuminate\Support\Str;

class InternshipRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        InternshipRegistration::factory()->count(20)->create();
    }
}
