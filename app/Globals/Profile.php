<?php

namespace App\Globals;

use Manifold\Cms\Fields\ArrayField;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;
use Manifold\Cms\Globals\GlobalDocument;

class Profile extends GlobalDocument
{
    protected string $slug = 'profile';

    public function fields(): array
    {
        return [
            Text::make('name')->required(),
            Text::make('headline')->help('The big line on the homepage.'),
            Textarea::make('intro'),
            Text::make('email'),
            Text::make('github'),
            ArrayField::make('skills')->of([
                Text::make('name')->required(),
            ]),
        ];
    }

    public function access(): array
    {
        return ['read' => fn ($user) => true];
    }
}
