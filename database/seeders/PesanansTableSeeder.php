<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PesanansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('pesanans')->delete();
        
        \DB::table('pesanans')->insert(array (
            0 => 
            array (
                'id' => 1,
                'kode_pesanan' => 'INV-20250215-0001',
                'nama_pelanggan' => 'Jeje',
                'email' => 'jeje@gmail.com',
                'telepon' => '000000000000',
                'alamat' => 'Jl Tlogosari',
                'quantity' => 1,
                'total_harga' => '380000.00',
                'total_akhir' => '380000.00',
                'status' => 'selesai',
                'metode_pembayaran' => 'transfer',
                'catatan' => 'Jeje',
                'created_at' => '2025-02-15 20:03:05',
                'updated_at' => '2025-02-15 20:03:05',
                'voucher_id' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}