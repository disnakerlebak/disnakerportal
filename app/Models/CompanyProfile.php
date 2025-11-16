<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'logo',
        'jenis_usaha',
        'alamat_lengkap',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'telepon',
        'email',
        'website',
        'social_facebook',
        'social_instagram',
        'social_linkedin',
        'social_twitter',
        'deskripsi',
        'jumlah_karyawan',
        'nib',
        'npwp',
        'verification_status',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'company_id');
    }
}
