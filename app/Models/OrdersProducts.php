<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersProducts extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price', 'total_price'];

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
