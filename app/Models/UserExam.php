<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    use HasApiTokens, Notifiable;

    protected $table = 'UserExam';

    protected $fillable = [
        'student_code',
        'full_name',
        'password',
        'category',      // Hạng được gán (LPT, TM, TT, T4, ...)
        'role',          // 'admin' hoặc 'student'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
    ];
}
