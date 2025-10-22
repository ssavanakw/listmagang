<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name', 
        'company_contact_email', 
        'company_contact_phone', 
        'company_address', 
        'company_logo', 
        'signatory_name', 
        'signatory_position', 
        'signatory_image',
        'start_date',
        'end_date',
        'opening_greeting', 
        'closing_greeting',
        'internship_registration_id',
        'user_id',
    ];
}
