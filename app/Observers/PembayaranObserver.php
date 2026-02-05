<?php

namespace App\Observers;

use App\Models\Pembayaran;
use App\Models\Agent;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Log;

class PembayaranObserver
{
    /**
     * Handle the Pembayaran "created" event.
     * Ketika pembayaran dibuat dengan status berhasil (misalnya dari seeder),
     * maka saldo agent otomatis bertambah
     */
    public function created(Pembayaran $pembayaran): void
    {
        // Cek apakah pembayaran dibuat dengan status berhasil
        if (in_array(strtolower($pembayaran->status_pembayaran), ['berhasil', 'success'])) {
            $this->incrementAgentSaldo($pembayaran);
        }
    }

    /**
     * Handle the Pembayaran "updated" event.
     * Ketika status pembayaran berubah menjadi berhasil,
     * maka saldo agent otomatis bertambah
     */
    public function updated(Pembayaran $pembayaran): void
    {
        // Cek apakah status_pembayaran berubah menjadi berhasil
        if ($pembayaran->isDirty('status_pembayaran')) {
            $newStatus = strtoupper($pembayaran->status_pembayaran);
            $oldStatus = strtoupper($pembayaran->getOriginal('status_pembayaran'));
            
            // Jika status baru adalah SUCCESS dan sebelumnya bukan SUCCESS
            if ($newStatus === 'SUCCESS' && $oldStatus !== 'SUCCESS') {
                $this->incrementAgentSaldo($pembayaran);
                $this->incrementAffiliateFee($pembayaran);
            }
        }
    }

    /**
     * Increment saldo agent berdasarkan profit pembayaran
     */
    protected function incrementAgentSaldo(Pembayaran $pembayaran): void
    {
        if (!$pembayaran->agent_id || !$pembayaran->profit) {
            Log::warning("Skipping saldo increment - missing agent_id or profit", [
                'pembayaran_id' => $pembayaran->id,
                'agent_id' => $pembayaran->agent_id,
                'profit' => $pembayaran->profit,
            ]);
            return;
        }

        $agent = Agent::find($pembayaran->agent_id);
        
        if (!$agent) {
            Log::warning("Agent ID {$pembayaran->agent_id} tidak ditemukan untuk Pembayaran ID {$pembayaran->id}");
            return;
        }

        // Pastikan saldo tidak null agar increment tidak menghasilkan null
        $needsSave = false;
        if (is_null($agent->saldo)) {
            $agent->saldo = 0;
            $needsSave = true;
        }
        if (is_null($agent->saldo_bulan)) {
            $agent->saldo_bulan = 0;
            $needsSave = true;
        }
        if (is_null($agent->saldo_tahun)) {
            $agent->saldo_tahun = 0;
            $needsSave = true;
        }
        if ($needsSave) {
            $agent->save();
        }

        // Increment saldo, saldo_bulan, dan saldo_tahun
        $agent->increment('saldo', $pembayaran->profit);
        $agent->increment('saldo_bulan', $pembayaran->profit);
        $agent->increment('saldo_tahun', $pembayaran->profit);

        Log::info("Saldo agent berhasil diupdate", [
            'pembayaran_id' => $pembayaran->id,
            'batch_id' => $pembayaran->batch_id,
            'agent_id' => $agent->id,
            'agent_name' => $agent->nama_pic,
            'profit' => $pembayaran->profit,
            'saldo_baru' => $agent->fresh()->saldo,
        ]);
    }

    /**
     * Increment saldo_fee and total_fee affiliate berdasarkan fee_affiliate pembayaran
     */
    protected function incrementAffiliateFee(Pembayaran $pembayaran): void
    {
        if (!$pembayaran->agent_id) {
            Log::info("Skipping affiliate fee increment - missing agent_id", [
                'pembayaran_id' => $pembayaran->id,
            ]);
            return;
        }

        // Check if agent_id is actually an affiliate ID (starts with AFT)
        $isDirectAffiliate = str_starts_with($pembayaran->agent_id, 'AFT');
        
        if ($isDirectAffiliate) {
            // Agent ID adalah Affiliate ID, ambil fee dari profit
            if (!$pembayaran->profit) {
                Log::info("Skipping affiliate fee increment - missing profit", [
                    'pembayaran_id' => $pembayaran->id,
                    'agent_id' => $pembayaran->agent_id,
                    'profit' => $pembayaran->profit,
                ]);
                return;
            }

            $affiliate = Affiliate::find($pembayaran->agent_id);
            
            if (!$affiliate) {
                Log::warning("Affiliate ID {$pembayaran->agent_id} tidak ditemukan untuk Pembayaran ID {$pembayaran->id}");
                return;
            }

            $feeAmount = $pembayaran->profit;

            // Increment saldo_fee dan total_fee (atomic operation)
            $affiliate->increment('saldo_fee', $feeAmount);
            $affiliate->increment('total_fee', $feeAmount);

            Log::info("Saldo affiliate berhasil diupdate (transaksi langsung affiliate)", [
                'pembayaran_id' => $pembayaran->id,
                'batch_id' => $pembayaran->batch_id,
                'affiliate_id' => $affiliate->id,
                'affiliate_name' => $affiliate->nama,
                'fee_from_profit' => $feeAmount,
                'saldo_fee_baru' => $affiliate->fresh()->saldo_fee,
                'total_fee_baru' => $affiliate->fresh()->total_fee,
            ]);
        } else {
            // Agent ID adalah Agent biasa, ambil fee dari fee_affiliate
            if (!$pembayaran->fee_affiliate) {
                Log::info("Skipping affiliate fee increment - missing fee_affiliate", [
                    'pembayaran_id' => $pembayaran->id,
                    'agent_id' => $pembayaran->agent_id,
                    'fee_affiliate' => $pembayaran->fee_affiliate,
                ]);
                return;
            }

            $agent = Agent::with('affiliate')->find($pembayaran->agent_id);
            
            if (!$agent || !$agent->affiliate_id || !$agent->affiliate) {
                Log::info("Agent tidak memiliki affiliate untuk Pembayaran ID {$pembayaran->id}", [
                    'agent_id' => $pembayaran->agent_id,
                    'affiliate_id' => $agent->affiliate_id ?? null,
                ]);
                return;
            }

            $affiliate = $agent->affiliate;
            $feeAmount = $pembayaran->fee_affiliate;

            // Increment saldo_fee dan total_fee (atomic operation)
            $affiliate->increment('saldo_fee', $feeAmount);
            $affiliate->increment('total_fee', $feeAmount);

            Log::info("Saldo affiliate berhasil diupdate (melalui agent)", [
                'pembayaran_id' => $pembayaran->id,
                'batch_id' => $pembayaran->batch_id,
                'agent_id' => $agent->id,
                'affiliate_id' => $affiliate->id,
                'affiliate_name' => $affiliate->nama,
                'fee_affiliate' => $feeAmount,
                'saldo_fee_baru' => $affiliate->fresh()->saldo_fee,
                'total_fee_baru' => $affiliate->fresh()->total_fee,
            ]);
        }
    }
}
