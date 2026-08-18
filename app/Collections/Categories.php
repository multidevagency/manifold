<?php

namespace App\Collections;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\Join;
use Manifold\Cms\Fields\Slug;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;

class Categories extends Collection
{
    protected string $slug = 'categories';

    protected string $defaultSort = 'name';

    public function access(): array
    {
        return ['read' => fn ($user) => true];
    }

    public function fields(): array
    {
        return [
            Text::make('name')->required()->useAsTitle(),
            Slug::make('slug')->from('name'),
            Textarea::make('description'),
            Join::make('posts')->to('posts')->via('category'),
        ];
    }
}
