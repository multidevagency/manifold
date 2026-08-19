<?php

// Included by DatabaseSeeder; expects Laravel helpers to be loaded.

$now = now();

DB::table('mf_globals')->updateOrInsert(['slug' => 'profile'], ['data' => json_encode([
    'name' => 'Enes Karaca',
    'headline' => 'Full-stack developer who builds the tools, not just the sites.',
    'intro' => 'I run Multichoice Agency, where I have shipped 50+ production sites and platforms for Dutch businesses — and the products behind them: a headless CMS, a CRM, AI agents, and ecommerce infrastructure. This portfolio runs on Manifold, a Laravel + Nuxt CMS I built and open-sourced.',
    'email' => 'info@oemline.eu',
    'github' => 'https://github.com/multidevagency',
    'skills' => [
        ['name' => 'Laravel'], ['name' => 'PHP'], ['name' => 'Nuxt / Vue'], ['name' => 'Next.js / React'],
        ['name' => 'TypeScript'], ['name' => 'MySQL / Postgres'], ['name' => 'Elasticsearch'],
        ['name' => 'Docker / Coolify'], ['name' => 'AI integration'], ['name' => 'WordPress / WooCommerce'],
    ],
]), 'created_at' => $now, 'updated_at' => $now]);

DB::table('mf_globals')->where('slug', 'header')->update(['data' => json_encode([
    'brand' => 'ENES.K',
    'nav' => [
        ['label' => 'Work', 'url' => '/work'],
        ['label' => 'Blog', 'url' => '/'],
        ['label' => 'About', 'url' => '/about'],
        ['label' => 'Contact', 'url' => '/contact'],
    ],
]), 'updated_at' => $now]);

$rows = json_decode(file_get_contents(__DIR__.'/case-studies-data.json'), true) ?? [];

foreach ($rows as $row) {
    unset($row['id']);
    $row['created_at'] = $row['updated_at'] = $now;
    DB::table('mf_case_studies')->updateOrInsert(['slug' => $row['slug']], $row);
}

echo 'case studies: '.DB::table('mf_case_studies')->count();
