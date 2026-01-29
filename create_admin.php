<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Admin;

// Create admin user
$admin = Admin::create([
    'nama' => 'dev',
    'email' => 'wildanwhat@gmail.com',
    'no_wa' => '081234567890'
]);

echo "Admin created successfully!\n";
echo "ID: " . $admin->id . "\n";
echo "Email: " . $admin->email . "\n";
echo "Nama: " . $admin->nama . "\n";
