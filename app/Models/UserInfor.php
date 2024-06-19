<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfor extends Model
{
    protected $table = 'users_infor';
    protected $fillable = [
        'user_id',
        'total_purchases',
        'sold_purchases',
        'uploaded_product',
    ];
}
