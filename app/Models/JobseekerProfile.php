<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobseekerProfile extends Model
{
    protected $fillable = [
        'user_id','nama_lengkap','nik','tempat_lahir','tanggal_lahir','jenis_kelamin',
        'agama','status_perkawinan','pendidikan_terakhir','alamat_lengkap',
        'domisili_kecamatan','no_telepon','status_disabilitas','akun_media_sosial','email_cache',
    ];

    public function user() { return $this->belongsTo(User::class); }
    
    public function educations()      { return $this->hasMany(\App\Models\Education::class, 'jobseeker_profile_id', 'id'); }
    public function trainings()       { return $this->hasMany(\App\Models\Training::class, 'jobseeker_profile_id', 'id'); }
    public function workExperiences() { return $this->hasMany(\App\Models\WorkExperience::class, 'jobseeker_profile_id', 'id'); }
    public function getUsiaAttribute()
    {
    return $this->tanggal_lahir ? \Carbon\Carbon::parse($this->tanggal_lahir)->age : null;
}

}
