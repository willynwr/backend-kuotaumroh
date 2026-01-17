<?php

namespace Database\Factories;

use App\Models\Freelance;
use Illuminate\Database\Eloquent\Factories\Factory;

class FreelanceFactory extends Factory
{
    protected $model = Freelance::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'no_wa' => $this->faker->unique()->e164PhoneNumber(),
            'provinsi' => $this->faker->state(),
            'kab_kota' => $this->faker->city(),
            'alamat_lengkap' => $this->faker->address(),
            'date_register' => $this->faker->date(),
            'is_active' => true,
            'link_referral' => $this->faker->unique()->slug(2),
            'ref_code' => $this->faker->unique()->bothify('ref########'),
        ];
    }
}
