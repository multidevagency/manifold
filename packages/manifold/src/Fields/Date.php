<?php

namespace Manifold\Cms\Fields;

use Illuminate\Support\Carbon;

class Date extends Field
{
    public function type(): string
    {
        return 'date';
    }

    public function sqlType(): string
    {
        return 'date';
    }

    protected function baseStatement(): string
    {
        return "\$table->date('{$this->name}')";
    }

    public function fromDatabase(mixed $value): mixed
    {
        return $value === null ? null : Carbon::parse($value)->toDateString();
    }

    public function toDatabase(mixed $value): mixed
    {
        return $value === null || $value === '' ? null : Carbon::parse($value)->toDateString();
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['date']);
    }
}
