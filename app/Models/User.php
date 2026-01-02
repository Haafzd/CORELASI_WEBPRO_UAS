<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // public $incrementing = false; // REMOVED: Using default auto-increment
    // protected $keyType = 'string';

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'email',
        'role',
        'photo_path',
        'account_status'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function teacher(): HasOne
    {
        return $this->hasOne(Teacher::class, 'user_id', 'id');
    }
    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }
}
