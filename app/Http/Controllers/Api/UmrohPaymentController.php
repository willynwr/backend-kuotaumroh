<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Payment\BulkPaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

/**
 * Umroh Payment API Controller
 * 
 * Handles all payment-related API endpoints for Umroh packages.
 */
class UmrohPaymentController extends Controller
{
    protected BulkPaymentService $paymentService;

    public function __construct(BulkPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * GET /api/umroh/package
     * 
     * Get catalog of Umroh packages
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getPackages(Request $request): JsonResponse
    {
        try {
            $refCode = $request->input('ref_code', 'bulk_umroh');
            
            $packages = $this->paymentService->getCatalog($refCode);

            // Return array langsung (tidak wrapped) untuk kompatibilitas dengan frontend
            return response()->json($packages);
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
            'price' => 'required|array|min:1', // Wajib ada price dari local
            'price.*' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->paymentService->createBulkPayment($request->all());

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
     * Create individual payment order (untuk homepage / public user)
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
            'price' => 'required|numeric|min:0', // Wajib ada price dari local
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->paymentService->createIndividualPayment($request->all());

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
}
