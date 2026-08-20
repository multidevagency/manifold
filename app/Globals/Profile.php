<?php

namespace App\Globals;

use Manifold\Cms\Fields\ArrayField;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;
use Manifold\Cms\Fields\Upload;
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
            Textarea::make('bio')->help('Longer bio for the about page.'),
            Text::make('location'),
            Text::make('email'),
            Text::make('github'),
            Text::make('linkedin'),
            Upload::make('cv')->label('CV (PDF)'),
            ArrayField::make('skills')->of([
                Text::make('name')->required(),
            ]),
            ArrayField::make('experience')->of([
                Text::make('role')->required(),
                Text::make('company'),
                Text::make('period'),
                Textarea::make('description'),
            ]),
            ArrayField::make('education')->of([
                Text::make('name')->required(),
                Text::make('institution'),
                Text::make('period'),
            ]),
            ArrayField::make('languages')->of([
                Text::make('name')->required(),
                Text::make('level'),
            ]),
        ];
    }

    public function access(): array
    {
        return ['read' => fn ($user) => true];
    }
}
