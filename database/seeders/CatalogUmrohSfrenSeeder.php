<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogUmrohSfrenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('catalog_umroh_sfren')->insert([
            ['id' => 'R7-SFR-001', 'type' => 'SMARTFREN', 'sub_type' => 'INTERNET', 'name' => 'Internet 7 Hari', 'days' => 7, 'quota' => '2 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 98171, 'price_app' => 0, 'price_customer' => 158000, 'price_bulk' => 142200, 'price_self' => 138000, 'fee_affiliate' => 4740, 'is_active' => 1, 'product_id' => 'pre29424495', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R7-SFR-002', 'type' => 'SMARTFREN', 'sub_type' => 'INTERNET', 'name' => 'Internet 14 Hari', 'days' => 14, 'quota' => '12 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 272655, 'price_app' => 0, 'price_customer' => 332000, 'price_bulk' => 298800, 'price_self' => 312000, 'fee_affiliate' => 9960, 'is_active' => 1, 'product_id' => 'pre29424496', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
        ]);
    }
}
