<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class AgentStatsController extends Controller
{
    /**
     * Get agent statistics
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats(Request $request)
    {
        $agentId = $request->query('agent_id');
        
        if (!$agentId) {
            return response()->json([
                'success' => false,
                'message' => 'Agent ID is required'
            ], 400);
        }
        
        $agent = Agent::find($agentId);
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Agent not found'
            ], 404);
        }
        
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfYear = $now->copy()->startOfYear();
        
        // Profit bulan ini diambil dari kolom saldo_bulan (reset setiap bulan)
        $monthlyProfit = $agent->saldo_bulan ?? 0;
        
        // Breakdown profit store (IND-) dan bulk (BATCH_) untuk bulan ini
        $monthlyStoreProfit = \App\Models\Pembayaran::where('agent_id', $agentId)
            ->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS'])
            ->where('batch_id', 'LIKE', 'IND-%')
            ->whereBetween('created_at', [$startOfMonth, $now])
            ->sum('profit') ?? 0;
        
        $monthlyBulkProfit = \App\Models\Pembayaran::where('agent_id', $agentId)
            ->whereIn('status_pembayaran', ['selesai', 'berhasil', 'SUCCESS'])
            ->where('batch_id', 'LIKE', 'BATCH_%')
            ->whereBetween('created_at', [$startOfMonth, $now])
            ->sum('profit') ?? 0;
        
        // Total akumulasi tahun ini diambil dari kolom saldo_tahun (reset setiap tahun)
        $totalProfit = $agent->saldo_tahun ?? 0;
        
        // Hitung total transaksi (jumlah pesanan) bulan ini yang pembayarannya berhasil
        $monthlyTransactions = Pesanan::where('kategori_channel', 'agent')
            ->where('channel_id', $agentId)
            ->whereHas('pembayaran', function($query) {
                $query->where('status_pembayaran', 'selesai');
            })
            ->whereBetween('created_at', [$startOfMonth, $now])
            ->count();
        
        // Hitung total transaksi tahun ini yang pembayarannya berhasil
        $totalTransactions = Pesanan::where('kategori_channel', 'agent')
            ->where('channel_id', $agentId)
            ->whereHas('pembayaran', function($query) {
                $query->where('status_pembayaran', 'selesai');
            })
            ->whereBetween('created_at', [$startOfYear, $now])
            ->count();
        
        // Wallet balance dari saldo (tidak pernah reset)
        $walletBalance = $agent->saldo ?? 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'agent_id' => $agentId,
                'agent_name' => $agent->nama_pic,
                'monthly_profit' => $monthlyProfit,
                'monthly_store_profit' => $monthlyStoreProfit,
                'monthly_bulk_profit' => $monthlyBulkProfit,
                'total_profit' => $totalProfit,
                'monthly_transactions' => $monthlyTransactions,
                'total_transactions' => $totalTransactions,
                'wallet_balance' => $walletBalance,
                'pending_withdrawal' => 0, // TODO: implement dari table withdraw
            ]
        ]);
    }
}
