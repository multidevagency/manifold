<?php

namespace Manifold\Cms\Fields;

class Tabs extends Layout
{
    /** @param array<string, Field[]> $tabs label => fields */
    public static function of(array $tabs): static
    {
        $children = [];
        foreach ($tabs as $label => $fields) {
            $children[] = Tab::with($fields)->label($label);
        }

        return static::with($children);
    }

    public function type(): string
    {
        return 'tabs';
    }
}
