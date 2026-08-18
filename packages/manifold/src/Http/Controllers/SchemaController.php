<?php

namespace Manifold\Cms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Manifold\Cms\Collections\Collection;
use Manifold\Cms\Registry;

class SchemaController extends Controller
{
    public function __invoke(Registry $registry): JsonResponse
    {
        return response()->json([
            'collections' => collect($registry->all())
                ->map(fn (Collection $c) => $c->toSchema())
                ->values(),
        ]);
    }
}
