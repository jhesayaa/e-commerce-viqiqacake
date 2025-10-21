<?php

namespace App\Observers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Log;

class PesananObserver
{
    /**
     * Handle the Pesanan "created" event.
     */
    public function creating(Pesanan $pesanan)
    {
        Log::info('Creating Pesanan: ' . json_encode($pesanan));
    }

    public function created(Pesanan $pesanan)
    {
        Log::info('Pesanan created with items: ' . json_encode($pesanan->items));

        foreach ($pesanan->items as $item) {
            Log::info('Processing item: ' . json_encode($item));
            
            $produk = $item->produk;
            if ($produk) {
                $stokAwal = $produk->stok;
                $produk->decrement('stok', $item->jumlah);
                Log::info("Stok produk {$produk->nama} berkurang dari {$stokAwal} menjadi {$produk->stok}");
            }
        }
    }

    /**
     * Handle the Pesanan "updated" event.
     */
    public function updated(Pesanan $pesanan): void
    {
        if ($pesanan->isDirty('status') && $pesanan->status === 'dibatalkan') {
            foreach ($pesanan->items as $item) {
                $produk = $item->produk;
                $produk->increment('stok', $item->jumlah);
            }
        }
    }

    /**
     * Handle the Pesanan "deleted" event.
     */
    public function deleted(Pesanan $pesanan): void
    {
        //
    }

    /**
     * Handle the Pesanan "restored" event.
     */
    public function restored(Pesanan $pesanan): void
    {
        //
    }

    /**
     * Handle the Pesanan "force deleted" event.
     */
    public function forceDeleted(Pesanan $pesanan): void
    {
        //
    }
}
