<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    protected $table = 'product_details';
    protected $fillable = [
        'product_id',
        'detail',
    ];
}
