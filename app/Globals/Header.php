<?php

namespace App\Globals;

use Manifold\Cms\Fields\ArrayField;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Globals\GlobalDocument;

class Header extends GlobalDocument
{
    protected string $slug = 'header';

    public function fields(): array
    {
        return [
            Text::make('brand')->required()->default('MANIFOLD'),
            ArrayField::make('nav')->of([
                Text::make('label')->required(),
                Text::make('url')->required(),
            ]),
        ];
    }

    public function access(): array
    {
        return ['read' => fn ($user) => true];
    }
}
