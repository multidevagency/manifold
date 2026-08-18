<?php

namespace App\Collections;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\ArrayField;
use Manifold\Cms\Fields\Boolean;
use Manifold\Cms\Fields\Number;
use Manifold\Cms\Fields\Presets\Seo;
use Manifold\Cms\Fields\Relationship;
use Manifold\Cms\Fields\RichText;
use Manifold\Cms\Fields\Select;
use Manifold\Cms\Fields\Slug;
use Manifold\Cms\Fields\Text;
use Manifold\Cms\Fields\Textarea;
use Manifold\Cms\Fields\Upload;

class Products extends Collection
{
    protected string $slug = 'products';

    public function fields(): array
    {
        return [
            Text::make('title')->required()->useAsTitle(),
            Slug::make('slug')->from('title'),
            Textarea::make('excerpt'),
            RichText::make('description'),
            Upload::make('image'),
            Number::make('price')->decimal()->required()->help('In EUR.'),
            Select::make('status')->options(['draft', 'published'])->default('draft')->required()->index(),
            Boolean::make('in_stock')->default(true),
            Relationship::make('category')->to('categories'),
            ArrayField::make('variants')->of([
                Text::make('name')->required()->help('e.g. "Size M / Black"'),
                Text::make('sku'),
                Number::make('price')->decimal()->help('Overrides the base price.'),
                Boolean::make('in_stock')->default(true),
            ]),
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
}
