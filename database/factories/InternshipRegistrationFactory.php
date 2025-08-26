<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InternshipRegistration>
 */
class InternshipRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name(),
            'born_date' => $this->faker->date(),
            'student_id' => $this->faker->unique()->numerify('NIM#######'),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'phone_number' => $this->faker->phoneNumber(),
            'institution_name' => $this->faker->company(),
            'study_program' => $this->faker->word(),
            'faculty' => $this->faker->word(),
            'current_city' => $this->faker->city(),
            'internship_reason' => $this->faker->sentence(),
            'internship_type' => $this->faker->randomElement(['Full Time', 'Part Time']),
            'internship_arrangement' => $this->faker->randomElement(['Online', 'Offline', 'Hybrid']),
            'current_status' => $this->faker->randomElement(['Student', 'Fresh Graduate']),
            'english_book_ability' => $this->faker->randomElement(['Good', 'Fair', 'Poor']),
            'supervisor_contact' => $this->faker->phoneNumber(),
            'internship_interest' => $this->faker->word(),
            'internship_interest_other' => $this->faker->optional()->word(),
            'design_software' => $this->faker->optional()->word(),
            'video_software' => $this->faker->optional()->word(),
            'programming_languages' => $this->faker->optional()->randomElement(['PHP', 'JavaScript', 'Python']),
            'digital_marketing_type' => $this->faker->optional()->word(),
            'digital_marketing_type_other' => $this->faker->optional()->word(),
            'laptop_equipment' => $this->faker->optional()->randomElement(['Yes', 'No']),
            'owned_tools' => $this->faker->optional()->word(),
            'owned_tools_other' => $this->faker->optional()->word(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'internship_info_sources' => $this->faker->sentence(),
            'internship_info_other' => $this->faker->optional()->sentence(),
            'cv_ktp_portofolio_pdf' => 'dummy_cv.pdf',
            'portofolio_visual' => 'dummy_portofolio.png',
            'current_activities' => $this->faker->optional()->sentence(),
            'boarding_info' => $this->faker->randomElement(['Yes', 'No']),
            'family_status' => $this->faker->randomElement(['Single', 'Married']),
            'parent_wa_contact' => $this->faker->phoneNumber(),
            'social_media_instagram' => '@' . $this->faker->userName(),
        ];
    }
}
