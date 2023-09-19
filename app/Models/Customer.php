<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "md_customer";
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'adress',
        'mobile_no',
        'password',
        'role',
        'otp',
        'otp_status',
        "role",
        "otp",
        "otp_status",
        "user_type",
        "deleted_flag",
        "deleted_by",
        "deleted_at",
        "created_by",
        "update_by"
    ];



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
    ];
}
