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
            ['email' => 'rima.agent@gmail.com', 'nama_pic' => 'Agent Rima', 'no_hp' => '081234567901', 'nama_travel' => 'Travel Rima Indonesia', 'jenis_travel' => 'Travel Umroh', 'total_traveller' => 0, 'provinsi' => 'DKI Jakarta', 'kabupaten_kota' => 'Jakarta Pusat', 'alamat_lengkap' => 'Jl. Gatot Subroto No. 1', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'rima-agent', 'status' => 'active'],
            ['email' => 'sandi.agent@gmail.com', 'nama_pic' => 'Agent Sandi', 'no_hp' => '081234567902', 'nama_travel' => 'Travel Sandi Jaya', 'jenis_travel' => 'Travel Haji', 'total_traveller' => 0, 'provinsi' => 'Jawa Timur', 'kabupaten_kota' => 'Surabaya', 'alamat_lengkap' => 'Jl. Ahmad Yani No. 45', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'sandi-agent', 'status' => 'active'],
            ['email' => 'tari.agent@gmail.com', 'nama_pic' => 'Agent Tari', 'no_hp' => '081234567903', 'nama_travel' => 'Travel Tari Express', 'jenis_travel' => 'Travel Umroh', 'total_traveller' => 0, 'provinsi' => 'Jawa Tengah', 'kabupaten_kota' => 'Solo', 'alamat_lengkap' => 'Jl. Slamet Riyadi No. 78', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'tari-agent', 'status' => 'active'],
            ['email' => 'udin.agent@gmail.com', 'nama_pic' => 'Agent Udin', 'no_hp' => '081234567904', 'nama_travel' => 'Travel Udin Paradise', 'jenis_travel' => 'Travel Umroh', 'total_traveller' => 0, 'provinsi' => 'Bali', 'kabupaten_kota' => 'Ubud', 'alamat_lengkap' => 'Jl. Raya Ubud No. 12', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'udin-agent', 'status' => 'active'],
            ['email' => 'vina.agent@gmail.com', 'nama_pic' => 'Agent Vina', 'no_hp' => '081234567905', 'nama_travel' => 'Travel Vina Tour', 'jenis_travel' => 'Travel Haji', 'total_traveller' => 0, 'provinsi' => 'Yogyakarta', 'kabupaten_kota' => 'Yogyakarta', 'alamat_lengkap' => 'Jl. Diponegoro No. 56', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'vina-agent', 'status' => 'active'],
            ['email' => 'wayan.agent@gmail.com', 'nama_pic' => 'Agent Wayan', 'no_hp' => '081234567906', 'nama_travel' => 'Travel Wayan Indonesia', 'jenis_travel' => 'Travel Umroh', 'total_traveller' => 0, 'provinsi' => 'Sumatera Utara', 'kabupaten_kota' => 'Medan', 'alamat_lengkap' => 'Jl. Brigjen Katamso No. 99', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'wayan-agent', 'status' => 'active'],
            ['email' => 'xena.agent@gmail.com', 'nama_pic' => 'Agent Xena', 'no_hp' => '081234567907', 'nama_travel' => 'Travel Xena Global', 'jenis_travel' => 'Travel Haji', 'total_traveller' => 0, 'provinsi' => 'Kalimantan Timur', 'kabupaten_kota' => 'Samarinda', 'alamat_lengkap' => 'Jl. Patimura No. 34', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'xena-agent', 'status' => 'active'],
            ['email' => 'yoga.agent@gmail.com', 'nama_pic' => 'Agent Yoga', 'no_hp' => '081234567908', 'nama_travel' => 'Travel Yoga Indah', 'jenis_travel' => 'Travel Umroh', 'total_traveller' => 0, 'provinsi' => 'Sulawesi Selatan', 'kabupaten_kota' => 'Makassar', 'alamat_lengkap' => 'Jl. Jenderal Sudirman No. 88', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'yoga-agent', 'status' => 'active'],
            ['email' => 'zara.agent@gmail.com', 'nama_pic' => 'Agent Zara', 'no_hp' => '081234567909', 'nama_travel' => 'Travel Zara Maju', 'jenis_travel' => 'Travel Haji', 'total_traveller' => 0, 'provinsi' => 'Lampung', 'kabupaten_kota' => 'Bandar Lampung', 'alamat_lengkap' => 'Jl. Raden Intan No. 22', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'zara-agent', 'status' => 'active'],
            ['email' => 'alfa.agent@gmail.com', 'nama_pic' => 'Agent Alfa', 'no_hp' => '081234567910', 'nama_travel' => 'Travel Alfa Bersama', 'jenis_travel' => 'Travel Umroh', 'total_traveller' => 0, 'provinsi' => 'Riau', 'kabupaten_kota' => 'Pekanbaru', 'alamat_lengkap' => 'Jl. Diponegoro No. 101', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'alfa-agent', 'status' => 'active'],
            ['email' => 'faizdaf123@gmail.com', 'nama_pic' => 'Agent Faiz', 'no_hp' => '081234568761', 'nama_travel' => 'Travel Faiz Bersama', 'jenis_travel' => 'Travel Umroh', 'total_traveller' => 0, 'provinsi' => 'Riau', 'kabupaten_kota' => 'Pekanbaru', 'alamat_lengkap' => 'Jl. Diponegoro No. 101', 'kategori_agent' => 'Host', 'is_active' => true, 'link_referal' => 'alfa-agent', 'status' => 'active'],
        ];
        DB::table('agents')->insert($agents);
    }
}