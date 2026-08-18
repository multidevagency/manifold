<?php

namespace Manifold\Cms\Fields;

abstract class Layout extends Field
{
    protected static int $counter = 0;

    protected array $fields = [];

    public static function with(array $fields): static
    {
        $instance = static::make(strtolower(class_basename(static::class)).'_'.(++static::$counter));
        $instance->fields = $fields;

        return $instance;
    }

    public function hasColumn(): bool
    {
        return false;
    }

    public function children(): array
    {
        return $this->fields;
    }

    public function sqlType(): string
    {
        return '';
    }

    protected function baseStatement(): string
    {
        return '';
    }
}
