<?php

namespace Manifold\Cms\Collections;

use Illuminate\Support\Str;
use Manifold\Cms\Fields\Field;
use Manifold\Cms\Fields\Relationship;

abstract class Collection
{
    protected string $slug = '';

    protected ?string $labelSingular = null;

    protected ?string $labelPlural = null;

    protected string $defaultSort = '-created_at';

    /** @return Field[] */
    abstract public function fields(): array;

    /**
     * Per-operation gates: read|create|update|delete => fn (?Authenticatable $user): bool.
     * Missing keys deny everyone except authenticated users.
     */
    public function access(): array
    {
        return [];
    }

    public function slug(): string
    {
        return $this->slug !== '' ? $this->slug : Str::of(class_basename(static::class))->snake('-')->toString();
    }

    public function table(): string
    {
        return 'mf_'.str_replace('-', '_', $this->slug());
    }

    public function labelSingular(): string
    {
        return $this->labelSingular ?? Str::of($this->slug())->replace('-', ' ')->singular()->headline()->toString();
    }

    public function labelPlural(): string
    {
        return $this->labelPlural ?? Str::of($this->slug())->replace('-', ' ')->headline()->toString();
    }

    public function defaultSort(): string
    {
        return $this->defaultSort;
    }

    public function titleField(): ?string
    {
        foreach ($this->fields() as $field) {
            if ($field->isUsedAsTitle()) {
                return $field->column();
            }
        }

        return null;
    }

    public function field(string $name): ?Field
    {
        foreach ($this->fields() as $field) {
            if ($field->name() === $name || $field->column() === $name) {
                return $field;
            }
        }

        return null;
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
            'labelSingular' => $this->labelSingular(),
            'labelPlural' => $this->labelPlural(),
            'titleField' => $this->titleField(),
            'defaultSort' => $this->defaultSort(),
            'fields' => array_map(fn (Field $f) => $f->toSchema() + ['column' => $f->column()], $this->fields()),
            'relationships' => collect($this->fields())
                ->whereInstanceOf(Relationship::class)
                ->mapWithKeys(fn (Relationship $f) => [$f->column() => $f->target()])
                ->all(),
        ];
    }
}
