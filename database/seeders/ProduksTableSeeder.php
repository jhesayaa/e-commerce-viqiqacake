<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProduksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('produks')->delete();
        
        \DB::table('produks')->insert(array (
            0 => 
            array (
                'id' => 4,
                'nama' => 'Pisang Bolen 10 pcs',
                'harga' => '35000.00',
                'stok' => 0,
                'deskripsi' => 'Coklat, Keju, Tape, Nanas,Ubi',
                'gambar' => 'produk/rotibolen-viqiqacake.jpg',
                'aktif' => 1,
                'created_at' => '2025-02-13 16:13:52',
                'updated_at' => '2025-02-17 07:56:05',
                'kategori_id' => 1,
                'deleted_at' => NULL,
                'favorit' => 1,
            ),
            1 => 
            array (
                'id' => 5,
                'nama' => 'Brownies /pcs',
                'harga' => '3000.00',
                'stok' => 90,
                'deskripsi' => 'Brownies Viqiqa Cake',
                'gambar' => 'produk/brownies.png',
                'aktif' => 1,
                'created_at' => '2025-02-15 19:46:18',
                'updated_at' => '2025-02-15 20:03:05',
                'kategori_id' => 3,
                'deleted_at' => NULL,
                'favorit' => 0,
            ),
        ));
        
        
    }
}