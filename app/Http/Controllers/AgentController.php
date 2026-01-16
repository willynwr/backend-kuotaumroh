<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Agent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:agents,email',
            'jenis_agent' => 'required|in:travel agent,agent,freelance',
            'nama_pic' => 'required|string',
            'no_hp' => 'required|string',
            'nama_travel' => 'required|string',
            'jenis_travel' => 'required|string',
            'provinsi' => 'required|string',
            'kabupaten_kota' => 'required|string',
            'alamat_lengkap' => 'required|string',
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
}
