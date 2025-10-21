<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenjualan extends Model
{
    // Model ini memakai tabel yang sama dengan Pesanan
    // Ini adalah teknik "view model" - membuat model baru
    // yang mengakses tabel yang sudah ada dengan cara berbeda
    protected $table = 'pesanans';
    
    // Menggunakan kolom id sebagai primary key seperti di tabel asli
    protected $primaryKey = 'id';
}