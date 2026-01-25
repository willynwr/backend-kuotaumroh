<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AffiliateSeeder::class,
            AgentSeeder::class,
            FreelanceSeeder::class,
            ProdukSeeder::class,
            CatalogUmrohAxisSeeder::class,
            CatalogUmrohByuSeeder::class,
            CatalogUmrohIsatSeeder::class,
            CatalogUmrohSfrenSeeder::class,
            CatalogUmrohTriSeeder::class,
            CatalogUmrohTselSeeder::class,
            CatalogUmrohXlSeeder::class,
            SamplePesananSeeder::class,
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
