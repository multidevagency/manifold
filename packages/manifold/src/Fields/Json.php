<?php

namespace Manifold\Cms\Fields;

class Json extends Field
{
    public function type(): string
    {
        return 'json';
    }

    public function sqlType(): string
    {
        return 'json';
    }

    protected function baseStatement(): string
    {
        return "\$table->json('{$this->name}')";
    }

    public function fromDatabase(mixed $value): mixed
    {
        return is_string($value) ? json_decode($value, true) : $value;
    }

    public function toDatabase(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_string($value) ? $value : json_encode($value);
    }

    public function validationRules(bool $updating): array
    {
        $rules = parent::validationRules($updating);
        $rules[] = function (string $attribute, mixed $value, \Closure $fail) {
            if (is_string($value) && json_decode($value) === null && trim($value) !== 'null') {
                $fail("The {$attribute} field must be valid JSON.");
            }
        };

        return $rules;
    }
}
