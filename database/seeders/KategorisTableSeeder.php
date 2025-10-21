<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KategorisTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('kategoris')->delete();
        
        \DB::table('kategoris')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama' => 'Pisang Bolen',
                'created_at' => '2025-02-13 16:07:22',
                'updated_at' => '2025-02-13 16:07:22',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'nama' => 'Roti Manis',
                'created_at' => '2025-02-13 16:07:28',
                'updated_at' => '2025-02-13 16:07:28',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'nama' => 'Kue Premium',
                'created_at' => '2025-02-13 16:07:36',
                'updated_at' => '2025-02-13 16:07:36',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'nama' => 'Kue Tradisional',
                'created_at' => '2025-02-13 16:07:45',
                'updated_at' => '2025-02-13 16:07:45',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}