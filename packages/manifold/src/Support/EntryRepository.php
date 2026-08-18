<?php

namespace Manifold\Cms\Support;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Fields\Slug;

class EntryRepository
{
    public function query(Collection $collection): Builder
    {
        return DB::table($collection->table());
    }

    public function paginate(Collection $collection, array $params): array
    {
        $query = $this->query($collection);

        if (! empty($params['search']) && ($title = $collection->titleField())) {
            $query->where($title, 'like', '%'.$params['search'].'%');
        }

        foreach ($params['filter'] ?? [] as $column => $value) {
            if ($collection->field($column)) {
                $query->where($column, $value);
            }
        }

        $sort = $params['sort'] ?? $collection->defaultSort();
        $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $column = ltrim($sort, '-');
        if ($column !== 'id' && ! in_array($column, ['created_at', 'updated_at']) && ! $collection->field($column)) {
            $column = 'id';
        }

        $perPage = min((int) ($params['perPage'] ?? 25), 100);
        $page = max((int) ($params['page'] ?? 1), 1);
        $total = (clone $query)->count();

        $rows = $query->orderBy($column, $direction)
            ->forPage($page, $perPage)
            ->get()
            ->map(fn ($row) => $this->serialize($collection, (array) $row));

        return [
            'data' => $rows,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'lastPage' => max((int) ceil($total / $perPage), 1),
            ],
        ];
    }

    public function find(Collection $collection, int $id): ?array
    {
        $row = $this->query($collection)->find($id);

        return $row ? $this->serialize($collection, (array) $row) : null;
    }

    public function create(Collection $collection, array $input): array
    {
        foreach ($collection->columnFields() as $field) {
            if ($field->hasDefault() && ! array_key_exists($field->column(), $input) && ! array_key_exists($field->name(), $input)) {
                $input[$field->column()] = $field->defaultValue();
            }
        }

        $data = $this->validate($collection, $input, updating: false);
        $data = $this->prepare($collection, $data);
        $data['created_at'] = $data['updated_at'] = now();

        $id = $this->query($collection)->insertGetId($data);

        return $this->find($collection, $id);
    }

    public function update(Collection $collection, int $id, array $input): array
    {
        $data = $this->validate($collection, $input, updating: true, ignoreId: $id);
        $data = $this->prepare($collection, $data, existingId: $id);
        $data['updated_at'] = now();

        $this->query($collection)->where('id', $id)->update($data);

        return $this->find($collection, $id);
    }

    public function delete(Collection $collection, int $id): void
    {
        $this->query($collection)->where('id', $id)->delete();
    }

    protected function validate(Collection $collection, array $input, bool $updating, ?int $ignoreId = null): array
    {
        foreach ($collection->columnFields() as $field) {
            if ($field->column() !== $field->name()
                && array_key_exists($field->name(), $input)
                && ! array_key_exists($field->column(), $input)) {
                $input[$field->column()] = $input[$field->name()];
                unset($input[$field->name()]);
            }
        }

        $rules = [];

        foreach ($collection->columnFields() as $field) {
            $fieldRules = $field->validationRules($updating);

            if ($field->isUnique()) {
                $fieldRules[] = Rule::unique($collection->table(), $field->column())->ignore($ignoreId);
            }

            if ($field instanceof Slug && ! $updating) {
                // Auto-generated after validation, so absence is not an error.
                $fieldRules = array_map(fn ($r) => $r === 'required' ? 'nullable' : $r, $fieldRules);
            }

            $rules[$field->column()] = array_values($fieldRules);
        }

        $data = Validator::make($input, $updating ? array_intersect_key($rules, $input) : $rules)->validate();

        return array_intersect_key($data, array_flip(array_map(fn ($f) => $f->column(), $collection->columnFields())));
    }

    protected function prepare(Collection $collection, array $data, ?int $existingId = null): array
    {
        foreach ($collection->columnFields() as $field) {
            $column = $field->column();

            if ($field instanceof Slug) {
                $source = $field->sourceField();
                if (empty($data[$column]) && $source && ! empty($data[$source])) {
                    $data[$column] = $this->uniqueSlug($collection, $column, Str::slug($data[$source]), $existingId);
                }
            }

            if (array_key_exists($column, $data)) {
                $data[$column] = $field->toDatabase($data[$column]);
            }
        }

        return $data;
    }

    protected function uniqueSlug(Collection $collection, string $column, string $base, ?int $ignoreId): string
    {
        $slug = $base;
        $n = 2;

        while ($this->query($collection)->where($column, $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$n}";
            $n++;
        }

        return $slug;
    }

    protected function serialize(Collection $collection, array $row): array
    {
        foreach ($collection->columnFields() as $field) {
            $column = $field->column();
            if (array_key_exists($column, $row)) {
                $row[$column] = $field->fromDatabase($row[$column]);
            }
        }

        return $row;
    }
}
