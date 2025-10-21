<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_amount'
    ];

    // Relasi dengan CartItem
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}