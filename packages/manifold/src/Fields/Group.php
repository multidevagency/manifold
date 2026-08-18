<?php

namespace Manifold\Cms\Fields;

class Group extends Json
{
    protected array $fields = [];

    /** @param Field[] $fields */
    public function fields(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function children(): array
    {
        return $this->fields;
    }

    public function type(): string
    {
        return 'group';
    }
}
