<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderNumbers extends Model
{
    protected $table = 'order_numbers';
    protected $fillable = [
        'order_number',
    ];
}
