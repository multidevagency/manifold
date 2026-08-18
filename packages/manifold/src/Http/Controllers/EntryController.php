<?php

namespace Manifold\Cms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Registry;
use Manifold\Cms\Support\EntryRepository;

class EntryController extends Controller
{
    public function __construct(
        protected Registry $registry,
        protected EntryRepository $entries,
    ) {}

    public function index(Request $request, string $collection): JsonResponse
    {
        $col = $this->resolve($request, $collection, 'read');

        $params = $request->all();
        foreach ($this->guestFilters($request, $col) as $column => $value) {
            $params['filter'][$column] = $value;
        }

        return response()->json($this->entries->paginate($col, $params));
    }

    public function show(Request $request, string $collection, int $id): JsonResponse
    {
        $col = $this->resolve($request, $collection, 'read');
        $entry = $this->entries->find($col, $id);

        foreach ($this->guestFilters($request, $col) as $column => $value) {
            if ($entry !== null && ($entry[$column] ?? null) !== $value) {
                $entry = null;
            }
        }

        abort_if($entry === null, 404);

        return response()->json(['data' => $entry]);
    }

    public function store(Request $request, string $collection): JsonResponse
    {
        $col = $this->resolve($request, $collection, 'create');

        return response()->json(['data' => $this->entries->create($col, $request->all())], 201);
    }

    public function update(Request $request, string $collection, int $id): JsonResponse
    {
        $col = $this->resolve($request, $collection, 'update');

        abort_if($this->entries->find($col, $id) === null, 404);

        return response()->json(['data' => $this->entries->update($col, $id, $request->all())]);
    }

    public function destroy(Request $request, string $collection, int $id): JsonResponse
    {
        $col = $this->resolve($request, $collection, 'delete');

        abort_if($this->entries->find($col, $id) === null, 404);
        $this->entries->delete($col, $id);

        return response()->json(null, 204);
    }

    protected function resolve(Request $request, string $slug, string $operation): Collection
    {
        $collection = $this->registry->get($slug);

        abort_if($collection === null, 404, "Unknown collection [{$slug}]");

        $user = $request->user('sanctum');
        abort_unless($collection->allows($operation, $user), $user ? 403 : 401);

        return $collection;
    }

    protected function guestFilters(Request $request, Collection $collection): array
    {
        return $request->user('sanctum') ? [] : $collection->guestFilters();
    }
}
