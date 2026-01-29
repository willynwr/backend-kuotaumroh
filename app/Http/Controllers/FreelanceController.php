<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Freelance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
        \Log::info('=== FREELANCE STORE START ===');
        \Log::info('Request all data:', $request->all());
        \Log::info('Request has file ktp:', [$request->hasFile('ktp')]);
        \Log::info('Request files:', $request->allFiles());
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:freelance,email|unique:agent,email|unique:affiliate,email',
            'no_wa' => 'required|string|unique:freelance,no_wa|unique:agent,no_hp|unique:affiliate,no_wa',
            'provinsi' => 'required|string',
            'kab_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'ktp' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:5120',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg,gif|max:2048',
            'surat_ppiu' => 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048',
            'date_register' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'link_referral' => 'required|string|alpha_dash:ascii|unique:freelance,link_referral|unique:agent,link_referal|unique:affiliate,link_referral',
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
            $ktpPath = $request->file('ktp')->store('freelance_ktp', 'public');
            $data['ktp'] = $ktpPath;
            \Log::info('KTP uploaded to: ' . $ktpPath);
        } else {
            \Log::warning('No KTP file in request');
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('freelance_logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle surat PPIU upload
        if ($request->hasFile('surat_ppiu')) {
            $suratPath = $request->file('surat_ppiu')->store('freelance_documents', 'public');
            $data['surat_ppiu'] = $suratPath;
        }

        // Set default values
        if (!isset($data['date_register'])) {
            $data['date_register'] = now()->format('Y-m-d');
        }

        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        $freelance = Freelance::create($data);

        \Log::info('Freelance created successfully', ['id' => $freelance->id, 'ktp' => $freelance->ktp]);

        // Check if request is from web form or API
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Freelance successfully created',
                'data' => $freelance
            ], 201);
        }

        // For web form submission
        return redirect('/admin/users?tab=freelance')->with('success', 'Freelance berhasil ditambahkan');
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
            'email' => 'email|unique:freelance,email,' . $id . '|unique:agent,email|unique:affiliate,email',
            'no_wa' => 'string|unique:freelance,no_wa,' . $id,
            'provinsi' => 'string',
            'kab_kota' => 'string',
            'alamat_lengkap' => 'string',
            'date_register' => 'date',
            'is_active' => 'boolean',
            'link_referral' => 'string|alpha_dash:ascii|unique:freelance,link_referral,' . $id,
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
