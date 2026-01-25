<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekening;
use Illuminate\Support\Facades\Validator;

class RekeningController extends Controller
{
    public function index(Request $request)
    {
        $agentId = $request->query('agent_id');
        
        if (!$agentId) {
            return response()->json([
                'success' => false,
                'message' => 'agent_id diperlukan'
            ], 400);
        }
        
        $rekenings = Rekening::where('agent_id', $agentId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $rekenings
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|string|exists:agent,id',
            'nama_rekening' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check duplicate nomor rekening untuk agent yang sama
        $exists = Rekening::where('agent_id', $request->agent_id)
            ->where('nomor_rekening', $request->nomor_rekening)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor rekening sudah terdaftar'
            ], 422);
        }

        try {
            $rekening = Rekening::create([
                'agent_id' => $request->agent_id,
                'nama_rekening' => $request->nama_rekening,
                'bank' => $request->bank,
                'nomor_rekening' => $request->nomor_rekening,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekening berhasil ditambahkan',
                'data' => $rekening
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan rekening',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $rekening = Rekening::findOrFail($id);
            $rekening->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rekening berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus rekening',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
