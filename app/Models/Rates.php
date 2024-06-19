<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rates extends Model
{
    protected $table = 'rate';
    protected $fillable = [
        'user_id',
        'rate_user_id',
    ];
}
