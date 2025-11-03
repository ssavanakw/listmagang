<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'user_id', 
        'angkatan',
        'instansi',
        'brand',
        'model_url',
        'has_downloaded',
        'downloaded_at',
    ];

    // Define any additional methods or relationships if needed
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}