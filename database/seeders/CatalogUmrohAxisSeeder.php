<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogUmrohAxisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('catalog_umroh_axis')->insert([
            ['id' => 'R5-AXIS-001', 'type' => 'AXIS', 'sub_type' => 'INTERNET', 'name' => 'Internet 10 Hari', 'days' => 10, 'quota' => 'Unlimited', 'telp' => null, 'sms' => null, 'bonus' => null, 'price_modal' => 189830, 'price_app' => 0, 'price_customer' => 249000, 'price_bulk' => 224100, 'price_self' => 229000, 'fee_affiliate' => 7470, 'is_active' => 1, 'product_id' => 'pre29424490', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => 'PROMO TERBAIK'],
            ['id' => 'R5-AXIS-002', 'type' => 'AXIS', 'sub_type' => 'INTERNET + TELP/SMS', 'name' => 'Combo 10 Hari', 'days' => 10, 'quota' => 'Unlimited', 'telp' => '50', 'sms' => '50', 'bonus' => null, 'price_modal' => 283506, 'price_app' => 0, 'price_customer' => 343000, 'price_bulk' => 308700, 'price_self' => 323000, 'fee_affiliate' => 10290, 'is_active' => 1, 'product_id' => 'pre29424484', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => 'PROMO TERBAIK'],
            ['id' => 'R5-AXIS-004', 'type' => 'AXIS', 'sub_type' => 'INTERNET + TELP/SMS', 'name' => 'Combo 20 Hari', 'days' => 20, 'quota' => 'Unlimited', 'telp' => '75', 'sms' => '75', 'bonus' => null, 'price_modal' => 377025, 'price_app' => 0, 'price_customer' => 437000, 'price_bulk' => 393300, 'price_self' => 417000, 'fee_affiliate' => 13110, 'is_active' => 1, 'product_id' => 'pre29424486', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => 'PROMO TERBAIK'],
            ['id' => 'R5-AXIS-006', 'type' => 'AXIS', 'sub_type' => 'INTERNET + TELP/SMS', 'name' => 'Combo 40 Hari', 'days' => 40, 'quota' => 'Unlimited', 'telp' => '100', 'sms' => '100', 'bonus' => null, 'price_modal' => 518725, 'price_app' => 0, 'price_customer' => 578000, 'price_bulk' => 520200, 'price_self' => 558000, 'fee_affiliate' => 17340, 'is_active' => 1, 'product_id' => 'pre29424487', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => 'PROMO TERBAIK'],
            ['id' => 'R5-AXIS-007', 'type' => 'AXIS', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 2GB', 'days' => 45, 'quota' => '1 GB', 'telp' => null, 'sms' => null, 'bonus' => '1 GB Indo', 'price_modal' => 95875, 'price_app' => 0, 'price_customer' => 155000, 'price_bulk' => 139500, 'price_self' => 135000, 'fee_affiliate' => 4650, 'is_active' => 1, 'product_id' => 'pre29424488', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R5-AXIS-008', 'type' => 'AXIS', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 4GB', 'days' => 45, 'quota' => '2 GB', 'telp' => null, 'sms' => null, 'bonus' => '2 GB Indo', 'price_modal' => 182725, 'price_app' => 0, 'price_customer' => 243000, 'price_bulk' => 218700, 'price_self' => 223000, 'fee_affiliate' => 7290, 'is_active' => 1, 'product_id' => 'pre29424489', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
            ['id' => 'R5-AXIS-009', 'type' => 'AXIS', 'sub_type' => 'INTERNET', 'name' => 'Internet 45 Hari 10GB', 'days' => 45, 'quota' => '5 GB', 'telp' => null, 'sms' => null, 'bonus' => '5 GB Indo', 'price_modal' => 452250, 'price_app' => 0, 'price_customer' => 512000, 'price_bulk' => 460800, 'price_self' => 492000, 'fee_affiliate' => 15360, 'is_active' => 1, 'product_id' => 'pre29424493', 'menu_id' => null, 'source_digipos' => 0, 'source_name' => 'DIGIFLAZZ', 'promo' => null],
        ]);
    }
}
