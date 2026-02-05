<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\Payment\PackagePricingService;
use Illuminate\Support\Facades\DB;

$pricingService = new PackagePricingService();

echo "╔════════════════════════════════════════════════════════════════════╗\n";
echo "║         PAKET CATALOG TEST - VERIFY 82 ITEMS PER ROLE              ║\n";
echo "╚════════════════════════════════════════════════════════════════════╝\n\n";

$allPassed = true;

// ====================
// TEST AGENT ROLES
// ====================
echo "=== AGENT BULK CATALOG ===\n\n";

$agents = [
    'AGT00001' => 'Super Host',
    'AGT00003' => 'Referral',
    'AGT00007' => 'Non Referral',
    'AGT00008' => 'Host',
];

foreach ($agents as $agentId => $kategori) {
    echo "Testing {$agentId} ({$kategori}):\n";
    
    try {
        $catalog = $pricingService->getBulkCatalogForAgent($agentId);
        
        $count = count($catalog);
        $status = ($count === 82) ? '✅' : '⚠️';
        echo "  {$status} Bulk Catalog Count: {$count}/82\n";
        
        if ($count > 0) {
            $first = $catalog[0];
            echo "  📦 First Package:\n";
            echo "     ID: " . ($first['package_id'] ?? 'N/A') . "\n";
            echo "     Name: " . ($first['name'] ?? 'N/A') . "\n";
            echo "     Price (harga_beli): Rp " . number_format($first['bulk_harga_beli'] ?? 0) . "\n";
            echo "     Profit: Rp " . number_format($first['bulk_potensi_profit'] ?? 0) . "\n";
            
            if ($pricingService->shouldAffiliateReceiveFee($agentId)) {
                echo "     Affiliate Fee: Rp " . number_format($first['bulk_final_fee_affiliate'] ?? 0) . "\n";
            } else {
                echo "     Affiliate Fee: Rp 0 (forced for this role)\n";
            }
        }
        
        if ($count !== 82) {
            $allPassed = false;
        }
    } catch (Exception $e) {
        echo "  ❌ ERROR: " . $e->getMessage() . "\n";
        $allPassed = false;
    }
    
    echo "\n";
}

// ====================
// TEST AGENT STORE
// ====================
echo "=== AGENT STORE CATALOG ===\n\n";

foreach ($agents as $agentId => $kategori) {
    if (!$pricingService->agentHasStore($agentId)) {
        echo "❌ {$agentId} ({$kategori}) - No store access (expected for Host)\n";
        echo "\n";
        continue;
    }
    
    echo "Testing {$agentId} ({$kategori}):\n";
    
    try {
        $catalog = $pricingService->getStoreCatalogForAgent($agentId);
        
        $count = count($catalog);
        $status = ($count === 82) ? '✅' : '⚠️';
        echo "  {$status} Store Catalog Count: {$count}/82\n";
        
        if ($count > 0) {
            $first = $catalog[0];
            echo "  🏪 First Package:\n";
            echo "     ID: " . ($first['package_id'] ?? 'N/A') . "\n";
            echo "     Name: " . ($first['name'] ?? 'N/A') . "\n";
            echo "     Price (toko_harga_jual): Rp " . number_format($first['toko_harga_jual'] ?? 0) . "\n";
            echo "     Fee (mandiri_final_fee_travel): Rp " . number_format($first['mandiri_final_fee_travel'] ?? 0) . "\n";
        }
        
        if ($count !== 82) {
            $allPassed = false;
        }
    } catch (Exception $e) {
        echo "  ❌ ERROR: " . $e->getMessage() . "\n";
        $allPassed = false;
    }
    
    echo "\n";
}

// ====================
// TEST AFFILIATES
// ====================
echo "=== AFFILIATE BULK CATALOG ===\n\n";

$affiliateIds = DB::table('v_pembelian_paket_agent_travel')
    ->select('affiliate_id')
    ->distinct()
    ->orderBy('affiliate_id')
    ->limit(5)
    ->pluck('affiliate_id')
    ->toArray();

foreach ($affiliateIds as $affiliateId) {
    echo "Testing {$affiliateId}:\n";
    
    try {
        $catalog = $pricingService->getBulkCatalogForAffiliate($affiliateId);
        
        $count = count($catalog);
        $status = ($count === 82) ? '✅' : '⚠️';
        echo "  {$status} Catalog Count: {$count}/82\n";
        
        if ($count > 0) {
            $first = $catalog[0];
            echo "  📦 First Package:\n";
            echo "     ID: " . ($first['package_id'] ?? 'N/A') . "\n";
            echo "     Name: " . ($first['name'] ?? 'N/A') . "\n";
            echo "     Price: Rp " . number_format($first['bulk_harga_beli'] ?? 0) . "\n";
        }
        
        if ($count !== 82) {
            $allPassed = false;
        }
    } catch (Exception $e) {
        echo "  ❌ ERROR: " . $e->getMessage() . "\n";
        $allPassed = false;
    }
    
    echo "\n";
}

// ====================
// TEST FALLBACK
// ====================
echo "=== FALLBACK TO PRODUK_DEFAULT ===\n\n";

echo "Testing non-existent affiliate (AFT99999):\n";

try {
    $catalog = $pricingService->getBulkCatalogForAffiliate('AFT99999');
    
    $count = count($catalog);
    $status = ($count === 82) ? '✅' : '⚠️';
    echo "  {$status} Fallback Catalog Count: {$count}/82 (from produk_default)\n";
    
    if ($count > 0) {
        $first = $catalog[0];
        echo "  📦 First Package (from produk_default):\n";
        echo "     ID: " . ($first['package_id'] ?? 'N/A') . "\n";
        echo "     Name: " . ($first['name'] ?? 'N/A') . "\n";
        echo "     Price: Rp " . number_format($first['bulk_harga_beli'] ?? 0) . "\n";
    }
    
    if ($count !== 82) {
        $allPassed = false;
    }
} catch (Exception $e) {
    echo "  ❌ ERROR: " . $e->getMessage() . "\n";
    $allPassed = false;
}

echo "\n";

// ====================
// SUMMARY
// ====================
echo "╔════════════════════════════════════════════════════════════════════╗\n";
if ($allPassed) {
    echo "║  ✅ ALL CATALOG TESTS PASSED!                                     ║\n";
    echo "║                                                                    ║\n";
    echo "║  All agents and affiliates have 82 pakets:                         ║\n";
    echo "║  • Super Host (Bulk only) - 82 items                              ║\n";
    echo "║  • Referral (Bulk + Store) - 82 items each                        ║\n";
    echo "║  • Non Referral (Bulk + Store) - 82 items each                    ║\n";
    echo "║  • Host (Bulk only, no store) - 82 items                          ║\n";
    echo "║  • Affiliates (via agent VIEW) - 82 items                         ║\n";
    echo "║  • Fallback to produk_default - 82 items                          ║\n";
} else {
    echo "║  ⚠️ SOME TESTS SHOWED WARNINGS - REVIEW ABOVE                     ║\n";
}
echo "╚════════════════════════════════════════════════════════════════════╝\n";

exit($allPassed ? 0 : 1);
