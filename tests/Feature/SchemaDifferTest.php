<?php

namespace Tests\Feature;

use App\Collections\Posts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Support\SchemaDiffer;
use Tests\TestCase;

class SchemaDifferTest extends TestCase
{
    use RefreshDatabase;

    protected SchemaDiffer $differ;

    protected function setUp(): void
    {
        parent::setUp();
        $this->differ = new SchemaDiffer;
    }

    public function test_missing_table_yields_create(): void
    {
        $collection = new class extends Collection
        {
            protected string $slug = 'widgets';

            public function fields(): array
            {
                return [Text::make('name')];
            }
        };

        $this->assertSame(['create' => true], $this->differ->diff($collection));
    }

    public function test_synced_collection_yields_empty_diff(): void
    {
        $this->assertSame([], $this->differ->diff(new Posts));
    }

    public function test_new_field_is_detected_as_add(): void
    {
        $collection = new class extends Posts
        {
            public function fields(): array
            {
                return [...parent::fields(), Text::make('subtitle')];
            }
        };

        $diff = $this->differ->diff($collection);

        $this->assertCount(1, $diff['add']);
        $this->assertSame('subtitle', $diff['add'][0]->column());
        $this->assertSame([], $diff['drop']);
    }

    public function test_removed_field_is_detected_as_drop(): void
    {
        $collection = new class extends Posts
        {
            public function fields(): array
            {
                return array_values(array_filter(parent::fields(), fn ($f) => $f->column() !== 'excerpt'));
            }
        };

        $this->assertSame(['excerpt'], $this->differ->diff($collection)['drop']);
    }

    public function test_declared_rename_is_detected_not_drop_plus_add(): void
    {
        $collection = new class extends Posts
        {
            public function fields(): array
            {
                return array_map(
                    fn ($f) => $f->column() === 'excerpt' ? Text::make('summary')->renamedFrom('excerpt') : $f,
                    parent::fields(),
                );
            }
        };

        $diff = $this->differ->diff($collection);

        $this->assertCount(1, $diff['rename']);
        $this->assertSame('summary', $diff['rename'][0]->column());
        $this->assertSame([], $diff['add']);
        $this->assertSame([], $diff['drop']);
    }
}
