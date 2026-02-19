<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'kelas',
    'child_id_code',
    'otp',              // Add this
    'otp_expires_at',
    'user_code',   // Add this
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
   protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime', // <--- TAMBAHKAN BARIS INI
    ];

    /**
     * Relasi untuk Orang Tua mengambil data Anaknya
     */
    public function child()
    {
        // Mencocokkan 'child_id_code' milik Ortu dengan 'user_code' milik Siswa
        return $this->belongsTo(User::class, 'child_id_code', 'user_code');
    }
}