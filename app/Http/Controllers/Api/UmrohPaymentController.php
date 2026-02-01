<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payment\BulkPaymentService;
use App\Services\Payment\PackagePricingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Umroh Payment API Controller
 * 
 * Handles all payment-related API endpoints for Umroh packages.
 */
class UmrohPaymentController extends Controller
{
    protected BulkPaymentService $paymentService;
    protected PackagePricingService $pricingService;

    public function __construct(
        BulkPaymentService $paymentService,
        PackagePricingService $pricingService
    ) {
        $this->paymentService = $paymentService;
        $this->pricingService = $pricingService;
    }

    /**
     * GET /api/umroh/package
     * 
     * Get catalog of Umroh packages
     * 
     * Query params:
     * - ref_code: legacy param (ignored, untuk backward compatibility)
     * - affiliate_id: AFTxxx untuk affiliate bulk catalog
     * - agent_id: AGTxxx untuk agent bulk catalog atau ADMxxx untuk admin
     * - context: 'store' untuk agent store publik, default 'bulk'
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getPackages(Request $request): JsonResponse
    {
        try {
            // Ambil params
            $affiliateId = $request->input('affiliate_id');
            $agentId = $request->input('agent_id');
            $context = $request->input('context', 'bulk'); // 'bulk' or 'store'
            
            $packages = [];
            
            // ===== ROUTE 1: Affiliate Bulk Catalog =====
            if ($affiliateId) {
                // Validasi prefix AFT
                if (!str_starts_with($affiliateId, 'AFT')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid affiliate_id format. Expected: AFTxxx',
                    ], 400);
                }
                
                $packages = $this->pricingService->getBulkCatalogForAffiliate($affiliateId);
            }
            
            // ===== ROUTE 2: Agent/Admin Catalog =====
            elseif ($agentId) {
                // Detect apakah AGT atau ADM
                $prefix = substr($agentId, 0, 3);
                
                // Admin (ADMxxx) -> bulk only
                if ($prefix === 'ADM') {
                    $packages = $this->pricingService->getBulkCatalogForAdmin($agentId);
                }
                
                // Agent (AGTxxx) -> bulk or store
                elseif ($prefix === 'AGT') {
                    if ($context === 'store') {
                        // Store publik (individu)
                        $packages = $this->pricingService->getStoreCatalogForAgent($agentId);
                    } else {
                        // Bulk (default)
                        $packages = $this->pricingService->getBulkCatalogForAgent($agentId);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid agent_id format. Expected: AGTxxx or ADMxxx',
                    ], 400);
                }
            }
            
            // ===== ROUTE 3: Fallback ke legacy (untuk backward compatibility) =====
            else {
                // Jika tidak ada affiliate_id/agent_id, fallback ke getCatalog lama
                // (pakai BulkPaymentService yang query dari Produk lokal)
                $refCode = $request->input('ref_code', 'bulk_umroh');
                $packages = $this->paymentService->getCatalog($refCode);
            }

            // Return array langsung (tidak wrapped) untuk kompatibilitas dengan frontend
            return response()->json($packages);
            
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil catalog paket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/umroh/bulkpayment
     * 
     * Create bulk payment order
     * 
     * Request body:
     * - affiliate_id: AFTxxx (untuk affiliate)
     * - agent_id: AGTxxx atau ADMxxx (untuk agent/admin)
     * - batch_id: optional
     * - batch_name: optional
     * - payment_method: QRIS atau SALDO
     * - detail: optional (JSON string untuk scheduled time, dll)
     * - ref_code: required (untuk backward compatibility)
     * - msisdn: array nomor HP
     * - package_id: array package ID
     * - price: array (IGNORED - akan diambil dari VIEW server-side)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function createBulkPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'nullable|string|max:100',
            'batch_name' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:QRIS,SALDO,qris,saldo',
            'detail' => 'nullable|string',
            'ref_code' => 'required|string|max:50',
            'msisdn' => 'required|array|min:1',
            'msisdn.*' => 'required|string',
            'package_id' => 'required|array|min:1',
            'package_id.*' => 'required|string',
            'price' => 'nullable|array', // Optional (untuk backward compatibility)
            'price.*' => 'nullable|numeric|min:0',
            'affiliate_id' => 'nullable|string|max:20', // AFTxxx
            'agent_id' => 'nullable|string|max:20', // AGTxxx atau ADMxxx
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // ===== DETECT ROLE & USER ID =====
            $affiliateId = $request->input('affiliate_id');
            $agentId = $request->input('agent_id');
            $role = null;
            $userId = null;

            if ($affiliateId) {
                if (!str_starts_with($affiliateId, 'AFT')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid affiliate_id format. Expected: AFTxxx',
                    ], 400);
                }
                $role = 'affiliate';
                $userId = $affiliateId;
            } elseif ($agentId) {
                $prefix = substr($agentId, 0, 3);
                if ($prefix === 'AGT') {
                    $role = 'agent';
                    $userId = $agentId;
                } elseif ($prefix === 'ADM') {
                    $role = 'admin';
                    $userId = $agentId;
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid agent_id format. Expected: AGTxxx or ADMxxx',
                    ], 400);
                }
            }

            // ===== GET SERVER-SIDE PRICING FROM VIEW =====
            $packageIds = $request->input('package_id');
            $serverPricing = null;
            
            if ($role && $userId) {
                try {
                    $serverPricing = $this->pricingService->getBulkPricesForItems($role, $userId, $packageIds);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengambil harga dari sistem',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

            // ===== PASS DATA TO SERVICE =====
            $data = $request->all();
            $data['_role'] = $role; // Internal field
            $data['_user_id'] = $userId; // Internal field
            $data['_server_pricing'] = $serverPricing; // Internal field
            
            $result = $this->paymentService->createBulkPayment($data);

            return response()->json($result, 201);
            
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat bulk payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/umroh/bulkpayment
     * 
     * Get bulk payment history for an agent
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getHistory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $agentId = $request->input('agent_id');
            $history = $this->paymentService->getHistory($agentId);

            return response()->json([
                'success' => true,
                'message' => 'History pembayaran berhasil diambil',
                'data' => $history,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil history pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/umroh/bulkpayment/detail
     * 
     * Get detail of bulk payment order
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getDetail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'agent_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $paymentId = $request->input('id');
            $agentId = $request->input('agent_id');
            
            $detail = $this->paymentService->getDetail($paymentId, $agentId);

            return response()->json([
                'success' => true,
                'message' => 'Detail pembayaran berhasil diambil',
                'data' => $detail,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/umroh/payment/local-detail
     * 
     * Get payment detail from local database (not from external API)
     * Use this for invoice to get the updated status from local DB
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getLocalDetail(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required', // Allow both string and integer
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $paymentId = $request->input('id');
            $detail = $this->paymentService->getLocalPaymentDetail($paymentId);

            return response()->json($detail);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/umroh/payment
     * 
     * Create individual payment order (untuk homepage / public user / toko agent)
     * 
     * Request body:
     * - payment_method: QRIS atau SALDO
     * - detail: optional (JSON string untuk scheduled time, dll)
     * - ref_code: required (agent_id untuk store publik, atau '0' untuk homepage)
     * - msisdn: nomor HP (STRING)
     * - package_id: package ID (STRING)
     * - price: optional (IGNORED - akan diambil dari VIEW server-side jika agent_id valid)
     * - agent_id: AGTxxx (untuk toko agent, optional)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function createIndividualPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|in:QRIS,SALDO,qris,saldo',
            'detail' => 'nullable|string',
            'ref_code' => 'required|string|max:50',
            'msisdn' => 'required|string', // STRING untuk individual
            'package_id' => 'required|string', // STRING untuk individual
            'price' => 'nullable|numeric|min:0', // Optional (untuk backward compatibility)
            'agent_id' => 'nullable|string|max:20', // AGTxxx untuk toko agent
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // ===== DETECT AGENT & GET STORE PRICING =====
            $agentId = $request->input('agent_id') ?? $request->input('ref_code');
            $packageId = $request->input('package_id');
            $storePricing = null;
            
            // Jika agent_id valid (AGTxxx), ambil pricing dari VIEW
            if ($agentId && str_starts_with($agentId, 'AGT')) {
                try {
                    $storePricing = $this->pricingService->getStorePriceForItem($agentId, $packageId);
                    
                    if (!$storePricing) {
                        return response()->json([
                            'success' => false,
                            'message' => "Package {$packageId} tidak tersedia di toko {$agentId}",
                        ], 404);
                    }
                    
                    Log::info('✅ Store pricing retrieved from VIEW', [
                        'agent_id' => $agentId,
                        'package_id' => $packageId,
                        'toko_harga_jual' => $storePricing['toko_harga_jual'],
                    ]);
                    
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengambil harga dari sistem',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            } elseif ($agentId && !str_starts_with($agentId, 'AGT')) {
                // Jika ref_code/agent_id tidak valid tapi ada, warning saja (fallback ke client price)
                Log::warning('⚠️ Invalid agent_id format for store pricing', [
                    'agent_id' => $agentId,
                    'expected' => 'AGTxxx',
                ]);
            }

            // ===== PASS DATA TO SERVICE =====
            $data = $request->all();
            $data['_store_pricing'] = $storePricing; // Internal field
            $data['_agent_id'] = str_starts_with($agentId, 'AGT') ? $agentId : null; // Internal field
            
            $result = $this->paymentService->createIndividualPayment($data);

            return response()->json($result, 201);
            
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat individual payment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/umroh/payment/status
     * 
     * Get payment status (for polling)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getPaymentStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $paymentId = $request->input('id');
            $result = $this->paymentService->getPaymentStatus($paymentId);

            // Service already returns properly formatted response
            return response()->json($result);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/umroh/payment/verify
     * 
     * Manually verify payment (trigger check)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $paymentId = $request->input('id');
            $result = $this->paymentService->verifyPayment($paymentId);

            return response()->json($result);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal verifikasi pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/umroh/payment/callback
     * 
     * Payment callback from payment gateway (QRIS)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function paymentCallback(Request $request): JsonResponse
    {
        // TODO: Implement callback verification from QRIS provider
        // This should verify the signature/token from the payment provider
        
        $paymentId = $request->input('payment_id');
        $qrisRrn = $request->input('rrn');
        $status = $request->input('status');

        if ($status === 'success' && $paymentId) {
            $result = $this->paymentService->markPaymentSuccess($paymentId, $qrisRrn);
            
            return response()->json([
                'success' => $result,
                'message' => $result ? 'Payment processed' : 'Failed to process payment',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid callback data',
        ], 400);
    }

    /**
     * GET /api/pembayaran/{external_payment_id}/status
     * 
     * Get payment status by external_payment_id (for tracking order)
     * 
     * @param string $externalPaymentId
     * @return JsonResponse
     */
    public function getPaymentStatusByExternalId(string $externalPaymentId): JsonResponse
    {
        try {
            Log::info('Tracking payment by external_payment_id', [
                'external_payment_id' => $externalPaymentId
            ]);

            // Find payment by external_payment_id
            $payment = \App\Models\Pembayaran::where('external_payment_id', $externalPaymentId)->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan',
                ], 404);
            }

            // Return payment status
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $payment->id,
                    'external_payment_id' => $payment->external_payment_id,
                    'status' => $payment->status,
                    'status_pembayaran' => $payment->status_pembayaran,
                    'metode_pembayaran' => $payment->metode_pembayaran,
                    'total_pembayaran' => $payment->total_pembayaran,
                    'created_at' => $payment->created_at,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Error tracking payment', [
                'external_payment_id' => $externalPaymentId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melacak pembayaran',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
