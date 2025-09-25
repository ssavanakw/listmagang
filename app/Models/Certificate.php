<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    // Specify the table associated with the model if it doesn't follow the default naming convention
    protected $table = 'certificates'; // Table name (defaults to plural of model name)

    // Specify the fillable attributes that can be mass-assigned
    protected $fillable = [
        'name', 
        'division',
        'company',
        'brand',
        'start_date',
        'end_date',
        'city',
        'background_image',
        'logo1',
        'logo2',
        'signature_image1',
        'signature_image2',
        'role1',
        'role2',
        'name_signatory1',
        'name_signatory2',
        'serial_number'
    ];

    // If you need to specify hidden attributes (e.g., passwords), you can use the $hidden property
    // protected $hidden = ['password'];

    // If the table has timestamps, use the $timestamps property to enable/disable
    public $timestamps = true;  // By default, Laravel uses created_at and updated_at columns
}
