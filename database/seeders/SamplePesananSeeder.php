<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Agent;
use App\Models\Produk;
use Carbon\Carbon;

class SamplePesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agentId = 3; // Fixed agent_id
        $produkId = 1; // Fixed produk_id
        $now = now();
        
        // Pastikan agent dengan ID 3 tersedia (buat jika belum ada)
        $agent = Agent::find($agentId);
        if (!$agent) {
            $this->command->warn('Agent ID ' . $agentId . ' belum ada, membuat sample agent...');
            DB::table('agents')->updateOrInsert(
                ['id' => $agentId],
                [
                    'jenis_agent' => 'agent',
                    'email' => 'agent'.$agentId.'@example.com',
                    'nama_pic' => 'Agent Sample '.$agentId,
                    'no_hp' => '081234567890',
                    'nama_travel' => 'Travel Sample '.$agentId,
                    'jenis_travel' => 'umroh',
                    'total_traveller' => 10,
                    'provinsi' => 'DKI Jakarta',
                    'kabupaten_kota' => 'Jakarta Selatan',
                    'alamat_lengkap' => 'Jl. Sudirman No. 123',
                    'link_gmaps' => null,
                    'long' => null,
                    'lat' => null,
                    'link_referal' => 'REF'.$agentId,
                    'rekening_agent' => null,
                    'date_approve' => $now,
                    'logo' => null,
                    'surat_ppiu' => null,
                    'saldo' => 0,
                    'saldo_bulan' => 0,
                    'saldo_tahun' => 0,
                    'status' => 'active',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $agent = Agent::find($agentId);
            $this->command->info('Sample agent dibuat dengan ID ' . $agentId);
        }
        
        // Ambil data produk dari database
        $produk = Produk::find($produkId);
        if (!$produk) {
            $this->command->warn('Produk ID ' . $produkId . ' belum ada, membuat sample produk...');
            DB::table('produk')->updateOrInsert(
                ['id' => $produkId],
                [
                    'nama_paket' => 'Paket Umroh Sample',
                    'tipe_paket' => 'data',
                    'provider' => 'Tokodigi',
                    'masa_aktif' => 30,
                    'total_kuota' => 1024000,
                    'kuota_utama' => 1024000,
                    'kuota_bonus' => 0,
                    'telp' => 0,
                    'sms' => 0,
                    'harga_modal' => 150000,
                    'harga_eup' => 0,
                    'persentase_margin_star' => 0,
                    'margin_star' => 0,
                    'margin_total' => 50000,
                    'fee_travel' => 10000,
                    'persentase_fee_travel' => 5,
                    'persentase_fee_affiliate' => 2,
                    'fee_affiliate' => 5000,
                    'persentase_fee_host' => 1,
                    'fee_host' => 2500,
                    'harga_tp_travel' => 0,
                    'harga_tp_host' => 0,
                    'poin' => 0,
                    'profit' => 50000,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
            $produk = Produk::find($produkId);
            $this->command->info('Sample produk dibuat dengan ID ' . $produkId);
        }
        
        if (!$produk) {
            $this->command->error('Produk dengan ID ' . $produkId . ' tidak ditemukan!');
            return;
        }
        
        // Hapus data lama
        \App\Models\Pesanan::where('agent_id', $agentId)->delete();
        \App\Models\Pembayaran::where('agent_id', $agentId)->delete();
        
        $this->command->info('Data lama untuk agent ' . $agentId . ' telah dihapus.');
        
        // Buat 5 data pembayaran dan pesanan
        for ($i = 1; $i <= 5; $i++) {
            $batchId = 'BATCH' . now()->format('YmdHis') . $i;
            
            $hargaJual = 200000;
            $profit = 50000;
            $biayaPlatform = $hargaJual * 0.01; // 1% biaya platform
            $totalPembayaran = $hargaJual + $biayaPlatform;
            
            // Buat pembayaran
            $pembayaran = Pembayaran::create([
                'batch_id' => $batchId,
                'agent_id' => $agentId,
                'produk_id' => $produkId,
                'nama_batch' => 'Sample Batch ' . $i,
                'sub_total' => $hargaJual,
                'biaya_platform' => $biayaPlatform,
                'total_pembayaran' => $totalPembayaran,
                'profit' => $profit,
                'metode_pembayaran' => 'QRIS',
                'status_pembayaran' => 'berhasil',
                'created_at' => now()->subDays(rand(1, 15)),
            ]);
            
            // Buat 1 pesanan per pembayaran dengan data dari produk
            Pesanan::create([
                'batch_id' => $batchId,
                'agent_id' => $agentId,
                'produk_id' => $produkId,
                'nama_batch' => 'Sample Batch ' . $i,
                'msisdn' => '08123456' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_paket' => $produk->nama_paket,
                'tipe_paket' => $produk->tipe_paket,
                'masa_aktif' => $produk->masa_aktif,
                'total_kuota' => $produk->total_kuota,
                'kuota_utama' => $produk->kuota_utama,
                'kuota_bonus' => $produk->kuota_bonus,
                'telp' => $produk->telp,
                'sms' => $produk->sms,
                'harga_modal' => $produk->harga_modal,
                'harga_jual' => $hargaJual,
                'profit' => $profit,
                'jadwal_aktivasi' => now()->addDays(5),
                'status_aktivasi' => 'berhasil',
                'created_at' => $pembayaran->created_at,
            ]);
        }
        
        // Saldo agent akan otomatis bertambah via PembayaranObserver
        // karena pembayaran dibuat dengan status 'berhasil'
        
        $this->command->info('✅ Sample data created successfully!');
        $this->command->info('Total Pembayaran: 5');
        $this->command->info('Total Pesanan: 5');
        $this->command->info('Agent ID: 3');
        $this->command->info('Produk: ' . $produk->nama_paket . ' (ID: ' . $produk->id . ')');
    }
}
