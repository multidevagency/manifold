<?php

namespace Manifold\Cms\Fields;

class Point extends Field
{
    public function type(): string
    {
        return 'point';
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
        $point = is_string($value) ? json_decode($value, true) : $value;

        return json_encode(['lat' => (float) ($point['lat'] ?? 0), 'lng' => (float) ($point['lng'] ?? 0)]);
    }

    public function validationRules(bool $updating): array
    {
        $rules = parent::validationRules($updating);
        $rules[] = function (string $attribute, mixed $value, \Closure $fail) {
            $point = is_string($value) ? json_decode($value, true) : $value;
            if ($point !== null && (! is_array($point) || ! isset($point['lat'], $point['lng'])
                || ! is_numeric($point['lat']) || ! is_numeric($point['lng'])
                || abs($point['lat']) > 90 || abs($point['lng']) > 180)) {
                $fail("The {$attribute} field must be a point with valid lat and lng.");
            }
        };

        return $rules;
    }
}
