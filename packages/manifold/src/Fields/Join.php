<?php

namespace Manifold\Cms\Fields;

/** Read-only reverse relationship: entries in $to whose $via field points here. */
class Join extends Field
{
    protected string $to = '';

    protected string $via = '';

    public function to(string $collectionSlug): static
    {
        $this->to = $collectionSlug;

        return $this;
    }

    public function via(string $fieldName): static
    {
        $this->via = $fieldName;

        return $this;
    }

    public function hasColumn(): bool
    {
        return false;
    }

    public function type(): string
    {
        return 'join';
    }

    public function sqlType(): string
    {
        return '';
    }

    protected function baseStatement(): string
    {
        return '';
    }

    public function toSchema(): array
    {
        return parent::toSchema() + ['to' => $this->to, 'via' => $this->via.'_id'];
    }
}
