<?php

namespace Manifold\Cms\Fields;

use Illuminate\Validation\Rule;

class Select extends Field
{
    protected array $options = [];

    /** Accepts ['a', 'b'] or ['a' => 'Label A', ...]. */
    public function options(array $options): static
    {
        $this->options = array_is_list($options)
            ? array_combine($options, array_map(fn ($o) => str($o)->headline()->toString(), $options))
            : $options;

        return $this;
    }

    public function type(): string
    {
        return 'select';
    }

    public function sqlType(): string
    {
        return 'varchar';
    }

    protected function baseStatement(): string
    {
        return "\$table->string('{$this->name}', 64)";
    }

    public function validationRules(bool $updating): array
    {
        return array_merge(parent::validationRules($updating), [Rule::in(array_keys($this->options))]);
    }

    public function toSchema(): array
    {
        return parent::toSchema() + ['options' => $this->options];
    }
}
