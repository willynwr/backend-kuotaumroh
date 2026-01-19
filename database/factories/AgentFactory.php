<?php

namespace Database\Factories;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentFactory extends Factory
{
    protected $model = Agent::class;

    public function definition(): array
    {
        return [
            'affiliate_id' => 1,
            'email' => $this->faker->unique()->safeEmail(),
            'nama_pic' => $this->faker->name(),
            'no_hp' => $this->faker->unique()->numerify('08##########'),
            'nama_travel' => $this->faker->company(),
            'jenis_travel' => $this->faker->randomElement(['PT', 'CV', 'Perusahaan Perorangan']),
            'total_traveller' => $this->faker->numberBetween(10, 1000),
            'provinsi' => $this->faker->state(),
            'kabupaten_kota' => $this->faker->city(),
            'alamat_lengkap' => $this->faker->address(),
            'logo' => null,
            'surat_ppiu' => null,
            'status' => 'active',
            'is_active' => true,
        ];
    }
}
