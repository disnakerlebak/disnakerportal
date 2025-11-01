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
        'nomor_ak1',
    ];

    /**
     * Cast otomatis untuk kolom waktu.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
}
