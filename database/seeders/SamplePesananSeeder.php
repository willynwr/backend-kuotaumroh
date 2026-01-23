<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon;

class SamplePesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agentId = 1;
        
        // Hapus data lama untuk agent ini
        \App\Models\Pesanan::where('agent_id', $agentId)->delete();
        \App\Models\Pembayaran::where('agent_id', $agentId)->delete();
        
        $this->command->info('Data lama agent ' . $agentId . ' telah dihapus.');
        
        // Ambil produk untuk referensi data
        $produk = \App\Models\Produk::first();
        
        if (!$produk) {
            $this->command->error('Tidak ada produk di database! Jalankan seeder produk terlebih dahulu.');
            return;
        }
        
        // Buat 5 transaksi berhasil bulan ini
        for ($i = 1; $i <= 5; $i++) {
            $batchId = 'BATCH' . now()->format('YmdHis') . $i;
            
            // Hitung total untuk 3 pesanan
            $jumlahPesanan = 3;
            $hargaPerPesanan = $produk->harga_b2c_star ?? 200000;
            $profitPerPesanan = 50000; // Dummy profit
            
            $subTotal = $hargaPerPesanan * $jumlahPesanan;
            $biayaPlatform = $subTotal * 0.01; // 1% biaya platform
            $totalPembayaran = $subTotal + $biayaPlatform;
            $totalProfit = $profitPerPesanan * $jumlahPesanan;
            
            // Buat pembayaran
            $pembayaran = Pembayaran::create([
                'batch_id' => $batchId,
                'agent_id' => $agentId,
                'produk_id' => $produk->id,
                'nama_batch' => 'Test Batch ' . $i,
                'sub_total' => $subTotal,
                'biaya_platform' => $biayaPlatform,
                'total_pembayaran' => $totalPembayaran,
                'profit' => $totalProfit,
                'metode_pembayaran' => 'QRIS',
                'status_pembayaran' => 'berhasil',
                'created_at' => now()->subDays(rand(1, 20)),
            ]);
            
            // Buat 3 pesanan per batch
            for ($j = 1; $j <= 3; $j++) {
                Pesanan::create([
                    'batch_id' => $batchId,
                    'agent_id' => $agentId,
                    'produk_id' => $produk->id,
                    'nama_batch' => 'Test Batch ' . $i,
                    'msisdn' => '08123456' . str_pad($i . $j, 3, '0', STR_PAD_LEFT),
                    'nama_paket' => $produk->nama_paket ?? 'Paket Umroh Premium',
                    'tipe_paket' => $produk->kategori ?? 'umroh',
                    'masa_aktif' => $produk->masa_aktif ?? 30,
                    'total_kuota' => $produk->total_kuota ?? 10000000000,
                    'kuota_utama' => $produk->kuota_reguler ?? 8000000000,
                    'kuota_bonus' => $produk->kuota_bonus ?? 2000000000,
                    'telp' => $produk->unlimited_call ?? true,
                    'sms' => $produk->unlimited_sms ?? true,
                    'harga_modal' => $produk->harga_dasar ?? 150000,
                    'harga_jual' => $hargaPerPesanan,
                    'profit' => $profitPerPesanan, // Dummy profit untuk testing
                    'jadwal_aktivasi' => now()->addDays(5),
                    'status_aktivasi' => 'berhasil',
                    'created_at' => $pembayaran->created_at,
                ]);
            }
        }
        
        // Buat 2 transaksi dengan status proses (belum berhasil pembayaran)
        for ($i = 6; $i <= 7; $i++) {
            $batchId = 'BATCH' . now()->format('YmdHis') . $i;
            
            $hargaPerPesanan = $produk->harga_b2c_star ?? 130000;
            $profitPerPesanan = 30000; // Dummy profit
            
            $subTotal = $hargaPerPesanan;
            $biayaPlatform = $subTotal * 0.01;
            $totalPembayaran = $subTotal + $biayaPlatform;
            
            Pembayaran::create([
                'batch_id' => $batchId,
                'agent_id' => $agentId,
                'produk_id' => $produk->id,
                'nama_batch' => 'Test Batch Proses ' . $i,
                'sub_total' => $subTotal,
                'biaya_platform' => $biayaPlatform,
                'total_pembayaran' => $totalPembayaran,
                'profit' => $profitPerPesanan,
                'metode_pembayaran' => 'QRIS',
                'status_pembayaran' => 'proses',
                'created_at' => now()->subDays(1),
            ]);
            
            Pesanan::create([
                'batch_id' => $batchId,
                'agent_id' => $agentId,
                'produk_id' => $produk->id,
                'nama_batch' => 'Test Batch Proses ' . $i,
                'msisdn' => '08123456' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_paket' => $produk->nama_paket ?? 'Paket Umroh Standard',
                'tipe_paket' => $produk->kategori ?? 'umroh',
                'masa_aktif' => $produk->masa_aktif ?? 30,
                'total_kuota' => $produk->total_kuota ?? 5000000000,
                'kuota_utama' => $produk->kuota_reguler ?? 4000000000,
                'kuota_bonus' => $produk->kuota_bonus ?? 1000000000,
                'telp' => $produk->unlimited_call ?? true,
                'sms' => $produk->unlimited_sms ?? true,
                'harga_modal' => $produk->harga_dasar ?? 100000,
                'harga_jual' => $hargaPerPesanan,
                'profit' => $profitPerPesanan, // Dummy profit untuk testing
                'jadwal_aktivasi' => now()->addDays(3),
                'status_aktivasi' => 'proses',
                'created_at' => now()->subDays(1),
            ]);
        }
        
        // Update saldo agent dengan total profit bulan ini
        $agent = \App\Models\Agent::find($agentId);
        if ($agent) {
            // 5 batch x 3 pesanan x 50000 profit = 750000
            $totalProfitBulanIni = 5 * 3 * 50000;
            $agent->saldo = $totalProfitBulanIni;        // Saldo permanent (tidak reset)
            $agent->saldo_bulan = $totalProfitBulanIni;  // Saldo bulan ini (reset tiap bulan)
            $agent->saldo_tahun = $totalProfitBulanIni;  // Saldo tahun ini (reset tiap tahun)
            $agent->save();
            
            $this->command->info('✅ Saldo agent updated:');
            $this->command->info('   - saldo (permanent): Rp ' . number_format($totalProfitBulanIni, 0, ',', '.'));
            $this->command->info('   - saldo_bulan: Rp ' . number_format($totalProfitBulanIni, 0, ',', '.'));
            $this->command->info('   - saldo_tahun: Rp ' . number_format($totalProfitBulanIni, 0, ',', '.'));
        }
        
        $this->command->info('Sample data created successfully!');
        $this->command->info('Agent ID: ' . $agentId);
        $this->command->info('Produk ID: ' . $produk->id . ' - ' . $produk->nama_paket);
        $this->command->info('Total Pembayaran berhasil: 5 (15 pesanan)');
        $this->command->info('Total Pembayaran Proses: 2 (2 pesanan)');
        $this->command->info('Expected Monthly Profit: Rp 750,000 (5 batch x 3 pesanan x Rp 50,000)');
    }
}
