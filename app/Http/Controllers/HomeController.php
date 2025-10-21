<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homepage()
    {
        $produks = Produk::where('favorit', true)
                        ->where('aktif', true)
                        ->orderBy('created_at', 'desc')
                        ->get();
        return view('homepage', compact('produks'));
    }
}