<?php

namespace Manifold\Cms\Fields;

abstract class Field
{
    protected bool $required = false;

    protected bool $unique = false;

    protected bool $index = false;

    protected mixed $default = null;

    protected bool $hasDefault = false;

    protected ?string $label = null;

    protected ?string $renamedFrom = null;

    protected bool $useAsTitle = false;

    protected array $rules = [];

    protected ?string $help = null;

    final protected function __construct(protected string $name) {}

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function required(bool $required = true): static
    {
        $this->required = $required;

        return $this;
    }

    public function unique(bool $unique = true): static
    {
        $this->unique = $unique;

        return $this;
    }

    public function index(bool $index = true): static
    {
        $this->index = $index;

        return $this;
    }

    public function default(mixed $value): static
    {
        $this->default = $value;
        $this->hasDefault = true;

        return $this;
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    // The differ cannot tell a rename from a drop+add, so renames must be declared.
    public function renamedFrom(string $old): static
    {
        $this->renamedFrom = $old;

        return $this;
    }

    public function useAsTitle(bool $use = true): static
    {
        $this->useAsTitle = $use;

        return $this;
    }

    public function rules(string|array $rules): static
    {
        $this->rules = array_merge($this->rules, (array) $rules);

        return $this;
    }

    public function help(string $text): static
    {
        $this->help = $text;

        return $this;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function column(): string
    {
        return $this->name;
    }

    public function previousColumn(): ?string
    {
        return $this->renamedFrom;
    }

    public function isUsedAsTitle(): bool
    {
        return $this->useAsTitle;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }

    public function hasDefault(): bool
    {
        return $this->hasDefault;
    }

    public function defaultValue(): mixed
    {
        return $this->default;
    }

    abstract public function type(): string;

    /** Base SQL type as reported by Schema::getColumns(), used for change detection. */
    abstract public function sqlType(): string;

    /** Migration builder statements for a generated migration file. */
    public function columnStatement(): string
    {
        $stmt = $this->baseStatement();

        if (! $this->required) {
            $stmt .= '->nullable()';
        }
        if ($this->hasDefault) {
            $stmt .= '->default('.var_export($this->default, true).')';
        }
        if ($this->unique) {
            $stmt .= '->unique()';
        } elseif ($this->index) {
            $stmt .= '->index()';
        }

        return $stmt;
    }

    abstract protected function baseStatement(): string;

    public function validationRules(bool $updating): array
    {
        $rules = $this->rules;

        if (! $this->required) {
            array_unshift($rules, 'nullable');
        } elseif ($updating) {
            array_unshift($rules, 'sometimes', 'required');
        } else {
            array_unshift($rules, 'required');
        }

        return $rules;
    }

    /** Value as stored -> value as served by the API. */
    public function fromDatabase(mixed $value): mixed
    {
        return $value;
    }

    /** Value as received -> value as stored. */
    public function toDatabase(mixed $value): mixed
    {
        return $value;
    }

    public function toSchema(): array
    {
        return array_filter([
            'name' => $this->name,
            'type' => $this->type(),
            'label' => $this->label ?? str($this->name)->headline()->toString(),
            'required' => $this->required,
            'unique' => $this->unique,
            'useAsTitle' => $this->useAsTitle,
            'default' => $this->hasDefault ? $this->default : null,
            'help' => $this->help,
        ], fn ($v) => $v !== null && $v !== false);
    }
}
