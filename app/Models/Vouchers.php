<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vouchers extends Model
{
    protected $table = 'vouchers';
    protected $fillable = [
        'voucher_code',
        'discount_value',
        'usage_conditions',
        'start_date',
        'end_date',
        'quantity',
        'status',
    ];
}
