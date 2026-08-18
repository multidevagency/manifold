<?php

namespace Manifold\Cms\Fields;

use Illuminate\Support\Facades\Storage;

class Upload extends Field
{
    public function type(): string
    {
        return 'upload';
    }

    public function sqlType(): string
    {
        return 'varchar';
    }

    protected function baseStatement(): string
    {
        return "\$table->string('{$this->name}')";
    }

    public function fromDatabase(mixed $value): mixed
    {
        return $value === null ? null : ['path' => $value, 'url' => Storage::disk('public')->url($value)];
    }

    public function toDatabase(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_array($value) ? ($value['path'] ?? null) : $value;
    }

    public function validationRules(bool $updating): array
    {
        return parent::validationRules($updating);
    }
}
