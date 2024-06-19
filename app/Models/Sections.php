<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $table = 'section';
    protected $fillable = [
        'name',
        'image',
        'status',
        'description',
        'link_url',
    ];
}
