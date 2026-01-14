<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';
    use HasApiTokens, Notifiable;

    protected $table = 'users_exam';

    protected $fillable = [
       'student_code',
        'full_name',
        'email',
        'password',
        'role',
        'category',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
       'role' => 'string',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
