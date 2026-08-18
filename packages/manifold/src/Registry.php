<?php

namespace Manifold\Cms;

use Manifold\Cms\Collections\Collection;

class Registry
{
    /** @var array<string, Collection> */
    protected array $collections = [];

    public function __construct(array $collectionClasses)
    {
        foreach ($collectionClasses as $class) {
            $instance = new $class;
            $this->collections[$instance->slug()] = $instance;
        }
    }

    public function get(string $slug): ?Collection
    {
        return $this->collections[$slug] ?? null;
    }

    /** @return array<string, Collection> */
    public function all(): array
    {
        return $this->collections;
    }
}
