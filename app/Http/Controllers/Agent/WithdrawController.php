<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Agent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|exists:agents,id',
            'rekening_id' => 'required|exists:rekenings,id',
            'jumlah' => 'required|integer|min:100000',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $withdraw = Withdraw::create([
                'agent_id' => $request->agent_id,
                'rekening_id' => $request->rekening_id,
                'jumlah' => $request->jumlah,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan penarikan berhasil diajukan',
                'data' => $withdraw->load(['agent', 'rekening'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan penarikan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $agentId = $request->query('agent_id');
        
        $query = Withdraw::with(['agent', 'rekening']);
        
        if ($agentId) {
            $query->where('agent_id', $agentId);
        }
        
        $withdraws = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $withdraws
        ]);
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $withdraw = Withdraw::with('agent')->lockForUpdate()->findOrFail($id);
            
            // Cek apakah withdrawal sudah diproses sebelumnya
            if ($withdraw->status !== 'pending') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Penarikan ini sudah diproses sebelumnya'
                ], 400);
            }
            
            $agent = $withdraw->agent;
            
            // Cek apakah saldo agent cukup
            if ($agent->saldo < $withdraw->jumlah) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo agent tidak mencukupi untuk penarikan ini'
                ], 400);
            }
            
            // Update status withdrawal menjadi approve
            $withdraw->update([
                'status' => 'approve',
                'date_approve' => now()->format('Y-m-d')
            ]);
            
            // Kurangi saldo agent
            $agent->decrement('saldo', $withdraw->jumlah);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Penarikan berhasil disetujui dan saldo telah dikurangi',
                'data' => $withdraw->load(['agent', 'rekening']),
                'agent_saldo_after' => $agent->fresh()->saldo
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui penarikan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function reject($id)
    {
        try {
            $withdraw = Withdraw::findOrFail($id);
            
            // Cek apakah withdrawal sudah diproses sebelumnya
            if ($withdraw->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Penarikan ini sudah diproses sebelumnya'
                ], 400);
            }
            
            // Update status withdrawal menjadi reject
            $withdraw->update([
                'status' => 'reject'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Penarikan berhasil ditolak',
                'data' => $withdraw->load(['agent', 'rekening'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak penarikan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
