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
        Schema::create('internship_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('born_date');
            $table->string('student_id');
            $table->string('email')->unique();
            $table->string('gender');
            $table->string('phone_number');
            $table->string('institution_name');
            $table->string('study_program');
            $table->string('faculty');
            $table->string('current_city');
            $table->string('internship_reason');
            $table->string('internship_type');
            $table->string('internship_arrangement');
            $table->string('current_status');
            $table->string('english_book_ability');
            $table->string('supervisor_contact')->nullable();
            $table->string('internship_interest');
            $table->string('internship_interest_other')->nullable();
            $table->string('design_software')->nullable();
            $table->string('video_software')->nullable();
            $table->string('programming_languages')->nullable();
            $table->string('digital_marketing_type')->nullable();
            $table->string('digital_marketing_type_other')->nullable();
            $table->string('laptop_equipment')->nullable();
            $table->string('owned_tools')->nullable();
            $table->string('owned_tools_other')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('internship_info_sources')->nullable();
            $table->string('internship_info_other')->nullable();
            $table->string('cv_ktp_portofolio_pdf')->nullable();
            $table->string('portofolio_visual')->nullable();
            $table->string('current_activities')->nullable();
            $table->string('boarding_info')->nullable();
            $table->string('family_status');
            $table->string('parent_wa_contact')->nullable();
            $table->string('social_media_instagram')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_registrations');
    }
};
