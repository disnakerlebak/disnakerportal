<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobseeker_profile_id',
        'jenis_pelatihan',
        'lembaga_pelatihan',
        'tahun',
        'sertifikat_file',
    ];

    public function profile()
    {
        return $this->belongsTo(JobseekerProfile::class, 'jobseeker_profile_id', 'id');
    }
}
