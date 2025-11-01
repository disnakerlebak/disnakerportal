<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    public const ROLE_SUPERADMIN = 'superadmin';
    public const ROLE_ADMIN_VERIFIKATOR = 'admin_verifikator';
    public const ROLE_ADMIN_LOKER = 'admin_loker';
    public const ROLE_ADMIN_STATISTIK = 'admin_statistik';
    public const ROLE_PENCAKER = 'pencaker';

    public const ROLES = [
        self::ROLE_SUPERADMIN,
        self::ROLE_ADMIN_VERIFIKATOR,
        self::ROLE_ADMIN_LOKER,
        self::ROLE_ADMIN_STATISTIK,
        self::ROLE_PENCAKER,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Scope: only active users
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
// Relasi profil pencaker
    public function jobseekerProfile()
{
    return $this->hasOne(JobseekerProfile::class, 'user_id', 'id');
}
// app/Models/User.php
public function jobPreference()
{
    return $this->hasOne(JobPreference::class);
}

public function cardApplications()
{
    return $this->hasMany(CardApplication::class);
}


}
