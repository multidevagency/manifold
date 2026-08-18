<?php

namespace Manifold\Cms\Fields;

class Collapsible extends Layout
{
    public static function with(array $fields, ?string $label = null): static
    {
        $instance = parent::with($fields);
        if ($label) {
            $instance->label($label);
        }

        return $instance;
    }

    public function type(): string
    {
        return 'collapsible';
    }
}
