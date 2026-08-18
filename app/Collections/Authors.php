<?php

namespace App\Collections;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\Select;
use Manifold\Cms\Fields\Slug;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;

class Authors extends Collection
{
    protected string $slug = 'authors';

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title'),
            Select::make('role')->options(['writer', 'editor', 'admin'])->required(),
            Textarea::make('bio'),
        ];
    }
}
