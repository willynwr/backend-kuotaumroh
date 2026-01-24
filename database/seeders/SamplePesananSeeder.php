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
        $agentId = 3; // Fixed agent_id
        $produkId = 1; // Fixed produk_id
        
        // Ambil data produk dari database
        $produk = \App\Models\Produk::find($produkId);
        
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
