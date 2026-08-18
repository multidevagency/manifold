<?php

namespace Manifold\Cms\Support;

use Illuminate\Support\Facades\Schema;
use Manifold\Cms\Collections\Collection;

class SchemaDiffer
{
    /**
     * Returns [] when the table matches, otherwise:
     * ['create' => true] or ['add' => Field[], 'rename' => Field[], 'change' => Field[], 'drop' => string[]].
     */
    public function diff(Collection $collection): array
    {
        $table = $collection->table();

        if (! Schema::hasTable($table)) {
            return ['create' => true];
        }

        $live = collect(Schema::getColumns($table))->keyBy('name');
        $reserved = ['id', 'created_at', 'updated_at'];

        $add = [];
        $rename = [];
        $change = [];
        $seen = $reserved;

        foreach ($collection->columnFields() as $field) {
            $column = $field->column();
            $seen[] = $column;

            if ($live->has($column)) {
                if (! $this->typeMatches($field->sqlType(), $live[$column]['type_name'])) {
                    $change[] = $field;
                }

                continue;
            }

            if ($field->previousColumn() && $live->has($field->previousColumn())) {
                $rename[] = $field;
                $seen[] = $field->previousColumn();

                continue;
            }

            $add[] = $field;
        }

        $drop = $live->keys()->diff($seen)->values()->all();

        if (! $add && ! $rename && ! $change && ! $drop) {
            return [];
        }

        return compact('add', 'rename', 'change', 'drop');
    }

    protected function typeMatches(string $wanted, string $actual): bool
    {
        // SQLite reports affinity, not declared type; treat its text/numeric families as compatible.
        $families = [
            'varchar' => ['varchar', 'text'],
            'text' => ['text', 'varchar'],
            'longtext' => ['longtext', 'text'],
            'bigint' => ['bigint', 'integer', 'int'],
            'tinyint' => ['tinyint', 'integer', 'int', 'boolean'],
            'decimal' => ['decimal', 'numeric', 'float', 'double'],
            'datetime' => ['datetime', 'timestamp'],
            'date' => ['date', 'datetime'],
            'json' => ['json', 'jsonb', 'text', 'longtext'],
        ];

        return in_array(strtolower($actual), $families[$wanted] ?? [$wanted], true);
    }
}
