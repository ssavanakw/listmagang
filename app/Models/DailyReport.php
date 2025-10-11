<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'date', 
        'activities', 
        'challenges',
    ];

    // Definisikan relasi dengan User (jika dibutuhkan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
