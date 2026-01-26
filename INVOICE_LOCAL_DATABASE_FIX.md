# Invoice Local Database Fix

## Problem
Invoice page was showing "Menunggu Pembayaran" error despite payment being successful because:
1. `markPaymentSuccess()` updates local database with `STATUS_SUCCESS = 'SUCCESS'`
2. Invoice was calling proxy endpoints (`/api/proxy/umroh/payment`) which query external tokodigi API
3. External tokodigi API doesn't reflect local database status updates immediately
4. Invoice received outdated status from tokodigi, not the updated 'SUCCESS' status from local DB

## Solution
Created new endpoint that queries local database instead of external API for invoice display.

### Changes Made

#### 1. BulkPaymentService.php
**File:** `app/Services/Payment/BulkPaymentService.php`

Added new method `getLocalPaymentDetail()`:
```php
/**
 * Get local payment detail from database
 * This returns data from the local database, not from external API
 * Use this for invoice where you need the updated status from local DB
 */
public function getLocalPaymentDetail(int $paymentId): array
{
    $payment = Pembayaran::find($paymentId);
    
    if (!$payment) {
        throw new \InvalidArgumentException('Pembayaran tidak ditemukan');
    }

    // Format response similar to external API but with local DB data
    return [
        'success' => true,
        'data' => [
            'payment_id' => $payment->id,
            'id' => $payment->id,
            'batch_id' => $payment->batch_id,
            'status' => $payment->status_pembayaran,
            'status_pembayaran' => $payment->status_pembayaran,
            // ... other fields from local database
        ],
    ];
}
```

#### 2. UmrohPaymentController.php
**File:** `app/Http/Controllers/Api/UmrohPaymentController.php`

Added new endpoint `getLocalDetail()`:
```php
/**
 * GET /api/umroh/payment/local-detail
 * 
 * Get payment detail from local database (not from external API)
 * Use this for invoice to get the updated status from local DB
 */
public function getLocalDetail(Request $request): JsonResponse
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
        $detail = $this->paymentService->getLocalPaymentDetail($paymentId);

        return response()->json($detail);
    } catch (\InvalidArgumentException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 404);
    }
}
```

#### 3. routes/api.php
Added new route:
```php
// GET /api/umroh/payment/local-detail?id=123
// Get payment detail from local database (with updated status)
Route::get('/payment/local-detail', [UmrohPaymentController::class, 'getLocalDetail']);
```

#### 4. api.js
**File:** `public/shared/api.js`

Added new function `getLocalPaymentDetail()`:
```javascript
/**
 * Get local payment detail from database (with updated status)
 * Use this for invoice to get the updated status after markPaymentSuccess()
 */
async function getLocalPaymentDetail(paymentId) {
  try {
    const response = await fetch(`${API_BASE}/umroh/payment/local-detail?id=${paymentId}`);
    return await response.json();
  } catch (error) {
    console.error('Error fetching local payment detail:', error);
    throw error;
  }
}
```

Updated `getInvoiceDetail()` to use local database:
```javascript
async function getInvoiceDetail(paymentId, agentId = null) {
  try {
    // Gunakan local database endpoint untuk mendapatkan status terbaru
    console.log('🔍 Fetching invoice from local database for ID:', paymentId);
    
    const localResponse = await getLocalPaymentDetail(paymentId);
    
    if (localResponse && localResponse.success && localResponse.data) {
      return {
        success: true,
        type: 'local', // Dari database lokal
        data: localResponse.data
      };
    }
    
    throw new Error('Payment not found in local database');
  } catch (error) {
    console.error('Error fetching invoice detail:', error);
    return {
      success: false,
      message: error.message || 'Gagal memuat invoice'
    };
  }
}
```

#### 5. invoice.blade.php
**File:** `resources/views/invoice.blade.php`

Already updated in previous fix - `mapStatus()` function now handles:
- Case-insensitive status matching
- Maps 'SUCCESS' → 'paid'
- Maps 'sukses', 'berhasil', 'completed' → 'paid'

## Data Flow (After Fix)

### Before Fix (WRONG):
1. User pays via QRIS
2. `verifyPayment()` checks tokodigi API
3. `markPaymentSuccess()` updates local DB: `status_pembayaran = 'SUCCESS'`
4. User opens invoice
5. Invoice calls `/api/proxy/umroh/payment` → tokodigi external API
6. tokodigi returns old status (not updated with local DB)
7. Invoice shows "Menunggu Pembayaran" ❌

### After Fix (CORRECT):
1. User pays via QRIS
2. `verifyPayment()` checks tokodigi API
3. `markPaymentSuccess()` updates local DB: `status_pembayaran = 'SUCCESS'`
4. User opens invoice
5. Invoice calls `/api/umroh/payment/local-detail` → **local database**
6. Returns data from `Pembayaran` model with `status = 'SUCCESS'`
7. `mapStatus('SUCCESS')` → 'paid'
8. Invoice displays correctly ✅

## Testing

After clearing cache:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

To test:
1. Make a payment using QRIS
2. Wait for payment to be successful
3. Click "Cek Status" to trigger `verifyPayment()`
4. Local database should update with `status_pembayaran = 'SUCCESS'`
5. Click invoice button
6. Invoice should now show "Sukses" with green badge ✅
7. Invoice details should be displayed (no "Menunggu Pembayaran" error)

## API Endpoints Summary

### Old Endpoints (Still Available for Checkout):
- `GET /api/proxy/umroh/payment?id={id}` - Proxy to tokodigi external API
- `GET /api/proxy/umroh/bulkpayment/detail?id={id}&agent_id={agent_id}` - Proxy to tokodigi

### New Endpoint (For Invoice):
- `GET /api/umroh/payment/local-detail?id={id}` - Query local database with updated status ✅

## Benefits
1. ✅ Invoice shows accurate status from local database
2. ✅ No delay waiting for tokodigi to sync
3. ✅ Reliable data after `markPaymentSuccess()` updates
4. ✅ No CORS issues (direct backend API call)
5. ✅ Backward compatible (old proxy endpoints still work for checkout)
