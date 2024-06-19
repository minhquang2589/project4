<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    protected $table = 'discounts';
    protected $fillable = [
        'discount',
        'quantity',
        'remaining',
        'status',
        'start_datetime',
        'end_datetime',
    ];
}
