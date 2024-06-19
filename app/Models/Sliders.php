<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    protected $table = 'slider';
    protected $fillable = [
        'name',
        'image',
        'status',
        'link_url',
    ];
}
