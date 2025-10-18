<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SKLSetting extends Model
{
    protected $table = 'skl_settings'; // <- tambahkan ini biar aman

    protected $fillable = [
        'company_name',
        'company_address',
        'company_city',
        'leader_name',
        'leader_title',
        'logo_path',
        'stamp_path',
        'activity_description', 
        'participant_achievement',
    ];
}
