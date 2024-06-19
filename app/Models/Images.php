<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'product_image';
    protected $fillable = [
        'product_id',
        'image',
    ];
}
