<?php

namespace Manifold\Cms\Fields;

use Illuminate\Support\Carbon;

class DateTime extends Field
{
    public function type(): string
    {
        return 'datetime';
    }

    public function sqlType(): string
    {
        return 'datetime';
    }

    protected function baseStatement(): string
    {
        return "\$table->dateTime('{$this->name}')";
    }

    public function fromDatabase(mixed $value): mixed
    {
        return $value === null ? null : Carbon::parse($value)->toIso8601String();
    }

    public function toDatabase(mixed $value): mixed
    {
        return $value === null || $value === '' ? null : Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['date']);
    }
}
