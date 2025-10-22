<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoaSettings;

class LoaSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoaSettings::create([
            'company_name' => 'Seven Inc.',
            'company_contact_email' => 'contact@seveninc.com',
            'company_contact_phone' => '021-5555555',
            'company_address' => 'Jl. Raya Janti Gg. Harjuna No.59, Yogyakarta, Indonesia',
            'company_logo' => 'logo_seveninc.png',
            'signatory_name' => 'John Doe',
            'signatory_position' => 'CEO',
            'signatory_image' => 'signature-john-doe.png',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'opening_greeting' => 'Dengan ini kami mengonfirmasi bahwa Anda telah diterima untuk mengikuti program magang di perusahaan kami.',
            'closing_greeting' => 'Harap konfirmasi kehadiran Anda melalui email atau telepon yang tertera di bawah ini.',
        ]);
    }
}
