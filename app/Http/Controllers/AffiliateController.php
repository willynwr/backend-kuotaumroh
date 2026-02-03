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
        \Log::info('=== AFFILIATE STORE START ===');
        \Log::info('Request all data:', $request->all());
        \Log::info('Request has file ktp:', [$request->hasFile('ktp')]);
        \Log::info('Request files:', $request->allFiles());
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliate,email|unique:agent,email|unique:freelance,email',
            'no_wa' => 'required|string|unique:affiliate,no_wa|unique:agent,no_hp|unique:freelance,no_wa',
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'ktp' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg,gif|max:2048',
            'surat_ppiu' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'date_register' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:affiliate,link_referral|unique:agent,link_referal|unique:freelance,link_referral',
        ], [
            'no_wa.unique' => 'Nomor WhatsApp sudah terdaftar dalam sistem',
            'link_referral.unique' => 'Link referral sudah digunakan',
            'ktp.max' => 'Ukuran file KTP maksimal 5 MB',
            'ktp.mimes' => 'Format file KTP harus PDF, PNG, JPG, atau JPEG',
            'logo.max' => 'Ukuran file logo maksimal 2 MB',
            'logo.mimes' => 'Format file logo harus PNG, JPG, JPEG, atau GIF',
            'surat_ppiu.max' => 'Ukuran file Surat PPIU maksimal 2 MB',
            'surat_ppiu.mimes' => 'Format file Surat PPIU harus PDF, PNG, JPG, atau JPEG',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['logo', 'surat_ppiu', 'ktp']);

        // Handle KTP upload
        if ($request->hasFile('ktp')) {
            \Log::info('KTP file detected in request');
            $ktpPath = $request->file('ktp')->store('affiliate_ktp', 'public');
            $data['ktp'] = $ktpPath;
            \Log::info('KTP uploaded to: ' . $ktpPath);
        } else {
            \Log::warning('No KTP file in request');
        }

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

        \Log::info('Affiliate created successfully', ['id' => $affiliate->id, 'ktp' => $affiliate->ktp]);

        // Check if request is from web form or API
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Affiliate successfully created',
                'data' => $affiliate
            ], 201);
        }

        // For web form submission
        return redirect('/admin/users?tab=affiliate')->with('success', 'Affiliate berhasil ditambahkan');
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
            'email' => 'email|unique:affiliate,email,' . $id . '|unique:agent,email|unique:freelance,email',
            'no_wa' => 'string|unique:affiliate,no_wa,' . $id,
            'provinsi' => 'string',
            'kab_kota' => 'string',
            'alamat_lengkap' => 'string',
            'date_register' => 'date',
            'is_active' => 'boolean',
            'link_referral' => 'string|alpha_dash:ascii|unique:affiliate,link_referral,' . $id,
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
        
        // Cari selected agent (jika ada di session atau default ke agent pertama jika hanya 1)
        $selectedAgent = null;
        $saldo = 0;
        if ($agents->count() === 1) {
            $selectedAgent = $agents->first();
            $saldo = $selectedAgent->saldo ?? 0;
        }
        
        return view('affiliate.order', compact('packages', 'agents', 'linkReferral', 'saldo'));
    }

    /**
     * Get profile data for affiliate by link_referral.
     */
    public function getProfile($linkReferral)
    {
        $affiliate = Affiliate::where('link_referral', $linkReferral)
            ->with('agents')
            ->first();

        if (!$affiliate) {
            return response()->json([
                'message' => 'Affiliate not found'
            ], 404);
        }

        // Count active agents
        $activeAgentsCount = $affiliate->agents()->where('is_active', 1)->count();
        $totalAgentsCount = $affiliate->agents()->count();

        return response()->json([
            'message' => 'Profile retrieved successfully',
            'data' => [
                'id' => $affiliate->id,
                'nama' => $affiliate->nama,
                'email' => $affiliate->email,
                'no_wa' => $affiliate->no_wa,
                'provinsi' => $affiliate->provinsi,
                'kab_kota' => $affiliate->kab_kota,
                'alamat_lengkap' => $affiliate->alamat_lengkap,
                'link_referral' => $affiliate->link_referral,
                'ref_code' => $affiliate->ref_code,
                'saldo_fee' => $affiliate->saldo_fee ?? 0,
                'total_fee' => $affiliate->total_fee ?? 0,
                'is_active' => $affiliate->is_active,
                'date_register' => $affiliate->date_register,
                'agents_recruited' => $totalAgentsCount,
                'active_agents' => $activeAgentsCount,
            ]
        ], 200);
    }
}
