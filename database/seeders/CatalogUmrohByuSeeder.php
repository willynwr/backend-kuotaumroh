<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogUmrohByuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('CATALOG_UMROH_BYU')->insert([
            ['id' => 'R2-BYU-001', 'type' => 'BYU', 'sub_type' => 'INTERNET', 'name' => 'Internet 12 Hari', 'days' => 12, 'quota' => '5 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 272250, 'price_app' => 275000, 'price_customer' => 330000, 'price_bulk' => 297000, 'price_self' => 320100, 'fee_affiliate' => 9900, 'is_active' => 1, 'product_id' => '51533', 'menu_id' => '51533', 'source_digipos' => 0, 'source_name' => 'TSEL', 'promo' => null],
            ['id' => 'R2-BYU-002', 'type' => 'BYU', 'sub_type' => 'INTERNET', 'name' => 'Internet 17 Hari', 'days' => 17, 'quota' => '10 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 371250, 'price_app' => 375000, 'price_customer' => 450000, 'price_bulk' => 410000, 'price_self' => 438375, 'fee_affiliate' => 11625, 'is_active' => 1, 'product_id' => '51534', 'menu_id' => '51534', 'source_digipos' => 0, 'source_name' => 'TSEL', 'promo' => null],
            ['id' => 'R2-BYU-003', 'type' => 'BYU', 'sub_type' => 'INTERNET', 'name' => 'Internet 20 Hari', 'days' => 20, 'quota' => '15 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 495000, 'price_app' => 500000, 'price_customer' => 600000, 'price_bulk' => 552000, 'price_self' => 585750, 'fee_affiliate' => 14250, 'is_active' => 1, 'product_id' => '52312', 'menu_id' => '52312', 'source_digipos' => 0, 'source_name' => 'TSEL', 'promo' => null],
            ['id' => 'R2-BYU-004', 'type' => 'BYU', 'sub_type' => 'INTERNET', 'name' => 'Internet 30 Hari', 'days' => 30, 'quota' => '23 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 712800, 'price_app' => 720000, 'price_customer' => 792000, 'price_bulk' => 737000, 'price_self' => 775060, 'fee_affiliate' => 16940, 'is_active' => 1, 'product_id' => '52311', 'menu_id' => '52311', 'source_digipos' => 0, 'source_name' => 'TSEL', 'promo' => null],
            ['id' => 'R2-BYU-005', 'type' => 'BYU', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari', 'days' => 45, 'quota' => '30 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 871200, 'price_app' => 880000, 'price_customer' => 968000, 'price_bulk' => 901000, 'price_self' => 950120, 'fee_affiliate' => 17880, 'is_active' => 1, 'product_id' => '52310', 'menu_id' => '52310', 'source_digipos' => 0, 'source_name' => 'TSEL', 'promo' => null],
        ]);
    }
}
