<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AffiliateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $affiliates = [
            [
                'nama' => 'Kuota Umroh',
                'email' => 'kuotahaji@gmail.com',
                'no_wa' => '081234567890',
                'provinsi' => 'Jawa Barat',
                'kab_kota' => 'Bandung',
                'alamat_lengkap' => 'Jl. Merdeka No. 123, Bandung, Jawa Barat 40111',
                'date_register' => Carbon::now()->format('Y-m-d'),
                'is_active' => true,
                'link_referral' => 'kuotaumroh-official',
                'ref_code' => 'KUOTAUMROH',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
      
        ];

        DB::table('affiliates')->insert($affiliates);
    }
}