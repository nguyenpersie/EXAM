<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class UserExam extends Authenticatable
{
    protected $primaryKey = 'id';
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'users';

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
