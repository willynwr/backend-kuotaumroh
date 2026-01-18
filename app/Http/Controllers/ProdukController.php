<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of all products.
     * GET /api/produk
     */
    public function index()
    {
        try {
            $produk = Produk::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $produk
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created product.
     * POST /api/produk
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'required|string|max:255',
            'tipe_paket' => 'required|string|max:255',
            'masa_aktif' => 'required|integer|min:1',
            'total_kuota' => 'required|integer|min:0',
            'kuota_utama' => 'required|integer|min:0',
            'kuota_bonus' => 'required|integer|min:0',
            'telp' => 'nullable|integer|min:0',
            'sms' => 'nullable|integer|min:0',
            'harga_modal' => 'required|integer|min:0',
            'harga_eup' => 'required|integer|min:0',
            'persentase_margin_star' => 'nullable|numeric|min:0|max:100',
            'margin_star' => 'nullable|integer|min:0',
            'margin_total' => 'nullable|integer|min:0',
            'fee_travel' => 'nullable|integer|min:0',
            'persentase_fee_travel' => 'nullable|numeric|min:0|max:100',
            'persentase_fee_affiliate' => 'nullable|numeric|min:0|max:100',
            'fee_affiliate' => 'nullable|integer|min:0',
            'persentase_fee_host' => 'nullable|numeric|min:0|max:100',
            'fee_host' => 'nullable|integer|min:0',
            'harga_tp_travel' => 'nullable|integer|min:0',
            'harga_tp_host' => 'nullable|integer|min:0',
            'poin' => 'nullable|integer|min:0',
            'profit' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produk = Produk::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data' => $produk
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product.
     * GET /api/produk/{id}
     */
    public function show($id)
    {
        try {
            $produk = Produk::find($id);

            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diambil',
                'data' => $produk
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified product.
     * POST /api/produk/{id} or PUT/PATCH /api/produk/{id}
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_paket' => 'sometimes|required|string|max:255',
            'tipe_paket' => 'sometimes|required|string|max:255',
            'masa_aktif' => 'sometimes|required|integer|min:1',
            'total_kuota' => 'sometimes|required|integer|min:0',
            'kuota_utama' => 'sometimes|required|integer|min:0',
            'kuota_bonus' => 'sometimes|required|integer|min:0',
            'telp' => 'nullable|integer|min:0',
            'sms' => 'nullable|integer|min:0',
            'harga_modal' => 'sometimes|required|integer|min:0',
            'harga_eup' => 'sometimes|required|integer|min:0',
            'persentase_margin_star' => 'nullable|numeric|min:0|max:100',
            'margin_star' => 'nullable|integer|min:0',
            'margin_total' => 'nullable|integer|min:0',
            'fee_travel' => 'nullable|integer|min:0',
            'persentase_fee_travel' => 'nullable|numeric|min:0|max:100',
            'persentase_fee_affiliate' => 'nullable|numeric|min:0|max:100',
            'fee_affiliate' => 'nullable|integer|min:0',
            'persentase_fee_host' => 'nullable|numeric|min:0|max:100',
            'fee_host' => 'nullable|integer|min:0',
            'harga_tp_travel' => 'nullable|integer|min:0',
            'harga_tp_host' => 'nullable|integer|min:0',
            'poin' => 'nullable|integer|min:0',
            'profit' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produk = Produk::find($id);

            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            $produk->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diupdate',
                'data' => $produk
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product.
     * DELETE /api/produk/{id}
     */
    public function destroy($id)
    {
        try {
            $produk = Produk::find($id);

            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            $produk->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
