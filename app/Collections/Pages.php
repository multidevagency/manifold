<?php

namespace App\Collections;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\ArrayField;
use Manifold\Cms\Fields\Blocks;
use Manifold\Cms\Fields\Group;
use Manifold\Cms\Fields\Presets\Seo;
use Manifold\Cms\Fields\RichText;
use Manifold\Cms\Fields\Select;
use Manifold\Cms\Fields\Slug;
use Manifold\Cms\Fields\Tabs;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;
use Manifold\Cms\Fields\Ui;
use Manifold\Cms\Fields\Upload;

class Pages extends Collection
{
    protected string $slug = 'pages';

    public function access(): array
    {
        return ['read' => fn ($user) => true];
    }

    public function guestFilters(): array
    {
        return ['status' => 'published'];
    }

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title'),
            Select::make('status')->options(['draft', 'published'])->default('draft')->required(),
            Tabs::of([
                'Content' => [
                    Ui::note('The layout builder below renders on the frontend in order.'),
                    Group::make('hero')->fields([
                        Text::make('heading'),
                        Textarea::make('subheading'),
                        Upload::make('image'),
                    ]),
                    Blocks::make('layout')->blocks([
                        'content' => [RichText::make('body')],
                        'cta' => [Text::make('label')->required(), Text::make('url')->required()],
                        'media' => [Upload::make('file'), Text::make('caption')],
                    ]),
                    RichText::make('content'),
                ],
                'SEO' => Seo::fields(),
                'Extras' => [
                    ArrayField::make('faq')->of([
                        Text::make('question')->required(),
                        Textarea::make('answer'),
                    ]),
                ],
            ]),
        ];
    }
}
