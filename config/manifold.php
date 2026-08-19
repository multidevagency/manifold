<?php

use App\Collections\Authors;
use App\Collections\CaseStudies;
use App\Collections\Categories;
use App\Collections\Pages;
use App\Collections\Posts;
use App\Collections\Products;
use App\Globals\Footer;
use App\Globals\Header;
use App\Globals\Profile;

return [

    'collections' => [
        Categories::class,
        Posts::class,
        Pages::class,
        Authors::class,
        Products::class,
        CaseStudies::class,
    ],

    'globals' => [
        Header::class,
        Footer::class,
        Profile::class,
    ],

    'route_prefix' => 'api/manifold',

    // Server-side only. Never expose to the browser.
    'anthropic_api_key' => env('ANTHROPIC_API_KEY'),
    'anthropic_model' => env('MANIFOLD_AI_MODEL', 'claude-opus-5'),

];
