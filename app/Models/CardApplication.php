<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardApplication extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi secara mass-assignment.
     */
    protected $fillable = [
        'user_id',
        'status',
        'type',
        'parent_id',
        'nomor_ak1',
        'tanggal_pengajuan',
        'is_active',
        'snapshot_before',
        'snapshot_after',
    ];

    /**
     * Cast otomatis untuk kolom waktu.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'snapshot_before' => 'array',
        'snapshot_after' => 'array',
        'is_active' => 'boolean',
        'archived_at' => 'datetime',
    ];

    /* =========================================================
     |                      RELASI DATA
     ========================================================= */

    /**
     * Relasi ke pengguna (pencaker).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke dokumen yang diunggah (tabel card_application_documents).
     */
    public function documents()
    {
        return $this->hasMany(CardApplicationDocument::class, 'card_application_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Relasi ke log aktivitas (tabel card_application_logs).
     */
    public function logs()
    {
        return $this->hasMany(CardApplicationLog::class, 'card_application_id')->latest();
    }

    /**
     * Relasi ke handler (admin) terakhir yang memproses pengajuan ini.
     */
    public function lastHandler()
    {
        return $this->hasOne(CardApplicationLog::class, 'card_application_id')->latestOfMany();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isRepair(): bool
    {
        return $this->type === 'perbaikan';
    }

    public function isRenewal(): bool
    {
        return $this->type === 'perpanjangan';
    }
}
