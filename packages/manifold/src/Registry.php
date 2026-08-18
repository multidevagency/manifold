<?php

namespace Manifold\Cms;

use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Globals\GlobalDocument;

class Registry
{
    /** @var array<string, Collection> */
    protected array $collections = [];

    /** @var array<string, GlobalDocument> */
    protected array $globals = [];

    public function __construct(array $collectionClasses, array $globalClasses = [])
    {
        foreach ($collectionClasses as $class) {
            $instance = new $class;
            $this->collections[$instance->slug()] = $instance;
        }
        foreach ($globalClasses as $class) {
            $instance = new $class;
            $this->globals[$instance->slug()] = $instance;
        }
    }

    public function getGlobal(string $slug): ?GlobalDocument
    {
        return $this->globals[$slug] ?? null;
    }

    /** @return array<string, GlobalDocument> */
    public function allGlobals(): array
    {
        return $this->globals;
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
