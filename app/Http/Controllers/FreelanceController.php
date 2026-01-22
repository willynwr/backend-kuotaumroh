<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Freelance;
use Illuminate\Support\Facades\Validator;

class FreelanceController extends Controller
{
    /**
     * Display a listing of all freelances.
     */
    public function index()
    {
        $freelances = Freelance::with('agents')->get();

        return response()->json([
            'message' => 'Freelances retrieved successfully',
            'data' => $freelances
        ], 200);
    }

    /**
     * Store a newly created freelance in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:freelances,email|unique:agents,email|unique:affiliates,email',
            'no_wa' => 'required|string|unique:freelances,no_wa',
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'date_register' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:freelances,link_referral',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();

        // Set default values
        if (!isset($data['date_register'])) {
            $data['date_register'] = now()->format('Y-m-d');
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        $freelance = Freelance::create($data);

        return response()->json([
            'message' => 'Freelance successfully created',
            'data' => $freelance
        ], 201);
    }

    /**
     * Display the specified freelance.
     */
    public function show($id)
    {
        $freelance = Freelance::with('agents')->find($id);

        if (!$freelance) {
            return response()->json(['message' => 'Freelance not found'], 404);
        }

        return response()->json([
            'message' => 'Freelance retrieved successfully',
            'data' => $freelance
        ], 200);
    }

    /**
     * Update the specified freelance in storage.
     */
    public function update(Request $request, $id)
    {
        $freelance = Freelance::find($id);

        if (!$freelance) {
            return response()->json(['message' => 'Freelance not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'string|max:255',
            'email' => 'email|unique:freelances,email,' . $id . '|unique:agents,email|unique:affiliates,email',
            'no_wa' => 'string|unique:freelances,no_wa,' . $id,
            'provinsi' => 'string',
            'kab_kota' => 'string',
            'alamat_lengkap' => 'string',
            'date_register' => 'date',
            'is_active' => 'boolean',
            'link_referral' => 'string|alpha_dash:ascii|unique:freelances,link_referral,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $freelance->update($request->all());

        return response()->json([
            'message' => 'Freelance successfully updated',
            'data' => $freelance
        ], 200);
    }

    /**
     * Remove the specified freelance from storage.
     */
    public function destroy($id)
    {
        $freelance = Freelance::find($id);

        if (!$freelance) {
            return response()->json(['message' => 'Freelance not found'], 404);
        }

        // Check if freelance has agents
        $agentCount = $freelance->agents()->count();

        if ($agentCount > 0) {
            return response()->json([
                'message' => 'Cannot delete freelance. There are ' . $agentCount . ' agent(s) associated with this freelance.',
                'agent_count' => $agentCount
            ], 422);
        }

        $freelance->delete();

        return response()->json(['message' => 'Freelance successfully deleted'], 200);
    }

    /**
     * Activate a freelance.
     */
    public function activate($id)
    {
        $freelance = Freelance::find($id);

        if (!$freelance) {
            return response()->json(['message' => 'Freelance not found'], 404);
        }

        $freelance->update(['is_active' => true]);

        return response()->json([
            'message' => 'Freelance successfully activated',
            'data' => $freelance
        ], 200);
    }

    /**
     * Deactivate a freelance.
     */
    public function deactivate($id)
    {
        $freelance = Freelance::find($id);

        if (!$freelance) {
            return response()->json(['message' => 'Freelance not found'], 404);
        }

        $freelance->update(['is_active' => false]);

        return response()->json([
            'message' => 'Freelance successfully deactivated',
            'data' => $freelance
        ], 200);
    }

    /**
     * Get all agents for a specific freelance.
     */
    public function agents($id)
    {
        $freelance = Freelance::find($id);

        if (!$freelance) {
            return response()->json(['message' => 'Freelance not found'], 404);
        }

        $agents = $freelance->agents;

        return response()->json([
            'message' => 'Agents retrieved successfully',
            'data' => $agents
        ], 200);
    }

    /**
     * Display order page for freelance.
     */
    public function order($linkReferral)
    {
        $packages = \App\Models\Produk::orderBy('created_at', 'desc')->get();
        
        // Get freelance by link referral
        $freelance = \App\Models\Freelance::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();
        
        if (!$freelance) {
            abort(404, 'Freelance not found');
        }
        
        $agents = $freelance->agents;
        
        return view('freelance.order', compact('packages', 'agents', 'linkReferral'));
    }
}
