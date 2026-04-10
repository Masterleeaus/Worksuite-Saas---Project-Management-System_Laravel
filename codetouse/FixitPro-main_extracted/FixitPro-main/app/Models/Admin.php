<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasPermissionsTrait;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissionsTrait, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'designation',
        'profile_image',
        'address',
        'latitude',
        'longitude',
        'city',
        'province',
        'country',
        'postal_code',
        'bio',
        'active_status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
