<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'minat_lokasi',
        'minat_bidang',
        'gaji_harapan',
        'deskripsi_diri',
    ];

    protected $casts = [
        'minat_lokasi' => 'array',
        'minat_bidang' => 'array',
    ];
}
