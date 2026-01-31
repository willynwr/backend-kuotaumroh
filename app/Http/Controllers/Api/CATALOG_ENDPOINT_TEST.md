/**
 * TEST CATALOG ENDPOINT - Manual Testing Guide
 * 
 * Endpoint: GET /api/umroh/package atau GET /api/proxy/umroh/package
 */

// ============================================================================
// TEST 1: Affiliate Bulk Catalog
// ============================================================================

// Request:
GET /api/umroh/package?affiliate_id=AFT0001

// Expected Response:
// - HTTP 200
// - Array of packages with:
//   - price_app = bulk_harga_rekomendasi (coret)
//   - price = bulk_harga_beli (beli)
//   - profit_affiliate = bulk_potensi_profit

// Error Test (invalid format):
GET /api/umroh/package?affiliate_id=INVALID123

// Expected Response:
// - HTTP 400
// - { "success": false, "message": "Invalid affiliate_id format. Expected: AFTxxx" }


// ============================================================================
// TEST 2: Agent Bulk Catalog
// ============================================================================

// Request:
GET /api/umroh/package?agent_id=AGT0001

// Expected Response:
// - HTTP 200
// - Array of packages with:
//   - price_app = bulk_harga_rekomendasi (coret)
//   - price = bulk_harga_beli (beli)
//   - profit_agent = bulk_potensi_profit


// ============================================================================
// TEST 3: Agent Store Catalog (Public/Individu)
// ============================================================================

// Request:
GET /api/umroh/package?agent_id=AGT0001&context=store

// Expected Response:
// - HTTP 200
// - Array of packages with:
//   - price_app = toko_harga_coret (strikethrough)
//   - price = toko_harga_jual (customer bayar)
//   - hemat = toko_hemat
//   - profit_agent = mandiri_final_fee_travel
//   - profit_affiliate = mandiri_final_fee_affiliate


// ============================================================================
// TEST 4: Admin Bulk Catalog
// ============================================================================

// Request:
GET /api/umroh/package?agent_id=ADM0001

// Expected Response:
// - HTTP 200
// - Array of packages with:
//   - price_app = bulk_harga_rekomendasi (coret)
//   - price = bulk_harga_beli (beli)
//   - NO profit field (admin tidak dapat profit)

// Error Test (invalid format):
GET /api/umroh/package?agent_id=INVALID

// Expected Response:
// - HTTP 400
// - { "success": false, "message": "Invalid agent_id format. Expected: AGTxxx or ADMxxx" }


// ============================================================================
// TEST 5: Fallback Legacy (Backward Compatibility)
// ============================================================================

// Request (tanpa affiliate_id/agent_id):
GET /api/umroh/package?ref_code=bulk_umroh

// Expected Response:
// - HTTP 200
// - Array of packages dari tabel Produk lokal (legacy)
// - Harga: price_bulk, price_customer, harga_komersial (tapi INI SUDAH DEPRECATED)


// ============================================================================
// TEST 6: Proxy Route
// ============================================================================

// Request via proxy:
GET /api/proxy/umroh/package?affiliate_id=AFT0001

// Expected Response:
// - Same as direct route (forwarded to UmrohPaymentController)


// ============================================================================
// VALIDATION CHECKLIST
// ============================================================================

/**
 * ✅ 1. Response adalah array langsung (tidak wrapped { data: [...] })
 * ✅ 2. Field legacy ada: id, package_id, name, packageName, price, price_app, dll
 * ✅ 3. Harga sesuai context:
 *       - Bulk: price=bulk_harga_beli, price_app=bulk_harga_rekomendasi
 *       - Store: price=toko_harga_jual, price_app=toko_harga_coret
 * ✅ 4. Profit field ada untuk affiliate/agent, TIDAK ada untuk admin
 * ✅ 5. Validasi prefix AFT/AGT/ADM working (400 error jika invalid)
 * ✅ 6. Fallback legacy masih bisa dipakai (backward compatibility)
 * ✅ 7. Proxy route working
 */


// ============================================================================
// CURL COMMAND EXAMPLES
// ============================================================================

// Affiliate:
curl -X GET "http://localhost:8000/api/umroh/package?affiliate_id=AFT0001"

// Agent Bulk:
curl -X GET "http://localhost:8000/api/umroh/package?agent_id=AGT0001"

// Agent Store:
curl -X GET "http://localhost:8000/api/umroh/package?agent_id=AGT0001&context=store"

// Admin:
curl -X GET "http://localhost:8000/api/umroh/package?agent_id=ADM0001"

// Fallback:
curl -X GET "http://localhost:8000/api/umroh/package?ref_code=bulk_umroh"
