<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori
        $kategoris = Kategori::all();
        
        // Query dasar produk yang aktif
        $query = Produk::where('aktif', true);

            // Filter berdasarkan search jika ada
        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan kategori jika ada
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }
        
        // Ambil produk dengan pagination
        $produks = $query->latest()->paginate(12);
        
        return view('produk', compact('produks', 'kategoris'));
    }
}
