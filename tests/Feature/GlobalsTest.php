<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalsTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): static
    {
        return $this->actingAs(User::factory()->create(), 'sanctum');
    }

    public function test_guests_can_read_public_globals(): void
    {
        $this->getJson('/api/manifold/globals/header')
            ->assertOk()
            ->assertJsonStructure(['data' => ['brand', 'nav']]);
    }

    public function test_guests_cannot_update_globals(): void
    {
        $this->patchJson('/api/manifold/globals/header', ['brand' => 'X'])->assertUnauthorized();
    }

    public function test_unknown_global_is_404(): void
    {
        $this->actingAsAdmin()->getJson('/api/manifold/globals/nonsense')->assertNotFound();
    }

    public function test_update_merges_and_round_trips(): void
    {
        $admin = $this->actingAsAdmin();

        $admin->patchJson('/api/manifold/globals/header', [
            'brand' => 'ACME',
            'nav' => [['label' => 'Home', 'url' => '/']],
        ])->assertOk()->assertJsonPath('data.brand', 'ACME');

        $admin->patchJson('/api/manifold/globals/header', ['brand' => 'ACME 2'])
            ->assertOk()
            ->assertJsonPath('data.brand', 'ACME 2')
            ->assertJsonPath('data.nav.0.label', 'Home');
    }
}
