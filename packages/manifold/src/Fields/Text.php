<?php

namespace Manifold\Cms\Fields;

class Text extends Field
{
    protected int $maxLength = 255;

    public function maxLength(int $length): static
    {
        $this->maxLength = $length;

        return $this;
    }

    public function type(): string
    {
        return 'text';
    }

    public function sqlType(): string
    {
        return 'varchar';
    }

    protected function baseStatement(): string
    {
        return "\$table->string('{$this->name}', {$this->maxLength})";
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['string', 'max:'.$this->maxLength]);
    }
}
