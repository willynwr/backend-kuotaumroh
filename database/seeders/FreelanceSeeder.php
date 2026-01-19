<?php

namespace Database\Seeders;

use App\Models\Freelance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FreelanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Freelance::factory()
            ->count(10)
            ->create();
    }
}
