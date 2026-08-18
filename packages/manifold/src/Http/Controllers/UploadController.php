<?php

namespace Manifold\Cms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            // No svg: it can carry scripts and uploads are served from the site origin.
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,avif,pdf,mp4,webm',
        ]);

        $path = $request->file('file')->store('manifold', 'public');

        return response()->json([
            'data' => ['path' => $path, 'url' => Storage::disk('public')->url($path)],
        ], 201);
    }
}
