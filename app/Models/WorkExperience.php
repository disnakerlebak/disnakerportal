<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobseeker_profile_id',
        'nama_perusahaan',
        'jabatan',
        'tahun_mulai',
        'tahun_selesai',
        'surat_pengalaman',
    ];

    public function profile()
    {
        return $this->belongsTo(JobseekerProfile::class, 'user_id');
    }
}
