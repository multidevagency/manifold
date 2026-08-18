<?php

namespace Manifold\Cms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Manifold\Cms\Globals\GlobalDocument;
use Manifold\Cms\Registry;
use Manifold\Cms\Support\GlobalRepository;

class GlobalController extends Controller
{
    public function __construct(
        protected Registry $registry,
        protected GlobalRepository $globals,
    ) {}

    public function show(Request $request, string $slug): JsonResponse
    {
        $global = $this->resolve($request, $slug, 'read');

        return response()->json(['data' => $this->globals->get($global)]);
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        $global = $this->resolve($request, $slug, 'update');

        return response()->json(['data' => $this->globals->update($global, $request->all())]);
    }

    protected function resolve(Request $request, string $slug, string $operation): GlobalDocument
    {
        $global = $this->registry->getGlobal($slug);
        abort_if($global === null, 404, "Unknown global [{$slug}]");

        $user = $request->user('sanctum');
        abort_unless($global->allows($operation, $user), $user ? 403 : 401);

        return $global;
    }
}
