<?php

namespace Manifold\Cms\Globals;

use Illuminate\Support\Str;
use Manifold\Cms\Fields\Field;

/** Single-instance document (site header, footer, settings): one row, no list. */
abstract class GlobalDocument
{
    protected string $slug = '';

    protected ?string $label = null;

    /** @return Field[] */
    abstract public function fields(): array;

    /** read|update => fn (?user): bool. Missing keys deny guests. */
    public function access(): array
    {
        return [];
    }

    public function slug(): string
    {
        return $this->slug !== '' ? $this->slug : Str::of(class_basename(static::class))->snake('-')->toString();
    }

    public function label(): string
    {
        return $this->label ?? Str::of($this->slug())->replace('-', ' ')->headline()->toString();
    }

    public function columnFields(): array
    {
        $flat = [];
        $walk = function (array $fields) use (&$flat, &$walk) {
            foreach ($fields as $field) {
                $field->hasColumn() ? $flat[] = $field : $walk($field->children());
            }
        };
        $walk($this->fields());

        return $flat;
    }

    public function allows(string $operation, mixed $user): bool
    {
        $gate = $this->access()[$operation] ?? null;

        return $gate ? (bool) $gate($user) : $user !== null;
    }

    public function toSchema(): array
    {
        return [
            'slug' => $this->slug(),
            'label' => $this->label(),
            'fields' => array_map(fn (Field $f) => $f->toSchema() + ['column' => $f->column()], $this->fields()),
        ];
    }
}
