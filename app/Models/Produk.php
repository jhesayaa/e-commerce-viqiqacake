<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{

    use SoftDeletes;
    
    protected $table = 'produks';

    protected $fillable = [ 
        'nama',
        'harga',
        'stok',
        'deskripsi',  
        'gambar',
        'aktif',
        'kategori_id',
        'favorit'
    ];

    protected $casts = [
        'aktif' => 'boolean'
    ];

    public function pesananItems()
    {
        return $this->hasMany(PesananItem::class);
    }

    public function pesanan()
    {
        return $this->belongsToMany(Pesanan::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
