<?php

namespace App\Globals;

use Manifold\Cms\Fields\ArrayField;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;
use Manifold\Cms\Globals\GlobalDocument;

class Footer extends GlobalDocument
{
    protected string $slug = 'footer';

    public function fields(): array
    {
        return [
            Textarea::make('tagline'),
            ArrayField::make('links')->of([
                Text::make('label')->required(),
                Text::make('url')->required(),
            ]),
            Text::make('copyright'),
        ];
    }

    public function access(): array
    {
        return ['read' => fn ($user) => true];
    }
}
