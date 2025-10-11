<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'leave_type', 
        'leave_date', 
        'reason',
        'status',
    ];

    // Definisikan relasi dengan User (jika dibutuhkan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
