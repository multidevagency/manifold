<?php

use App\Collections\Categories;
use App\Collections\Pages;
use App\Collections\Posts;

return [

    'collections' => [
        Categories::class,
        Posts::class,
        Pages::class,
    ],

    'route_prefix' => 'api/manifold',

];
