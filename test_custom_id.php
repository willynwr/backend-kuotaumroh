<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TEST CUSTOM ID GENERATION ===\n\n";

// Test Agent
$agent = new App\Models\Agent();
echo "Agent ID: " . $agent->generateCustomId() . "\n";

// Test Affiliate
$affiliate = new App\Models\Affiliate();
echo "Affiliate ID: " . $affiliate->generateCustomId() . "\n";

// Test Freelance
$freelance = new App\Models\Freelance();
echo "Freelance ID: " . $freelance->generateCustomId() . "\n";

// Test Pesanan
$pesanan = new App\Models\Pesanan();
echo "Pesanan ID: " . $pesanan->generateCustomId() . "\n";

// Test Pembayaran
$pembayaran = new App\Models\Pembayaran();
echo "Pembayaran ID: " . $pembayaran->generateCustomId() . "\n";

// Test Rekening
$rekening = new App\Models\Rekening();
echo "Rekening ID: " . $rekening->generateCustomId() . "\n";

// Test Reward
$reward = new App\Models\Reward();
echo "Reward ID: " . $reward->generateCustomId() . "\n";

// Test Withdraw
$withdraw = new App\Models\Withdraw();
echo "Withdraw ID: " . $withdraw->generateCustomId() . "\n";

echo "\n=== TEST CREATE NEW RECORDS ===\n\n";

// Create Affiliate
$newAffiliate = App\Models\Affiliate::create([
    'nama' => 'Test Affiliate',
    'email' => 'test@affiliate.com',
    'no_wa' => '081234567890',
    'provinsi' => 'DKI Jakarta',
    'kab_kota' => 'Jakarta Selatan',
    'alamat_lengkap' => 'Jl. Test No. 1',
    'date_register' => now(),
    'is_active' => true,
    'link_referral' => 'aff-test-001',
]);
echo "Created Affiliate with ID: " . $newAffiliate->id . "\n";

// Create Freelance
$newFreelance = App\Models\Freelance::create([
    'nama' => 'Test Freelance',
    'email' => 'test@freelance.com',
    'no_wa' => '081234567891',
    'provinsi' => 'DKI Jakarta',
    'kab_kota' => 'Jakarta Selatan',
    'alamat_lengkap' => 'Jl. Test No. 2',
    'date_register' => now(),
    'is_active' => true,
    'link_referral' => 'frl-test-001',
]);
echo "Created Freelance with ID: " . $newFreelance->id . "\n";

// Create Agent
$newAgent = App\Models\Agent::create([
    'email' => 'test@agent.com',
    'nama_pic' => 'Test Agent',
    'no_hp' => '081234567892',
    'kategori_agent' => 'travel',
    'provinsi' => 'DKI Jakarta',
    'kabupaten_kota' => 'Jakarta Selatan',
    'alamat_lengkap' => 'Jl. Test No. 3',
    'affiliate_id' => $newAffiliate->id,
]);
echo "Created Agent with ID: " . $newAgent->id . "\n";

// Create Rekening
$newRekening = App\Models\Rekening::create([
    'agent_id' => $newAgent->id,
    'nama_rekening' => 'Test Rekening',
    'bank' => 'BCA',
    'nomor_rekening' => '1234567890',
]);
echo "Created Rekening with ID: " . $newRekening->id . "\n";

// Create Reward
$newReward = App\Models\Reward::create([
    'nama_reward' => 'Test Reward',
    'poin' => 100,
    'stok' => 10,
    'is_active' => true,
]);
echo "Created Reward with ID: " . $newReward->id . "\n";

echo "\n=== VERIFY RELATIONSHIPS ===\n\n";

// Refresh and check
$agent = App\Models\Agent::find($newAgent->id);
echo "Agent {$agent->id} affiliate_id: {$agent->affiliate_id}\n";

if ($agent->affiliate) {
    echo "Agent's Affiliate Name: " . $agent->affiliate->nama . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
