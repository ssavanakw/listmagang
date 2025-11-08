<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'nim_or_nis',
        'study_program',
        'div',
        'aspek_penilaian',
        'rata_rata',
    ];

    protected $casts = [
        'aspek_penilaian' => 'array',
    ];
}
