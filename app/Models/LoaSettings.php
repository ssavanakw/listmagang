<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name','company_contact_email','signatory_name','signatory_position',
        'logo_path','stamp_path','header_text','footer_text'
    ];
}
