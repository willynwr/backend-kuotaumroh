<?php

namespace App\Observers;

use App\Models\Withdraw;

class WithdrawObserver
{
    /**
     * Handle the Withdraw "created" event.
     */
    public function created(Withdraw $withdraw): void
    {
        //
    }

    /**
     * Handle the Withdraw "updated" event.
     */
    public function updated(Withdraw $withdraw): void
    {
        //
    }

    /**
     * Handle the Withdraw "deleted" event.
     * Kembalikan saldo ke agent jika withdrawal yang sudah approve dihapus
     */
    public function deleted(Withdraw $withdraw): void
    {
        // Jika withdrawal yang dihapus sudah di-approve, kembalikan saldo ke agent
        if ($withdraw->status === 'approve' && $withdraw->agent) {
            $withdraw->agent->increment('saldo', $withdraw->jumlah);
            
            \Log::info("Withdrawal ID {$withdraw->id} deleted. Saldo agent {$withdraw->agent->nama_pic} dikembalikan sebesar Rp " . number_format($withdraw->jumlah, 0, ',', '.'));
        }
    }

    /**
     * Handle the Withdraw "restored" event.
     */
    public function restored(Withdraw $withdraw): void
    {
        //
    }

    /**
     * Handle the Withdraw "force deleted" event.
     */
    public function forceDeleted(Withdraw $withdraw): void
    {
        // Jika withdrawal yang dihapus permanen sudah di-approve, kembalikan saldo ke agent
        if ($withdraw->status === 'approve' && $withdraw->agent) {
            $withdraw->agent->increment('saldo', $withdraw->jumlah);
            
            \Log::info("Withdrawal ID {$withdraw->id} force deleted. Saldo agent {$withdraw->agent->nama_pic} dikembalikan sebesar Rp " . number_format($withdraw->jumlah, 0, ',', '.'));
        }
    }
}
