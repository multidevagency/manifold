<?php

namespace Manifold\Cms\Fields;

class Code extends Textarea
{
    protected string $language = 'plaintext';

    public function language(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function type(): string
    {
        return 'code';
    }

    public function sqlType(): string
    {
        return 'longtext';
    }

    protected function baseStatement(): string
    {
        return "\$table->longText('{$this->name}')";
    }

    public function toSchema(): array
    {
        return parent::toSchema() + ['language' => $this->language];
    }
}
