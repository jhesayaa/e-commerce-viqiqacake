<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Actions;
use App\Models\Produk;
use Filament\Resources\Pages\CreateRecord;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function afterCreate(): void 
    {
        $pesanan = $this->record;
        $items = $this->data['items'];

        foreach ($items as $item) {
            $pesanan->items()->create([
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga' => $item['harga'],
                'subtotal' => $item['subtotal'],
            ]);

            // Kurangi stok langsung di sini
            $produk = Produk::find($item['produk_id']);
            if ($produk) {
                $produk->decrement('stok', $item['jumlah']);
            }
        }
    }
}
