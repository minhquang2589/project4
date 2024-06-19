<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follows extends Model
{
    protected $table = 'follows';
    protected $fillable = [
        'user_id',
        'follow_user_id',
    ];
}
