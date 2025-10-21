<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PesananItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pesanan_items')->delete();
        
        \DB::table('pesanan_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'pesanan_id' => 1,
                'produk_id' => 4,
                'jumlah' => 10,
                'harga' => '35000.00',
                'subtotal' => '350000.00',
                'created_at' => '2025-02-15 20:03:05',
                'updated_at' => '2025-02-15 20:03:05',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'pesanan_id' => 1,
                'produk_id' => 5,
                'jumlah' => 10,
                'harga' => '3000.00',
                'subtotal' => '30000.00',
                'created_at' => '2025-02-15 20:03:05',
                'updated_at' => '2025-02-15 20:03:05',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'pesanan_id' => 1,
                'produk_id' => 4,
                'jumlah' => 10,
                'harga' => '35000.00',
                'subtotal' => '350000.00',
                'created_at' => '2025-02-15 20:03:05',
                'updated_at' => '2025-02-15 20:03:05',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'pesanan_id' => 1,
                'produk_id' => 5,
                'jumlah' => 10,
                'harga' => '3000.00',
                'subtotal' => '30000.00',
                'created_at' => '2025-02-15 20:03:05',
                'updated_at' => '2025-02-15 20:03:05',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}