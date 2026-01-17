<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\Freelance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReferralRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_frontend_for_active_affiliate(): void
    {
        config(['app.frontend_url' => 'http://localhost:5173']);

        $affiliate = Affiliate::factory()->create([
            'is_active' => true,
            'link_referral' => 'john123',
        ]);

        $response = $this->get('/r/john123');

        $response->assertStatus(302);
        $response->assertRedirect('http://localhost:5173/ref.html?ref=affiliate%3A' . $affiliate->id);
    }

    public function test_redirects_to_frontend_for_active_freelance_when_affiliate_not_found(): void
    {
        config(['app.frontend_url' => 'http://localhost:5173']);

        $freelance = Freelance::factory()->create([
            'is_active' => true,
            'link_referral' => 'john123',
        ]);

        $response = $this->get('/r/john123');

        $response->assertStatus(302);
        $response->assertRedirect('http://localhost:5173/ref.html?ref=freelance%3A' . $freelance->id);
    }

    public function test_returns_404_for_inactive_referral(): void
    {
        Affiliate::factory()->create([
            'is_active' => false,
            'link_referral' => 'inactive',
        ]);

        $response = $this->get('/r/inactive');

        $response->assertStatus(404);
        $response->assertSee('Referral tidak valid');
    }

    public function test_returns_404_for_unknown_referral(): void
    {
        $response = $this->get('/r/notfound');

        $response->assertStatus(404);
        $response->assertSee('Referral tidak valid');
    }

    public function test_affiliate_has_priority_when_codes_collide(): void
    {
        config(['app.frontend_url' => 'http://localhost:5173']);

        $affiliate = Affiliate::factory()->create([
            'is_active' => true,
            'link_referral' => 'dup',
        ]);

        Freelance::factory()->create([
            'is_active' => true,
            'link_referral' => 'dup',
        ]);

        $response = $this->get('/r/dup');

        $response->assertStatus(302);
        $response->assertRedirect('http://localhost:5173/ref.html?ref=affiliate%3A' . $affiliate->id);
    }
}
