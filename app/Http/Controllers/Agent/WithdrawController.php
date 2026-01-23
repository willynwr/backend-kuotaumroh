<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Agent;
use Illuminate\Support\Facades\Validator;

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
}
