<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'mgt_no',
        'password',
        'department',
        'furigana',
        'family_name',
        'first_name',
        'region',
        // 'email',
        // 'email2',
        'official_registration_date',
        // 'birthday',
        // 'sex',
        // 'postal_code',
        // 'current_address',
        // 'desired_subject',
        // 'preferred_working_style',
        // 'current_address_area',
        // 'desired_area',
        // 'content',
        // 'last_updated',
        'isadmin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
}
