<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternshipRegistration extends Model
{
    use HasFactory;

    protected $table = 'internship_registrations';

    protected $fillable = [
        'fullname', 
        'born_date', 
        'student_id', 
        'email', 
        'gender', 
        'phone_number',
        'institution_name', 
        'study_program', 
        'faculty', 
        'current_city',
        'internship_reason', 
        'internship_type', 
        'internship_arrangement', 
        'current_status',
        'english_book_ability', 
        'supervisor_contact', 
        'internship_interest', 
        'internship_interest_other',
        'design_software', 
        'video_software', 
        'programming_languages', 
        'digital_marketing_type',
        'digital_marketing_type_other', 
        'laptop_equipment', 
        'owned_tools', 
        'owned_tools_other',
        'start_date', 
        'end_date', 
        'internship_info_sources', 
        'internship_info_other',
        'cv_ktp_portofolio_pdf', 
        'portofolio_visual', 
        'current_activities',
        'boarding_info', 
        'family_status', 
        'parent_wa_contact', 
        'social_media_instagram', 
    ];

    protected $casts = [
    'owned_tools' => 'array',
    'internship_info_sources' => 'array',
];


}
