<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        $agents = [
            ['email' => 'kuotaumroh@gmail.com', 'nama_pic' => 'Kuota Umroh', 'no_hp' => '081234567901', 'nama_travel' => 'Kuota Umroh', 'affiliate_id' => 1, 'jenis_travel' => 'UMROH LEISURE', 'total_traveller' => 0, 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat', 'alamat_lengkap' => 'Jl. Gatot Subroto No. 2', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'kuotaumroh', 'status' => 'active'],
            ['email' => 'pukishjai@gmail.com', 'nama_pic' => 'Agent Faiz', 'no_hp' => '081234568761', 'nama_travel' => 'Travel Faiz Bersama', 'affiliate_id' => 1, 'jenis_travel' => 'Travel haji', 'total_traveller' => 0, 'provinsi' => 'Riau', 'kabupaten_kota' => 'Pekanbaru', 'alamat_lengkap' => 'Jl. Diponegoro No. 101', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'alfa-agent', 'status' => 'active'],    
        ];
        DB::table('agents')->insert($agents);
    }
}