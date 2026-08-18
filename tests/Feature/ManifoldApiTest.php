<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManifoldApiTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): static
    {
        return $this->actingAs(User::factory()->create(), 'sanctum');
    }

    public function test_login_returns_token(): void
    {
        User::factory()->create(['email' => 'a@b.test', 'password' => bcrypt('secret123')]);

        $this->postJson('/api/manifold/auth/login', ['email' => 'a@b.test', 'password' => 'secret123'])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);
    }

    public function test_login_rejects_bad_credentials(): void
    {
        User::factory()->create(['email' => 'a@b.test', 'password' => bcrypt('secret123')]);

        $this->postJson('/api/manifold/auth/login', ['email' => 'a@b.test', 'password' => 'wrong'])
            ->assertStatus(422);
    }

    public function test_entries_require_authentication(): void
    {
        $this->getJson('/api/manifold/posts')->assertUnauthorized();
    }

    public function test_unknown_collection_is_404(): void
    {
        $this->actingAsAdmin()->getJson('/api/manifold/nonsense')->assertNotFound();
    }

    public function test_schema_describes_collections(): void
    {
        $this->actingAsAdmin()
            ->getJson('/api/manifold/schema')
            ->assertOk()
            ->assertJsonPath('collections.1.slug', 'posts')
            ->assertJsonPath('collections.1.relationships.category_id', 'categories');
    }

    public function test_create_applies_defaults_and_generates_slug(): void
    {
        $response = $this->actingAsAdmin()
            ->postJson('/api/manifold/posts', ['title' => 'Hello World'])
            ->assertCreated();

        $response->assertJsonPath('data.slug', 'hello-world')
            ->assertJsonPath('data.status', 'draft')
            ->assertJsonPath('data.featured', false);
    }

    public function test_duplicate_titles_get_suffixed_slugs(): void
    {
        $admin = $this->actingAsAdmin();
        $admin->postJson('/api/manifold/posts', ['title' => 'Same Title']);

        $admin->postJson('/api/manifold/posts', ['title' => 'Same Title'])
            ->assertCreated()
            ->assertJsonPath('data.slug', 'same-title-2');
    }

    public function test_create_validates_required_and_enum_fields(): void
    {
        $this->actingAsAdmin()
            ->postJson('/api/manifold/posts', ['status' => 'bogus'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status']);
    }

    public function test_partial_update_leaves_other_fields_alone(): void
    {
        $admin = $this->actingAsAdmin();
        $id = $admin->postJson('/api/manifold/posts', ['title' => 'Patch Me'])->json('data.id');

        $admin->patchJson("/api/manifold/posts/{$id}", ['status' => 'published'])
            ->assertOk()
            ->assertJsonPath('data.status', 'published')
            ->assertJsonPath('data.title', 'Patch Me');
    }

    public function test_relationship_accepts_field_name_and_stores_column(): void
    {
        $admin = $this->actingAsAdmin();
        $catId = $admin->postJson('/api/manifold/categories', ['name' => 'News'])->json('data.id');

        $admin->postJson('/api/manifold/posts', ['title' => 'Linked', 'category' => $catId])
            ->assertCreated()
            ->assertJsonPath('data.category_id', $catId);
    }

    public function test_list_filters_and_paginates(): void
    {
        $admin = $this->actingAsAdmin();
        foreach (range(1, 3) as $i) {
            $admin->postJson('/api/manifold/posts', ['title' => "Draft {$i}"]);
        }
        $admin->postJson('/api/manifold/posts', ['title' => 'Live one', 'status' => 'published']);

        $admin->getJson('/api/manifold/posts?filter[status]=draft&perPage=2')
            ->assertOk()
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('meta.lastPage', 2)
            ->assertJsonCount(2, 'data');
    }

    public function test_search_matches_title_field(): void
    {
        $admin = $this->actingAsAdmin();
        $admin->postJson('/api/manifold/posts', ['title' => 'Unrelated entry']);
        $admin->postJson('/api/manifold/posts', ['title' => 'Findable needle']);

        $admin->getJson('/api/manifold/posts?search=needle')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Findable needle');
    }

    public function test_delete_removes_entry(): void
    {
        $admin = $this->actingAsAdmin();
        $id = $admin->postJson('/api/manifold/posts', ['title' => 'Doomed'])->json('data.id');

        $admin->deleteJson("/api/manifold/posts/{$id}")->assertNoContent();
        $this->assertDatabaseMissing('mf_posts', ['id' => $id]);
        $admin->getJson("/api/manifold/posts/{$id}")->assertNotFound();
    }
}
