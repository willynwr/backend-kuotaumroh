<?php

use App\Models\Agent;
use Illuminate\Support\Facades\Validator;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'test_unique_' . time() . '@example.com';
echo "Testing with email: $email\n";

try {
    // 1. Create agent
    $agent = Agent::create([
        'email' => $email,
        'nama_pic' => 'Test Agent',
        'no_hp' => '0812' . time(),
        'kategori_agent' => 'Referral',
        'provinsi' => 'Test Prov',
        'kabupaten_kota' => 'Test City',
        'alamat_lengkap' => 'Test Address'
    ]);
    echo "Agent created (ID: {$agent->id})\n";

    // 2. Test validation rules that should fail
    $rules = [
        'email' => 'required|email|unique:agents,email|unique:affiliates,email|unique:freelances,email'
    ];

    $validator = Validator::make(['email' => $email], $rules);

    if ($validator->fails()) {
        echo "Validation FAILED as expected. Errors: " . implode(', ', $validator->errors()->all()) . "\n";
    } else {
        echo "Validation PASSED (UNEXPECTED!)\n";
    }

    // Cleanup
    $agent->delete();
    echo "Agent deleted.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
