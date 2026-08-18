<?php

use Illuminate\Support\Facades\Route;
use Manifold\Cms\Http\Controllers\AuthController;
use Manifold\Cms\Http\Controllers\EntryController;
use Manifold\Cms\Http\Controllers\SchemaController;

Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

// Guests reach these too; collection access() gates + guestFilters() decide what they see.
Route::get('schema', SchemaController::class);
Route::get('{collection}', [EntryController::class, 'index']);
Route::post('{collection}', [EntryController::class, 'store']);
Route::get('{collection}/{id}', [EntryController::class, 'show'])->whereNumber('id');
Route::patch('{collection}/{id}', [EntryController::class, 'update'])->whereNumber('id');
Route::delete('{collection}/{id}', [EntryController::class, 'destroy'])->whereNumber('id');
