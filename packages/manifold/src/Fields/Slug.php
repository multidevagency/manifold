<?php

namespace Manifold\Cms\Fields;

use Illuminate\Support\Str;

class Slug extends Field
{
    protected ?string $from = null;

    public static function make(string $name): static
    {
        return parent::make($name)->unique();
    }

    public function from(string $field): static
    {
        $this->from = $field;

        return $this;
    }

    public function sourceField(): ?string
    {
        return $this->from;
    }

    public function type(): string
    {
        return 'slug';
    }

    public function sqlType(): string
    {
        return 'varchar';
    }

    protected function baseStatement(): string
    {
        return "\$table->string('{$this->name}')";
    }

    public function toDatabase(mixed $value): mixed
    {
        return $value === null ? null : Str::slug($value);
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['string', 'max:255']);
    }

    public function toSchema(): array
    {
        return parent::toSchema() + array_filter(['from' => $this->from]);
    }
}
