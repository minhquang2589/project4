<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cates extends Model
{
    protected $table = 'product_cate';
    protected $fillable = [
        'product_id',
        'cate',
    ];
}
