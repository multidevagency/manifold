<?php

return [

    // Collection classes to register. Each becomes a table, a REST API, and an admin UI.
    'collections' => [
        // App\Collections\Posts::class,
    ],

    'route_prefix' => 'api/manifold',

    // Server-side only. Never expose to the browser.
    'anthropic_api_key' => env('ANTHROPIC_API_KEY'),
    'anthropic_model' => env('MANIFOLD_AI_MODEL', 'claude-opus-5'),

];
