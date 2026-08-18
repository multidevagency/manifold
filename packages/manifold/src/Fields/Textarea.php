<?php

namespace Manifold\Cms\Fields;

class Textarea extends Field
{
    public function type(): string
    {
        return 'textarea';
    }

    public function sqlType(): string
    {
        return 'text';
    }

    protected function baseStatement(): string
    {
        return "\$table->text('{$this->name}')";
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['string']);
    }
}
