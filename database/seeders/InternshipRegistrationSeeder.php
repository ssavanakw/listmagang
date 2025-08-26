<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternshipRegistration;
use Illuminate\Support\Str;

class InternshipRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            InternshipRegistration::create([
                'fullname' => "Peserta Magang $i",
                'born_date' => now()->subYears(20)->format('Y-m-d'),
                'student_id' => "12345$i",
                'email' => "peserta$i@example.com",
                'gender' => 'male',
                'phone_number' => "08123456789$i",
                'institution_name' => "Universitas Contoh $i",
                'study_program' => "Teknik Informatika",
                'faculty' => "Fakultas Teknik",
                'current_city' => "Kota $i",
                'internship_reason' => "Ingin belajar pengalaman kerja.",
                'internship_type' => 'self',
                'internship_arrangement' => 'wfo',
                'current_status' => 'school-or-college',
                'english_book_ability' => 'ican',
                'supervisor_contact' => "Pembimbing $i",
                'internship_interest' => "Design",
                'internship_interest_other' => null,
                'design_software' => "Photoshop, Illustrator",
                'video_software' => "Premiere Pro",
                'programming_languages' => "PHP, JavaScript",
                'digital_marketing_type' => 'organic',
                'digital_marketing_type_other' => null,
                'laptop_equipment' => 'yes-laptop',
                'owned_tools' => json_encode(['Kamera', 'Tripod']),
                'owned_tools_other' => 'Drawing Tablet',
                'start_date' => now()->addDays(10)->format('Y-m-d'),
                'end_date' => now()->addDays(70)->format('Y-m-d'),
                'internship_info_sources' => json_encode(['Instagram', 'Teman']),
                'internship_info_other' => null,
                'cv_ktp_portofolio_pdf' => null,
                'portofolio_visual' => null,
                'current_activities' => "Kuliah semester 6",
                'boarding_info' => 'no',
                'family_status' => 'yes',
                'parent_wa_contact' => "0812345678$i",
                'social_media_instagram' => "@pesertamagang$i",
            ]);
        }
    }
}
