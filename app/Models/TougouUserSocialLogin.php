<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TougouUserSocialLogin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        // 'email',
        // 'email_verified_at',
        'line_id',
        'access_tocken',
        'refresh_token',
        'remember_token',
        'socialplus_id'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(TougouUser::class, 'user_id');
    }
}
