<?php

namespace Manifold\Cms\Fields;

class Boolean extends Field
{
    public static function make(string $name): static
    {
        return parent::make($name)->default(false)->required();
    }

    public function type(): string
    {
        return 'boolean';
    }

    public function sqlType(): string
    {
        return 'tinyint';
    }

    protected function baseStatement(): string
    {
        return "\$table->boolean('{$this->name}')";
    }

    public function fromDatabase(mixed $value): mixed
    {
        return $value === null ? null : (bool) $value;
    }

    public function toDatabase(mixed $value): mixed
    {
        return $value === null ? null : (bool) $value;
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['boolean']);
    }
}
