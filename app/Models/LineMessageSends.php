<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineMessageSends extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'message',
    ];
}
