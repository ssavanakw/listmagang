<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class InternshipRegistration extends Model
{
    use HasFactory;

    protected $table = 'internship_registrations';

    // ===== Workflow status (dipakai filter/menu) =====
    public const STATUS_NEW       = 'new';
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_EXITED    = 'exited';
    public const STATUS_PENDING   = 'pending';

    protected $fillable = [
        'fullname', 'born_date', 'student_id', 'email', 'gender', 'phone_number',
        'institution_name', 'study_program', 'faculty', 'current_city',
        'internship_reason', 'internship_type', 'internship_arrangement',
        'current_status',
        'english_book_ability', 'supervisor_contact',
        'internship_interest', 'internship_interest_other',
        'design_software', 'video_software', 'programming_languages',
        'digital_marketing_type', 'digital_marketing_type_other',
        'laptop_equipment', 'owned_tools', 'owned_tools_other',
        'start_date', 'end_date',
        'internship_info_sources', 'internship_info_other',
        'cv_ktp_portofolio_pdf', 'portofolio_visual',
        'current_activities', 'boarding_info', 'family_status',
        'parent_wa_contact', 'social_media_instagram',
        'internship_status',
    ];

    /**
     * Catatan casts:
     * - JANGAN cast born_date/start_date/end_date sebagai 'date' karena input bisa beragam format.
     * - Simpan created_at/updated_at saja sebagai datetime.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* ============================================================
     |  Coercion tanggal yang aman (tidak melempar exception)
     * ============================================================ */
    private function tryToCarbon(mixed $value): ?Carbon
    {
        if ($value === null || $value === '') return null;

        $value = is_string($value) ? trim($value) : $value;

        // Coba beberapa format yang umum dipakai form
        $formats = ['Y-m-d','d-m-Y','d/m/Y','d M Y','d.m.Y','d m Y','m/d/Y','m-d-Y'];
        foreach ($formats as $f) {
            try {
                return Carbon::createFromFormat($f, (string) $value);
            } catch (\Throwable $e) {
                // lanjut
            }
        }
        // Terakhir, biarkan Carbon menebak
        try {
            return Carbon::parse((string) $value);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /** born_date accessor/mutator aman */
    protected function bornDate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $c = $this->tryToCarbon($value);
                // Kembalikan Carbon bila sukses, atau string asli bila gagal
                return $c ?: $value;
            },
            set: function ($value) {
                $c = $this->tryToCarbon($value);
                return $c ? $c->format('Y-m-d') : $value;
            }
        );
    }

    /** start_date accessor/mutator aman */
    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $c = $this->tryToCarbon($value);
                return $c ?: $value;
            },
            set: function ($value) {
                $c = $this->tryToCarbon($value);
                return $c ? $c->format('Y-m-d') : $value;
            }
        );
    }

    /** end_date accessor/mutator aman */
    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $c = $this->tryToCarbon($value);
                return $c ?: $value;
            },
            set: function ($value) {
                $c = $this->tryToCarbon($value);
                return $c ? $c->format('Y-m-d') : $value;
            }
        );
    }

    /* ============================================================
     |  Scopes (query helper)
     * ============================================================ */
    public function scopeStatus($query, string $status)
    {
        return $query->where('internship_status', $status);
    }

    // Hindari nama "new" (reserved-ish). Pakai isNew.
    public function scopeIsNew($query)     { return $query->status(self::STATUS_NEW); }
    public function scopeActive($query)    { return $query->status(self::STATUS_ACTIVE); }
    public function scopeCompleted($query) { return $query->status(self::STATUS_COMPLETED); }
    public function scopeExited($query)    { return $query->status(self::STATUS_EXITED); }
    public function scopePending($query)   { return $query->status(self::STATUS_PENDING); }

    /* ============================================================
     |  Helpers untuk UI (label & badge)
     * ============================================================ */
    public function getStatusLabelAttribute(): string
    {
        return [
            self::STATUS_NEW       => 'Pendaftar Baru',
            self::STATUS_ACTIVE    => 'Aktif',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_EXITED    => 'Keluar',
            self::STATUS_PENDING   => 'Pending',
        ][$this->internship_status] ?? ucfirst((string) $this->internship_status);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return [
            self::STATUS_NEW       => 'bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-200',
            self::STATUS_ACTIVE    => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200',
            self::STATUS_COMPLETED => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200',
            self::STATUS_EXITED    => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-200',
            self::STATUS_PENDING   => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
        ][$this->internship_status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    }

    /* ============================================================
     |  Optional: list helper untuk kolom CSV
     * ============================================================ */
    public function getOwnedToolsListAttribute(): array
    {
        $raw = (string) ($this->attributes['owned_tools'] ?? '');
        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    public function getInfoSourcesListAttribute(): array
    {
        $raw = (string) ($this->attributes['internship_info_sources'] ?? '');
        return array_values(array_filter(array_map('trim', explode(',', $raw))));
    }

    /* ============================================================
     |  Ringkasan cepat untuk dashboard
     * ============================================================ */
    public static function countsByStatus(): array
    {
        return [
            'new'       => static::status(self::STATUS_NEW)->count(),
            'active'    => static::status(self::STATUS_ACTIVE)->count(),
            'completed' => static::status(self::STATUS_COMPLETED)->count(),
            'exited'    => static::status(self::STATUS_EXITED)->count(),
            'pending'   => static::status(self::STATUS_PENDING)->count(),
        ];
    }
}
