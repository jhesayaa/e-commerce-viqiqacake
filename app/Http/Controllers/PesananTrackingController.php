<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananTrackingController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::where('email', auth()->user()->email)
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('pesanan.tracking', compact('pesanan'));
    }

    public function show(Pesanan $pesanan)
    {
        // Memastikan user hanya bisa melihat pesanannya sendiri
        if ($pesanan->email !== auth()->user()->email) {
            abort(403);
        }
        
        // Load relasi voucher jika ada
        if ($pesanan->voucher_id) {
            $pesanan->load('voucher');
        }
        
        return view('pesanan.detail', compact('pesanan'));
    }

    public function cancelPesanan(Pesanan $pesanan, Request $request)
    {
        // Validasi bahwa pesanan milik user ini
        if ($pesanan->email !== auth()->user()->email) {
            abort(403);
        }
        
        // Hanya bisa membatalkan pesanan dengan status pending atau diproses
        if ($pesanan->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan dengan status "Pending" atau "Diproses" yang dapat dibatalkan');
        }
        
        // Update status pesanan menjadi dibatalkan
        $pesanan->update([
            'status' => 'dibatalkan'
        ]);
        
        // Kembalikan stok
        foreach ($pesanan->items as $item) {
            $item->produk->increment('stok', $item->jumlah);
        }
        
        return redirect()->route('pesanan.tracking')
            ->with('success', 'Pesanan berhasil dibatalkan');
    }
}