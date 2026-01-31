/**
 * CONTOH PEMAKAIAN: PackagePricingService
 * 
 * Guna: Query harga paket dari VIEW DB kuotaumroh sesuai role & context
 */

// ============================================================================
// CONTOH 1: Get Bulk Catalog untuk Affiliate
// ============================================================================

$pricingService = new PackagePricingService();
$affiliateId = 'AFT0001';

try {
    $catalog = $pricingService->getBulkCatalogForAffiliate($affiliateId);
    
    // Response:
    // [
    //     [
    //         'id' => 'R1-TSEL-001',
    //         'name' => 'Internet 10 Hari',
    //         'type' => 'TELKOMSEL',
    //         'days' => 10,
    //         'quota' => '5 GB',
    //         'price_app' => 245000,           // bulk_harga_rekomendasi (coret)
    //         'bulk_harga_rekomendasi' => 245000,
    //         'price' => 230000,               // bulk_harga_beli (beli)
    //         'bulk_harga_beli' => 230000,
    //         'profit_affiliate' => 15000,    // bulk_potensi_profit
    //         'bulk_potensi_profit' => 15000,
    //         ...legacy fields...
    //     ],
    //     ...
    // ]
} catch (\InvalidArgumentException $e) {
    // Invalid affiliate ID format
    return response()->json(['error' => $e->getMessage()], 400);
}

// ============================================================================
// CONTOH 2: Get Bulk Catalog untuk Agent
// ============================================================================

$agentId = 'AGT0001';

try {
    $catalog = $pricingService->getBulkCatalogForAgent($agentId);
    
    // Sama seperti affiliate, tapi pakai table v_pembelian_paket_travel_agent
    // profit_agent = bulk_potensi_profit
} catch (\InvalidArgumentException $e) {
    return response()->json(['error' => $e->getMessage()], 400);
}

// ============================================================================
// CONTOH 3: Get Store Catalog (Toko Publik) untuk Agent
// ============================================================================

try {
    $storeCatalog = $pricingService->getStoreCatalogForAgent($agentId);
    
    // Response:
    // [
    //     [
    //         'id' => 'R1-TSEL-001',
    //         'name' => 'Internet 10 Hari',
    //         'price_app' => 250000,           // toko_harga_coret (strikethrough)
    //         'toko_harga_coret' => 250000,
    //         'price' => 225000,               // toko_harga_jual (customer bayar)
    //         'toko_harga_jual' => 225000,
    //         'hemat' => 25000,                // toko_hemat
    //         'toko_hemat' => 25000,
    //         'profit_agent' => 10000,         // mandiri_final_fee_travel
    //         'mandiri_final_fee_travel' => 10000,
    //         'profit_affiliate' => 5000,      // mandiri_final_fee_affiliate
    //         'mandiri_final_fee_affiliate' => 5000,
    //         ...
    //     ],
    //     ...
    // ]
} catch (\InvalidArgumentException $e) {
    return response()->json(['error' => $e->getMessage()], 400);
}

// ============================================================================
// CONTOH 4: Get Harga untuk Multiple Items (saat create bulk payment)
// ============================================================================

$role = 'agent'; // atau 'affiliate', 'admin'
$userId = 'AGT0001';
$packageIds = ['R1-TSEL-001', 'R2-XL-002', 'R3-ISAT-003'];

try {
    $priceMap = $pricingService->getBulkPricesForItems($role, $userId, $packageIds);
    
    // Response:
    // [
    //     'R1-TSEL-001' => [
    //         'package_id' => 'R1-TSEL-001',
    //         'bulk_harga_beli' => 230000,
    //         'bulk_harga_rekomendasi' => 245000,
    //         'bulk_potensi_profit' => 15000,
    //     ],
    //     'R2-XL-002' => [...],
    //     'R3-ISAT-003' => [...],
    // ]
    
    // Validasi & ambil price untuk kirim ke external API
    $priceList = [];
    foreach ($packageIds as $pkgId) {
        if (!isset($priceMap[$pkgId])) {
            throw new \Exception("Package {$pkgId} not found in pricing");
        }
        $priceList[] = $priceMap[$pkgId]['bulk_harga_beli'];
    }
    
} catch (\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 400);
}

// ============================================================================
// CONTOH 5: Get Harga untuk Single Item (saat create individu payment)
// ============================================================================

$agentId = 'AGT0001';
$packageId = 'R1-TSEL-001';

try {
    $priceData = $pricingService->getStorePriceForItem($agentId, $packageId);
    
    if (!$priceData) {
        throw new \Exception("Package {$packageId} tidak tersedia di toko {$agentId}");
    }
    
    // Response:
    // [
    //     'package_id' => 'R1-TSEL-001',
    //     'toko_harga_coret' => 250000,
    //     'toko_harga_jual' => 225000,    // <-- Kirim ini ke external API sebagai price
    //     'toko_hemat' => 25000,
    //     'mandiri_final_fee_travel' => 10000,    // <-- Simpan ke detail_pesanan
    //     'mandiri_final_fee_affiliate' => 5000,  // <-- Simpan ke detail_pesanan
    // ]
    
    // Kirim ke external API
    $requestBody = [
        'price' => $priceData['toko_harga_jual'], // 225000
        ...
    ];
    
    // Simpan pricing detail
    $pricingDetail = [
        'package_id' => $priceData['package_id'],
        'toko_harga_coret' => $priceData['toko_harga_coret'],
        'toko_harga_jual' => $priceData['toko_harga_jual'],
        'toko_hemat' => $priceData['toko_hemat'],
        'mandiri_final_fee_travel' => $priceData['mandiri_final_fee_travel'],
        'mandiri_final_fee_affiliate' => $priceData['mandiri_final_fee_affiliate'],
    ];
    
} catch (\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 400);
}

// ============================================================================
// CONTOH 6: Role Detection & Validation
// ============================================================================

$userId = 'AFT0001'; // atau AGT, ADM

$role = $pricingService->detectRole($userId);
if (!$role) {
    return response()->json(['error' => 'Invalid user ID format'], 400);
}

// role = 'affiliate' | 'agent' | 'admin'
// Gunakan untuk determine catalog mana yang dipakai
