<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Feedback extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'feedback'; // sesuaikan dengan nama tabelmu

    /**
     * Kolom yang bisa diisi massal (fillable)
     */
    protected $fillable = [
        'name',
        'feedback',
    ];

    /**
     * Kolom bertipe tanggal agar otomatis jadi instance Carbon
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Casting otomatis kolom tertentu (opsional tapi direkomendasikan)
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Akses format tanggal secara otomatis
     * (boleh kamu aktifkan supaya tidak perlu format() di Blade)
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at
            ? Carbon::parse($this->created_at)->translatedFormat('d F Y, H:i')
            : null;
    }

    /**
     * Jika kamu mau selalu menampilkan tanggal lokal Indonesia
     */
    public function getCreatedAtIndoAttribute()
    {
        Carbon::setLocale('id');
        return $this->created_at
            ? Carbon::parse($this->created_at)->translatedFormat('d F Y')
            : '-';
    }
}
