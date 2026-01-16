<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::all();
        
        return response()->json([
            'message' => 'Agents retrieved successfully',
            'data' => $agents
        ], 200);
    }

    public function show($id)
    {
        $agent = Agent::find($id);

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
            'email' => 'required|email',
            'jenis_agent' => 'required|in:travel agent,agent,freelance',
            'nama_pic' => 'required|string',
            'no_hp' => 'required|string',
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
            'email' => 'email',
            'jenis_agent' => 'in:travel agent,agent,freelance',
            'nama_pic' => 'string',
            'no_hp' => 'string',
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
}
