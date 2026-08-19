<?php

namespace App\Collections;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\ArrayField;
use Manifold\Cms\Fields\Boolean;
use Manifold\Cms\Fields\Number;
use Manifold\Cms\Fields\Presets\Seo;
use Manifold\Cms\Fields\RichText;
use Manifold\Cms\Fields\Select;
use Manifold\Cms\Fields\Slug;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;
use Manifold\Cms\Fields\Upload;

class CaseStudies extends Collection
{
    protected string $slug = 'case-studies';

    protected string $defaultSort = 'sort_order';

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title'),
            Text::make('tagline')->help('One line under the title, e.g. "Code-first headless CMS for Laravel + Nuxt".'),
            Select::make('category')->options([
                'product' => 'Product',
                'platform' => 'Platform',
                'ai' => 'AI',
                'client-work' => 'Client work',
            ])->default('product')->required(),
            Text::make('stack')->help('Comma-separated, e.g. "Laravel, Nuxt, Next.js".'),
            Number::make('year')->default(2026),
            Boolean::make('featured'),
            Number::make('sort_order')->default(99),
            Textarea::make('summary')->required(),
            RichText::make('body'),
            ArrayField::make('metrics')->of([
                Text::make('value')->required()->help('e.g. "530k"'),
                Text::make('label')->required()->help('e.g. "products in catalog"'),
            ]),
            Upload::make('hero'),
            Text::make('repo_url')->label('Repository URL'),
            Text::make('live_url')->label('Live URL'),
            Select::make('status')->options(['draft', 'published'])->default('published')->required(),
            ...Seo::fields(),
        ];
    }

    public function access(): array
    {
        return ['read' => fn ($user) => true];
    }

    public function guestFilters(): array
    {
        return ['status' => 'published'];
    }

    public function previewUrl(): ?string
    {
        return env('MANIFOLD_PREVIEW_URL', 'http://localhost:3002').'/work/{slug}';
    }
}
