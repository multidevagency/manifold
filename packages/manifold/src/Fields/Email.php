<?php

namespace Manifold\Cms\Fields;

class Email extends Text
{
    public function type(): string
    {
        return 'email';
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), ['email']);
    }
}
