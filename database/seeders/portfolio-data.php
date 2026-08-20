<?php

// Included by DatabaseSeeder; expects Laravel helpers to be loaded.

$now = now();

$profile = [
    'name' => 'Enes Karaca',
    'headline' => 'Full-stack developer who builds the tools, not just the sites.',
    'intro' => 'I run Multichoice Agency, where I have shipped 50+ production sites and platforms for Dutch businesses — and the products behind them: a headless CMS, a CRM, AI agents, and ecommerce infrastructure. This portfolio runs on Manifold, a Laravel + Nuxt CMS I built and open-sourced.',
    'bio' => 'Full-stack developer met ruime praktijkervaring in het ontwerpen, bouwen en moderniseren van webplatforms en eigen softwareprojecten. Ik combineer React/Next.js en Vue/Nuxt met Node.js, TypeScript, PHP/Laravel, CI/CD en AI/LLM-integraties — van RAG-architectuur en modelselectie tot prompt engineering en productiegerichte deployment via GitHub Actions. Ik werk van architectuur tot productie en bewaak continuïteit en technische kwaliteit via Domain-Driven Design en code review.',
    'location' => 'Rotterdam',
    'email' => 'eneskaraca80@gmail.com',
    'github' => 'https://github.com/multidevagency',
    'linkedin' => 'https://linkedin.com/in/enes-karaca-bb460996',
    'cv' => 'manifold/cv-enes-karaca.pdf',
    'skills' => [
        ['name' => 'React / Next.js'], ['name' => 'Vue / Nuxt'], ['name' => 'Node.js'], ['name' => 'TypeScript'],
        ['name' => 'PHP / Laravel'], ['name' => 'Tailwind CSS'], ['name' => 'AI & RAG-architectuur'],
        ['name' => 'Groq / OpenAI / Gemini'], ['name' => 'GitHub Actions / CI-CD'], ['name' => 'Docker / Coolify'],
        ['name' => 'WordPress / WooCommerce'], ['name' => 'Domain-Driven Design'],
    ],
    'experience' => [
        [
            'role' => 'Full-Stack Developer', 'company' => 'Eigen softwareprojecten / Multichoice Agency', 'period' => '2016 – heden',
            'description' => 'Ontwerpt, bouwt en onderhoudt full-stack webplatforms met Next.js, Vue/Nuxt en Node.js — van architectuur en realisatie tot productie en beheer. Integreert AI-functionaliteit en LLM-API\'s in bestaande producten, moderniseert actieve WordPress- en WooCommerce-platforms met Node.js- en TypeScript-services, en bewaakt technische kwaliteit via Domain-Driven Design en code review. Begeleidt teamleden bij technische keuzes en uitvoering.',
        ],
        [
            'role' => 'Cloudmigratiespecialist', 'company' => 'Argeweb', 'period' => '2018 – 2019',
            'description' => 'Migreerde klantomgevingen, domeinen en e-mail van KPN naar de infrastructuur van Argeweb, met minimale downtime en zonder dataverlies. Coördineerde technische overgangen rechtstreeks met eindklanten binnen een strak migratieschema.',
        ],
    ],
    'education' => [
        ['name' => 'HBO Bachelor Informatica (in uitvoering)', 'institution' => 'LOI', 'period' => '2022 – heden'],
        ['name' => 'MBO 4 Human Technology', 'institution' => 'Techniek College Rotterdam', 'period' => '2014 – 2017'],
    ],
    'languages' => [
        ['name' => 'Nederlands', 'level' => 'Moedertaal'],
        ['name' => 'Turks', 'level' => 'Vloeiend'],
        ['name' => 'Engels', 'level' => 'Goed'],
    ],
];

DB::table('mf_globals')->updateOrInsert(['slug' => 'profile'], [
    'data' => json_encode($profile, JSON_UNESCAPED_UNICODE),
    'created_at' => $now, 'updated_at' => $now,
]);

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
