<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Affiliate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first affiliate ID
        $affiliate = Affiliate::first();
        $affiliateId = $affiliate ? $affiliate->id : null;

        $agents = [
            ['email' => 'kuotaumroh@gmail.com', 'nama_pic' => 'Kuota Umroh', 'no_hp' => '081234567901', 'nama_travel' => 'Kuota Umroh', 'affiliate_id' => $affiliateId, 'jenis_travel' => 'UMROH LEISURE', 'total_traveller' => 0, 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat', 'alamat_lengkap' => 'Jl. Gatot Subroto No. 2', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'kuotaumroh', 'status' => 'active'],
            ['email' => 'pukishjai@gmail.com', 'nama_pic' => 'Agent Faiz', 'no_hp' => '081234568761', 'nama_travel' => 'Travel Faiz Bersama', 'affiliate_id' => $affiliateId, 'jenis_travel' => 'Travel haji', 'total_traveller' => 0, 'provinsi' => 'Riau', 'kabupaten_kota' => 'Pekanbaru', 'alamat_lengkap' => 'Jl. Diponegoro No. 101', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'alfa-agent', 'status' => 'active'],
        ];

        foreach ($agents as $data) {
            Agent::create($data);
        }
    }
}