<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function asset(string $file)
    {
        if (!preg_match('/\A[A-Za-z0-9_\-\.]+\z/', $file)) {
            abort(404);
        }

        if (!str_ends_with(strtolower($file), '.png')) {
            abort(404);
        }

        $path = resource_path('views/agent/agent123/' . $file);
        if (!is_file($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }

    public function dashboard()
    {
        return view('agent.dashboard');
    }

    public function catalog()
    {
        return view('agent.catalog');
    }

    public function history()
    {
        return view('agent.history');
    }

    public function order()
    {
        return view('agent.order');
    }

    public function wallet()
    {
        return view('agent.wallet');
    }

    public function withdraw()
    {
        return view('agent.withdraw');
    }

    public function profile()
    {
        return view('agent.profile');
    }

    public function referrals()
    {
        return view('agent.referrals');
    }

    public function index()
    {
        $agents = Agent::with(['affiliate', 'freelance'])->get();

        return response()->json([
            'message' => 'Agents retrieved successfully',
            'data' => $agents
        ], 200);
    }

    public function show($id)
    {
        $agent = Agent::with(['affiliate', 'freelance'])->find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        return response()->json([
            'message' => 'Agent retrieved successfully',
            'data' => $agent
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:agents,email|unique:affiliates,email|unique:freelances,email',
            'affiliate_id' => 'nullable|exists:affiliates,id',
            'freelance_id' => 'nullable|exists:freelances,id',
            'kategori_agent' => 'required|in:Referral,Host',
            'nama_pic' => 'required|string',
            'no_hp' => 'required|string|unique:agents,no_hp',
            'nama_travel' => 'nullable|string',
            'jenis_travel' => 'nullable|string',
            'provinsi' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'link_gmaps' => 'nullable|string',
            'long' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'link_referal' => 'nullable|string',
            'rekening_agent' => 'nullable|string',
            'date_approve' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'surat_ppiu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Validasi: hanya boleh salah satu dari affiliate_id atau freelance_id
        if ($request->affiliate_id && $request->freelance_id) {
            return response()->json([
                'message' => 'Agent hanya bisa terhubung ke Affiliate ATAU Freelance, tidak keduanya'
            ], 422);
        }

        // Validasi: minimal salah satu harus diisi
        if (!$request->affiliate_id && !$request->freelance_id) {
            return response()->json([
                'message' => 'Agent harus terhubung ke Affiliate atau Freelance'
            ], 422);
        }

        $data = $request->except(['logo', 'surat_ppiu']);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('agent_logos', 'public');
            $data['logo'] = $logoPath;
        }

        if ($request->hasFile('surat_ppiu')) {
            $suratPath = $request->file('surat_ppiu')->store('agent_documents', 'public');
            $data['surat_ppiu'] = $suratPath;
        }

        $agent = Agent::create($data);

        return response()->json([
            'message' => 'Agent successfully created',
            'data' => $agent
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:agents,email,' . $id . '|unique:affiliates,email|unique:freelances,email',
            'affiliate_id' => 'nullable|exists:affiliates,id',
            'freelance_id' => 'nullable|exists:freelances,id',
            'kategori_agent' => 'in:Referral,Host',
            'nama_pic' => 'string',
            'no_hp' => 'string|unique:agents,no_hp,' . $id,
            'nama_travel' => 'nullable|string',
            'jenis_travel' => 'nullable|string',
            'provinsi' => 'string',
            'kabupaten_kota' => 'string',
            'alamat_lengkap' => 'string',
            'link_gmaps' => 'nullable|string',
            'long' => 'nullable|numeric',
            'lat' => 'nullable|numeric',
            'link_referal' => 'nullable|string',
            'rekening_agent' => 'nullable|string',
            'date_approve' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'surat_ppiu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'total_traveller' => 'nullable|integer',
            'status' => 'string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->except(['logo', 'surat_ppiu']);

        if ($request->hasFile('logo')) {
            if ($agent->logo && Storage::disk('public')->exists($agent->logo)) {
                Storage::disk('public')->delete($agent->logo);
            }
            $logoPath = $request->file('logo')->store('agent_logos', 'public');
            $data['logo'] = $logoPath;
        }

        if ($request->hasFile('surat_ppiu')) {
            if ($agent->surat_ppiu && Storage::disk('public')->exists($agent->surat_ppiu)) {
                Storage::disk('public')->delete($agent->surat_ppiu);
            }
            $suratPath = $request->file('surat_ppiu')->store('agent_documents', 'public');
            $data['surat_ppiu'] = $suratPath;
        }

        $agent->update($data);

        return response()->json([
            'message' => 'Agent successfully updated',
            'data' => $agent
        ], 200);
    }

    public function destroy($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        if ($agent->logo && Storage::disk('public')->exists($agent->logo)) {
            Storage::disk('public')->delete($agent->logo);
        }

        if ($agent->surat_ppiu && Storage::disk('public')->exists($agent->surat_ppiu)) {
            Storage::disk('public')->delete($agent->surat_ppiu);
        }

        $agent->delete();

        return response()->json(['message' => 'Agent successfully deleted'], 200);
    }

    public function approve(Request $request, $id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'link_referal' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $agent->update([
            'status' => 'approve',
            'link_referal' => $request->link_referal,
            'date_approve' => now()->format('Y-m-d'),
        ]);

        return response()->json([
            'message' => 'Agent successfully approved',
            'data' => $agent
        ], 200);
    }

    public function reject($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $agent->update([
            'status' => 'reject',
        ]);

        return response()->json([
            'message' => 'Agent successfully rejected',
            'data' => $agent
        ], 200);
    }

    public function activate($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $agent->update([
            'is_active' => 1,
        ]);

        return response()->json([
            'message' => 'Agent successfully activated',
            'data' => $agent
        ], 200);
    }

    public function deactivate($id)
    {
        $agent = Agent::find($id);

        if (!$agent) {
            return response()->json(['message' => 'Agent not found'], 404);
        }

        $agent->update([
            'is_active' => 0,
        ]);

        return response()->json([
            'message' => 'Agent successfully deactivated',
            'data' => $agent
        ], 200);
    }
}
