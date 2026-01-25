<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogUmrohXlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('catalog_umroh_xl')->insert([
            ['id' => 'R4-XL-001', 'type' => 'XL', 'sub_type' => 'INTERNET', 'name' => 'Internet 10 Hari', 'days' => 10, 'quota' => '5 GB', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 221065, 'price_app' => 0, 'price_customer' => 280000, 'price_bulk' => 252000, 'price_self' => 260000, 'fee_affiliate' => 8400, 'is_active' => 1, 'product_id' => 'pre29424509', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-XL-002', 'type' => 'XL', 'sub_type' => 'INTERNET', 'name' => 'Internet 15 Hari', 'days' => 15, 'quota' => '8 GB', 'telp' => null, 'sms' => null, 'bonus' => '2 GB Transit', 'price_modal' => 318000, 'price_app' => 0, 'price_customer' => 378000, 'price_bulk' => 340200, 'price_self' => 358000, 'fee_affiliate' => 11340, 'is_active' => 1, 'product_id' => 'pre29424511', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-XL-003', 'type' => 'XL', 'sub_type' => 'INTERNET', 'name' => 'Internet 20 Hari', 'days' => 20, 'quota' => '8 GB', 'telp' => null, 'sms' => null, 'bonus' => '2 GB Transit', 'price_modal' => 398000, 'price_app' => 0, 'price_customer' => 458000, 'price_bulk' => 412200, 'price_self' => 438000, 'fee_affiliate' => 13740, 'is_active' => 1, 'product_id' => 'pre29424512', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-XL-004', 'type' => 'XL', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 2GB', 'days' => 45, 'quota' => '1 GB', 'telp' => null, 'sms' => null, 'bonus' => '1 GB Indo', 'price_modal' => 95933, 'price_app' => 0, 'price_customer' => 156000, 'price_bulk' => 140400, 'price_self' => 136000, 'fee_affiliate' => 4680, 'is_active' => 1, 'product_id' => 'pre29424505', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-XL-005', 'type' => 'XL', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 4GB', 'days' => 45, 'quota' => '2 GB', 'telp' => null, 'sms' => null, 'bonus' => '2 GB Indo', 'price_modal' => 182759, 'price_app' => 0, 'price_customer' => 243000, 'price_bulk' => 218700, 'price_self' => 223000, 'fee_affiliate' => 7290, 'is_active' => 1, 'product_id' => 'pre29424506', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-XL-006', 'type' => 'XL', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 10GB', 'days' => 45, 'quota' => '5 GB', 'telp' => null, 'sms' => null, 'bonus' => '5 GB Indo', 'price_modal' => 453209, 'price_app' => 0, 'price_customer' => 520000, 'price_bulk' => 468000, 'price_self' => 500000, 'fee_affiliate' => 15600, 'is_active' => 1, 'product_id' => 'pre29424507', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R4-XL-007', 'type' => 'XL', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 50GB', 'days' => 45, 'quota' => '25 GB', 'telp' => null, 'sms' => null, 'bonus' => '25 GB Indo', 'price_modal' => 968575, 'price_app' => 0, 'price_customer' => 1030000, 'price_bulk' => 927000, 'price_self' => 1010000, 'fee_affiliate' => 30900, 'is_active' => 1, 'product_id' => 'pre29424508', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
        ]);
    }
}
