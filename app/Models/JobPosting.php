<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'aktif';
    public const STATUS_CLOSED = 'tutup';

    protected $fillable = [
        'company_id',
        'judul',
        'posisi',
        'deskripsi',
        'kualifikasi',
        'pendidikan_minimal',
        'jenis_kelamin',
        'usia_min',
        'usia_max',
        'gaji_min',
        'gaji_max',
        'lokasi_kerja',
        'menerima_disabilitas',
        'tanggal_posting',
        'tanggal_expired',
        'status',
    ];

    protected $casts = [
        'menerima_disabilitas' => 'boolean',
        'tanggal_posting' => 'date',
        'tanggal_expired' => 'date',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_ACTIVE,
            self::STATUS_CLOSED,
        ];
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
