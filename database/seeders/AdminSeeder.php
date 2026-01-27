<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing admins
        Admin::truncate();

        // Create admin users
        $admins = [
            [
                'nama' => 'Dev Admin',
                'email' => 'wildanwhat@gmail.com',
                'no_wa' => '081234567890'
            ],
            [
                'nama' => 'Super Admin',
                'email' => 'lingr68@gmail.com',
                'no_wa' => '081987654321'
            ]
        ];

        foreach ($admins as $adminData) {
            $admin = Admin::create($adminData);
            $this->command->info("Admin created: {$admin->id} - {$admin->email}");
        }

        $this->command->info('Admin seeder completed successfully!');
    }
}
