<?php

namespace App\Collections;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\Boolean;
use Manifold\Cms\Fields\DateTime;
use Manifold\Cms\Fields\Number;
use Manifold\Cms\Fields\Relationship;
use Manifold\Cms\Fields\RichText;
use Manifold\Cms\Fields\Select;
use Manifold\Cms\Fields\Slug;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;

class Posts extends Collection
{
    protected string $slug = 'posts';

    protected string $defaultSort = '-published_at';

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title')->help('Leave empty to generate from the title.'),
            Textarea::make('excerpt'),
            RichText::make('body'),
            Select::make('status')->options(['draft', 'review', 'published'])->default('draft')->required()->index(),
            Relationship::make('category')->to('categories'),
            Boolean::make('featured'),
            Number::make('reading_time')->label('Reading time (min)'),
            DateTime::make('published_at')->index(),
        ];
    }

    public function access(): array
    {
        return [
            'read' => fn ($user) => true,
            'create' => fn ($user) => $user !== null,
            'update' => fn ($user) => $user !== null,
            'delete' => fn ($user) => $user !== null,
        ];
    }

    public function guestFilters(): array
    {
        return ['status' => 'published'];
    }

    public function previewUrl(): ?string
    {
        return env('MANIFOLD_PREVIEW_URL', 'http://localhost:3001').'/posts/{slug}';
    }
}
