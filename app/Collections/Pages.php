<?php

namespace App\Collections;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\RichText;
use Manifold\Cms\Fields\Select;
use Manifold\Cms\Fields\Slug;
use Manifold\Cms\Fields\Text;

class Pages extends Collection
{
    protected string $slug = 'pages';

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title'),
            RichText::make('content')->renamedFrom('body'),
            Text::make('meta_title'),
            Select::make('status')->options(['draft', 'published'])->default('draft')->required(),
        ];
    }
}
