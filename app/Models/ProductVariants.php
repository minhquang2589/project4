<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariants extends Model
{
    protected $table = 'product_variant';
    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'discount_id',
        'quantity',
        'sales_count',
    ];
    public function color()
    {
        return $this->belongsTo(Colors::class);
    }
    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function size()
    {
        return $this->belongsTo(Sizes::class);
    }
}
