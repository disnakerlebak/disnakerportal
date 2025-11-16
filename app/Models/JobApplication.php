<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_posting_id',
        'user_id',
        'company_id',
        'tanggal_lamaran',
        'status',
        'catatan_perusahaan',
    ];

    protected $casts = [
        'tanggal_lamaran' => 'date',
    ];

    public function posting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }
}

