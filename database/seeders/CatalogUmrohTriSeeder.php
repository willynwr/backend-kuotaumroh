<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogUmrohTriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('catalog_umroh_tri')->insert([
            ['id' => 'R4-TRI-001', 'type' => 'TRI', 'sub_type' => 'INTERNET', 'name' => 'Internet 15 Hari 2GB', 'days' => 15, 'quota' => '2 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 140065, 'price_app' => 0, 'price_customer' => 200000, 'price_bulk' => 180000, 'price_self' => 180000, 'fee_affiliate' => 6000, 'is_active' => 1, 'product_id' => 'pre29424497', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-TRI-002', 'type' => 'TRI', 'sub_type' => 'INTERNET', 'name' => 'Internet 15 Hari 15GB', 'days' => 15, 'quota' => '14 GB', 'telp' => null, 'sms' => null, 'bonus' => '1 GB Indo', 'price_modal' => 283925, 'price_app' => 0, 'price_customer' => 344000, 'price_bulk' => 309600, 'price_self' => 324000, 'fee_affiliate' => 10320, 'is_active' => 1, 'product_id' => 'pre29424501', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-TRI-003', 'type' => 'TRI', 'sub_type' => 'INTERNET', 'name' => 'Internet 20 Hari', 'days' => 20, 'quota' => '10 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 260000, 'price_app' => 0, 'price_customer' => 320000, 'price_bulk' => 288000, 'price_self' => 300000, 'fee_affiliate' => 9600, 'is_active' => 1, 'product_id' => 'pre29424499', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-TRI-004', 'type' => 'TRI', 'sub_type' => 'INTERNET', 'name' => 'Internet 30 Hari', 'days' => 30, 'quota' => '19 GB', 'telp' => null, 'sms' => null, 'bonus' => '1 GB Indo', 'price_modal' => 527025, 'price_app' => 0, 'price_customer' => 587000, 'price_bulk' => 528300, 'price_self' => 567000, 'fee_affiliate' => 17610, 'is_active' => 1, 'product_id' => 'pre29424503', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-TRI-005', 'type' => 'TRI', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 3GB', 'days' => 45, 'quota' => '3 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 243150, 'price_app' => 0, 'price_customer' => 303000, 'price_bulk' => 272700, 'price_self' => 283000, 'fee_affiliate' => 9090, 'is_active' => 1, 'product_id' => 'pre29424500', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-TRI-006', 'type' => 'TRI', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 25GB', 'days' => 45, 'quota' => '24 GB', 'telp' => null, 'sms' => null, 'bonus' => '1 GB Indo', 'price_modal' => 650000, 'price_app' => 0, 'price_customer' => 710000, 'price_bulk' => 639000, 'price_self' => 690000, 'fee_affiliate' => 21300, 'is_active' => 1, 'product_id' => 'pre29424504', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-TRI-007', 'type' => 'TRI', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari Unlimited', 'days' => 45, 'quota' => 'Unlimited', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 296025, 'price_app' => 0, 'price_customer' => 356000, 'price_bulk' => 320400, 'price_self' => 336000, 'fee_affiliate' => 10680, 'is_active' => 1, 'product_id' => 'pre29424502', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => 'PROMO TERBAIK'],
        ]);
    }
}
