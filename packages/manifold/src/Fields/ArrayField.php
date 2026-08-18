<?php

namespace Manifold\Cms\Fields;

class ArrayField extends Json
{
    protected array $fields = [];

    /** @param Field[] $fields the shape of each row */
    public function of(array $fields): static
    {
        $this->fields = $fields;

        return $this;
    }

    public function children(): array
    {
        return $this->fields;
    }

    public function type(): string
    {
        return 'array';
    }

    public function validationRules(bool $updating): array
    {
        $rules = parent::validationRules($updating);
        $rules[] = function (string $attribute, mixed $value, \Closure $fail) {
            $rows = is_string($value) ? json_decode($value, true) : $value;
            if ($rows !== null && ! array_is_list($rows)) {
                $fail("The {$attribute} field must be a list of rows.");
            }
        };

        return $rules;
    }
}
