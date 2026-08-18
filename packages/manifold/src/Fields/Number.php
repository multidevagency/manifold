<?php

namespace Manifold\Cms\Fields;

class Number extends Field
{
    protected bool $decimal = false;

    public function decimal(bool $decimal = true): static
    {
        $this->decimal = $decimal;

        return $this;
    }

    public function type(): string
    {
        return 'number';
    }

    public function sqlType(): string
    {
        return $this->decimal ? 'decimal' : 'bigint';
    }

    protected function baseStatement(): string
    {
        return $this->decimal
            ? "\$table->decimal('{$this->name}', 12, 2)"
            : "\$table->bigInteger('{$this->name}')";
    }

    public function fromDatabase(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return $this->decimal ? (float) $value : (int) $value;
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['numeric']);
    }
}
