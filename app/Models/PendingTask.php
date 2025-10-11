<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PendingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'description',
        'status'
    ];

    // Definisikan relasi dengan User (jika dibutuhkan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
