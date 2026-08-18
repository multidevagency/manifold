<?php

namespace Manifold\Cms\Fields;

class Blocks extends Json
{
    /** @var array<string, Field[]> blockType => fields */
    protected array $blocks = [];

    /** @param array<string, Field[]> $blocks */
    public function blocks(array $blocks): static
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function type(): string
    {
        return 'blocks';
    }

    public function blockTypes(): array
    {
        return $this->blocks;
    }

    public function toSchema(): array
    {
        return parent::toSchema() + [
            'blocks' => collect($this->blocks)
                ->map(fn (array $fields) => array_map(fn (Field $f) => $f->toSchema() + ['column' => $f->column()], $fields))
                ->all(),
        ];
    }

    public function validationRules(bool $updating): array
    {
        $rules = parent::validationRules($updating);
        $rules[] = function (string $attribute, mixed $value, \Closure $fail) {
            $rows = is_string($value) ? json_decode($value, true) : $value;
            if ($rows === null) {
                return;
            }
            if (! array_is_list($rows)) {
                $fail("The {$attribute} field must be a list of blocks.");

                return;
            }
            foreach ($rows as $row) {
                if (! isset($row['blockType']) || ! isset($this->blocks[$row['blockType']])) {
                    $fail("Each block in {$attribute} needs a valid blockType.");

                    return;
                }
            }
        };

        return $rules;
    }
}
