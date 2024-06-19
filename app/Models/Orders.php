<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'buyer_user_id',
        'total_amount',
        'notes',
        'voucher_id',
        'total_discount',
        'payment_method_id',
        'status',
        'order_number_id',
        'order_date',
    ];
}
