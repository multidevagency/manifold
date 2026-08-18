<?php

namespace Manifold\Cms\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Manifold\Cms\Globals\GlobalDocument;

class GlobalRepository
{
    public function get(GlobalDocument $global): array
    {
        $row = DB::table('mf_globals')->where('slug', $global->slug())->first();
        $data = $row ? (json_decode($row->data, true) ?? []) : [];

        foreach ($global->columnFields() as $field) {
            $column = $field->column();
            $data[$column] = $field->fromDatabase($data[$column] ?? null);
        }

        return $data;
    }

    public function update(GlobalDocument $global, array $input): array
    {
        $rules = [];
        foreach ($global->columnFields() as $field) {
            $rules[$field->column()] = $field->validationRules(updating: true);
        }

        $data = Validator::make($input, array_intersect_key($rules, $input))->validate();

        $current = $this->rawData($global);
        foreach ($global->columnFields() as $field) {
            $column = $field->column();
            if (array_key_exists($column, $data)) {
                $current[$column] = $field->toDatabase($data[$column]);
            }
        }

        DB::table('mf_globals')->updateOrInsert(
            ['slug' => $global->slug()],
            ['data' => json_encode($current), 'updated_at' => now(), 'created_at' => now()],
        );

        return $this->get($global);
    }

    protected function rawData(GlobalDocument $global): array
    {
        $row = DB::table('mf_globals')->where('slug', $global->slug())->first();

        return $row ? (json_decode($row->data, true) ?? []) : [];
    }
}
