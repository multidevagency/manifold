<?php

namespace Manifold\Cms\Fields;

class Relationship extends Field
{
    protected string $to = '';

    public static function make(string $name): static
    {
        return parent::make($name)->index();
    }

    public function to(string $collectionSlug): static
    {
        $this->to = $collectionSlug;

        return $this;
    }

    public function target(): string
    {
        return $this->to;
    }

    public function type(): string
    {
        return 'relationship';
    }

    public function sqlType(): string
    {
        return 'bigint';
    }

    public function column(): string
    {
        return $this->name.'_id';
    }

    protected function baseStatement(): string
    {
        return "\$table->unsignedBigInteger('{$this->column()}')";
    }

    public function fromDatabase(mixed $value): mixed
    {
        return $value === null ? null : (int) $value;
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['integer']);
    }

    public function toSchema(): array
    {
        return parent::toSchema() + ['to' => $this->to];
    }
}
