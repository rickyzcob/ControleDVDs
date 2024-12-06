<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['client_id', 'total_price', 'status'];

    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(OrdersProducts::class, 'order_id');
    }
}
