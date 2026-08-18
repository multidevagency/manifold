<?php

namespace Manifold\Cms\Fields;

class RichText extends Field
{
    public function type(): string
    {
        return 'richtext';
    }

    public function sqlType(): string
    {
        return 'longtext';
    }

    protected function baseStatement(): string
    {
        return "\$table->longText('{$this->name}')";
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['string']);
    }
}
