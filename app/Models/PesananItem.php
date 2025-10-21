<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PesananItem extends Model
{

    
    use SoftDeletes;

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'jumlah',
        'harga',
        'subtotal',
    ];
 
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
 
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
