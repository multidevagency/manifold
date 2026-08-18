<?php

namespace Tests\Feature;

use App\Collections\Categories;
use App\Collections\Pages;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContainerFieldsTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): static
    {
        return $this->actingAs(User::factory()->create(), 'sanctum');
    }

    public function test_layout_fields_own_no_columns_but_their_children_do(): void
    {
        $columns = array_map(fn ($f) => $f->column(), (new Pages)->columnFields());

        $this->assertContains('hero', $columns);
        $this->assertContains('meta_title', $columns);
        $this->assertNotContains('tabs_1', $columns);
    }

    public function test_join_fields_own_no_column(): void
    {
        $this->assertNotContains('posts', array_map(fn ($f) => $f->column(), (new Categories)->columnFields()));
    }

    public function test_group_array_and_blocks_round_trip_as_json(): void
    {
        $page = $this->actingAsAdmin()->postJson('/api/manifold/pages', [
            'title' => 'Nested',
            'hero' => ['heading' => 'Hi', 'subheading' => null, 'image' => null],
            'faq' => [['question' => 'Q?', 'answer' => 'A.']],
            'layout' => [['blockType' => 'cta', 'label' => 'Go', 'url' => '/x']],
        ])->assertCreated()->json('data');

        $this->assertSame('Hi', $page['hero']['heading']);
        $this->assertSame('Q?', $page['faq'][0]['question']);
        $this->assertSame('cta', $page['layout'][0]['blockType']);
    }

    public function test_unknown_block_type_is_rejected(): void
    {
        $this->actingAsAdmin()->postJson('/api/manifold/pages', [
            'title' => 'Bad blocks',
            'layout' => [['blockType' => 'bogus']],
        ])->assertStatus(422)->assertJsonValidationErrors(['layout']);
    }

    public function test_guests_see_only_published_products(): void
    {
        $admin = $this->actingAsAdmin();
        $admin->postJson('/api/manifold/products', ['title' => 'Hidden', 'price' => 1]);
        $admin->postJson('/api/manifold/products', ['title' => 'Visible', 'price' => 2, 'status' => 'published']);

        $this->app['auth']->forgetGuards();

        $this->getJson('/api/manifold/products')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Visible');
    }

    public function test_upload_field_serializes_to_path_and_url(): void
    {
        $admin = $this->actingAsAdmin();
        $product = $admin->postJson('/api/manifold/products', [
            'title' => 'With image', 'price' => 5, 'image' => 'manifold/x.png',
        ])->assertCreated()->json('data');

        $this->assertSame('manifold/x.png', $product['image']['path']);
        $this->assertStringEndsWith('/storage/manifold/x.png', $product['image']['url']);
    }
}
