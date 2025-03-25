<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, HasUuids;

    protected $table = 'users';

    protected $fillable = [
        'firstname',
        'username',
        'tg_id',
    ];

    protected $hidden = [
        'remember_token',
    ];

}
