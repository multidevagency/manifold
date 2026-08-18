<?php

namespace Manifold\Cms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Process;
use InvalidArgumentException;
use Manifold\Cms\Registry;
use Manifold\Cms\Support\CollectionEditor;

class SchemaEditController extends Controller
{
    public function __construct(protected CollectionEditor $editor)
    {
        // Schema edits rewrite PHP source files — dev machines only, never a deployed admin.
        abort_unless(app()->environment(['local', 'testing']), 403, 'Schema editing is only available in the local environment.');
    }

    public function storeCollection(Request $request): JsonResponse
    {
        abort_unless($request->user('sanctum'), 401);
        $data = $request->validate(['name' => 'required|string|max:40']);

        try {
            $slug = $this->editor->createCollection($data['name']);
        } catch (InvalidArgumentException $e) {
            abort(422, $e->getMessage());
        }

        $this->migrateAndRefresh();

        return response()->json(['data' => ['slug' => $slug]], 201);
    }

    public function storeField(Request $request, string $collection): JsonResponse
    {
        abort_unless($request->user('sanctum'), 401);

        $col = app(Registry::class)->get($collection);
        abort_if($col === null, 404, "Unknown collection [{$collection}]");

        $data = $request->validate([
            'name' => 'required|string|max:40',
            'type' => 'required|string',
            'required' => 'boolean',
            'options' => 'array',
            'options.*' => 'string|max:40',
            'to' => 'nullable|string|max:60',
        ]);

        try {
            $this->editor->addField($col, $data);
        } catch (InvalidArgumentException $e) {
            abort(422, $e->getMessage());
        }

        $this->migrateAndRefresh();

        return response()->json(['data' => ['name' => $data['name']]], 201);
    }

    protected function migrateAndRefresh(): void
    {
        // A subprocess, not Artisan::call: this request booted with the
        // pre-edit classes, and PHP cannot reload a class it already loaded.
        $result = Process::path(base_path())->run([PHP_BINARY, 'artisan', 'manifold:migrate']);

        abort_unless($result->successful(), 500, 'manifold:migrate failed: '.$result->errorOutput());
    }
}
