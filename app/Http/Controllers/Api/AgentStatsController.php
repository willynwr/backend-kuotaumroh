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
        
        // Profit bulan ini diambil dari kolom saldo di table agent
        $monthlyProfit = $agent->saldo ?? 0;
        
        // Hitung total profit dari semua pesanan yang pembayarannya berhasil
        $totalProfit = Pesanan::where('agent_id', $agentId)
            ->whereHas('pembayaran', function($query) {
                $query->where('status_pembayaran', 'selesai');
            })
            ->sum('profit');
        
        // Hitung total transaksi (jumlah pesanan) bulan ini yang pembayarannya berhasil
        $monthlyTransactions = Pesanan::where('agent_id', $agentId)
            ->whereHas('pembayaran', function($query) {
                $query->where('status_pembayaran', 'selesai');
            })
            ->whereBetween('created_at', [$startOfMonth, $now])
            ->count();
        
        // Hitung total transaksi keseluruhan yang pembayarannya berhasil
        $totalTransactions = Pesanan::where('agent_id', $agentId)
            ->whereHas('pembayaran', function($query) {
                $query->where('status_pembayaran', 'selesai');
            })
            ->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'agent_id' => $agentId,
                'agent_name' => $agent->nama_pic,
                'monthly_profit' => $monthlyProfit,
                'total_profit' => $totalProfit,
                'monthly_transactions' => $monthlyTransactions,
                'total_transactions' => $totalTransactions,
                'wallet_balance' => 0, // TODO: implement dari table wallet/saldo
                'pending_withdrawal' => 0, // TODO: implement dari table withdraw
            ]
        ]);
    }
}
