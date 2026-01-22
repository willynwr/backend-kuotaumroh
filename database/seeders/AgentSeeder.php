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
            ['email' => 'kuotaumroh@gmail.com', 'nama_pic' => 'Kuota Umroh', 'no_hp' => '081234567901', 'nama_travel' => 'Kuota Umroh', 'affiliate_id' => 1, 'jenis_travel' => 'UMROH LEISURE', 'total_traveller' => 0, 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat', 'alamat_lengkap' => 'Jl. Gatot Subroto No. 1', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'kuotaumroh', 'status' => 'active'],
            ];
        DB::table('agents')->insert($agents);
    }
}