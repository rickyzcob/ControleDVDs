<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ['title', 'gender', 'available', 'price'];

    public function stock()
    {
        return $this->hasOne(StockProduct::class, 'product_id');
    }
}
