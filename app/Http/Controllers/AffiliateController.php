<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Affiliate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AffiliateController extends Controller
{
    /**
     * Display a listing of all affiliates.
     */
    public function index()
    {
        $affiliates = Affiliate::with('agents')->get();

        return response()->json([
            'message' => 'Affiliates retrieved successfully',
            'data' => $affiliates
        ], 200);
    }

    /**
     * Store a newly created affiliate in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliates,email|unique:agents,email|unique:freelances,email',
            'no_wa' => 'required|string|unique:affiliates,no_wa',
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg,gif|max:2048',
            'surat_ppiu' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'date_register' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:affiliates,link_referral',
        ], [
            'logo.max' => 'Ukuran file logo maksimal 2 MB',
            'logo.mimes' => 'Format file logo harus PNG, JPG, JPEG, atau GIF',
            'surat_ppiu.max' => 'Ukuran file Surat PPIU maksimal 2 MB',
            'surat_ppiu.mimes' => 'Format file Surat PPIU harus PDF, PNG, JPG, atau JPEG',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except(['logo', 'surat_ppiu']);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('affiliate_logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle surat PPIU upload
        if ($request->hasFile('surat_ppiu')) {
            $suratPath = $request->file('surat_ppiu')->store('affiliate_documents', 'public');
            $data['surat_ppiu'] = $suratPath;
        }

        // Set default values
        if (!isset($data['date_register'])) {
            $data['date_register'] = now()->format('Y-m-d');
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        $affiliate = Affiliate::create($data);

        return response()->json([
            'message' => 'Affiliate successfully created',
            'data' => $affiliate
        ], 201);
    }

    /**
     * Display the specified affiliate.
     */
    public function show($id)
    {
        $affiliate = Affiliate::with('agents')->find($id);

        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        return response()->json([
            'message' => 'Affiliate retrieved successfully',
            'data' => $affiliate
        ], 200);
    }

    /**
     * Update the specified affiliate in storage.
     */
    public function update(Request $request, $id)
    {
        $affiliate = Affiliate::find($id);

        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'string|max:255',
            'email' => 'email|unique:affiliates,email,' . $id . '|unique:agents,email|unique:freelances,email',
            'no_wa' => 'string|unique:affiliates,no_wa,' . $id,
            'provinsi' => 'string',
            'kab_kota' => 'string',
            'alamat_lengkap' => 'string',
            'date_register' => 'date',
            'is_active' => 'boolean',
            'link_referral' => 'string|alpha_dash:ascii|unique:affiliates,link_referral,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $affiliate->update($request->all());

        return response()->json([
            'message' => 'Affiliate successfully updated',
            'data' => $affiliate
        ], 200);
    }

    /**
     * Remove the specified affiliate from storage.
     */
    public function destroy($id)
    {
        $affiliate = Affiliate::find($id);

        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        // Check if affiliate has agents
        $agentCount = $affiliate->agents()->count();

        if ($agentCount > 0) {
            return response()->json([
                'message' => 'Cannot delete affiliate. There are ' . $agentCount . ' agent(s) associated with this affiliate.',
                'agent_count' => $agentCount
            ], 422);
        }

        $affiliate->delete();

        return response()->json(['message' => 'Affiliate successfully deleted'], 200);
    }

    /**
     * Activate an affiliate.
     */
    public function activate($id)
    {
        $affiliate = Affiliate::find($id);

        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        $affiliate->update(['is_active' => true]);

        return response()->json([
            'message' => 'Affiliate successfully activated',
            'data' => $affiliate
        ], 200);
    }

    /**
     * Deactivate an affiliate.
     */
    public function deactivate($id)
    {
        $affiliate = Affiliate::find($id);

        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        $affiliate->update(['is_active' => false]);

        return response()->json([
            'message' => 'Affiliate successfully deactivated',
            'data' => $affiliate
        ], 200);
    }

    /**
     * Get all agents for a specific affiliate.
     */
    public function agents($id)
    {
        $affiliate = Affiliate::find($id);

        if (!$affiliate) {
            return response()->json(['message' => 'Affiliate not found'], 404);
        }

        $agents = $affiliate->agents;

        return response()->json([
            'message' => 'Agents retrieved successfully',
            'data' => $agents
        ], 200);
    }

    /**
     * Display order page for affiliate.
     */
    public function order($linkReferral)
    {
        $packages = \App\Models\Produk::orderBy('created_at', 'desc')->get();
        
        // Get affiliate by link referral
        $affiliate = \App\Models\Affiliate::where('link_referral', $linkReferral)
            ->where('is_active', true)
            ->with('agents')
            ->first();
        
        if (!$affiliate) {
            abort(404, 'Affiliate not found');
        }
        
        $agents = $affiliate->agents;
        
        return view('affiliate.order', compact('packages', 'agents', 'linkReferral'));
    }
}
