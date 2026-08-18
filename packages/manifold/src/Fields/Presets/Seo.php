<?php

namespace Manifold\Cms\Fields\Presets;

use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;

class Seo
{
    /** Spread into a collection's fields(): ...Seo::fields() */
    public static function fields(): array
    {
        return [
            Text::make('meta_title')->maxLength(70)
                ->help('Shown in search results and browser tabs. Falls back to the title.'),
            Textarea::make('meta_description')
                ->help('150-160 characters. Search engines and AI answers quote this.'),
        ];
    }
}
