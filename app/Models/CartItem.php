<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'produk_id',
        'quantity',
        'price',
        'subtotal'
    ];

    // Relasi dengan Cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relasi dengan Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}