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

$studies = [
    [
        'title' => 'Manifold — code-first headless CMS',
        'tagline' => 'Define a PHP class, get the schema, the API, and the admin. Built with Laravel 13 and Nuxt 4.',
        'category' => 'product', 'stack' => 'Laravel 13, Nuxt 4, Next.js, Tailwind v4, SQLite/MySQL',
        'featured' => true, 'sort_order' => 1,
        'summary' => 'An open-source headless CMS where collections are PHP classes: a schema differ generates reviewable migrations, the REST API and admin panel are projections of the code, and a typed JS client plus CLI connect any frontend. 20+ field types including layout builders, live preview with drafts, AI content generation, and this very portfolio as the demo frontend.',
        'body' => '<h2>The idea</h2><p>Payload CMS proved that schema-as-code is the right model. Manifold brings it to the Laravel ecosystem: one class per collection, and <code>php artisan manifold:migrate</code> diffs it against the live database to generate ordinary, reviewable Laravel migrations — renames declared explicitly so data survives.</p><h2>What I built</h2><p>The field type system (20+ types, from scalars to block-based layout builders), the schema differ, a Sanctum REST API with per-collection access closures and guest scoping, a schema-driven Nuxt admin with live preview and view-as-JSON, a zero-dependency JS client, a CLI that generates TypeScript discriminated unions from the schema, globals, uploads, and AI-assisted content via the Anthropic API.</p><h2>Proof</h2><p>30 feature tests, green CI, a VitePress docs site, and this portfolio — served by a Next.js frontend consuming the Manifold API, drafts previewed live inside the admin.</p>',
        'metrics' => [['value' => '20+', 'label' => 'field types'], ['value' => '30', 'label' => 'feature tests, green CI'], ['value' => '3', 'label' => 'packages: engine, JS client, CLI']],
        'repo_url' => 'https://github.com/multidevagency/manifold', 'live_url' => 'http://localhost:3001',
    ],
    [
        'title' => 'OEMLine — auto parts at catalog scale',
        'tagline' => 'Ecommerce platform for OEM car parts: half a million products, vehicle-aware search.',
        'category' => 'platform', 'stack' => 'Node.js (Fastify), Next.js, Nuxt, PostgreSQL, Elasticsearch, Coolify',
        'featured' => true, 'sort_order' => 2,
        'summary' => 'A multi-storefront auto-parts platform: a 530k-product catalog recategorised into a 2,000-node taxonomy, Elasticsearch-backed search, license-plate (VRM) vehicle lookup, supplier feed pipelines, and brand-visibility rules — running on self-hosted infrastructure.',
        'body' => '<h2>Scale problems, real answers</h2><p>OEMLine aggregates supplier feeds into one catalog of over half a million parts. The hard problems were data quality at scale: recategorising 528k products into a curated taxonomy, keeping unreliable supplier enrichment from poisoning it, and making search fast and vehicle-aware.</p><h2>Highlights</h2><p>Full Elasticsearch reindex pipeline (531k documents, zero bulk errors), license-plate lookup that scopes the entire catalog to one vehicle, cross-brand article-number resolution, and deploy pipelines on self-hosted Coolify infrastructure.</p>',
        'metrics' => [['value' => '530k+', 'label' => 'products in catalog'], ['value' => '2,068', 'label' => 'taxonomy leaf categories'], ['value' => '4', 'label' => 'apps: API, admin, 2 storefronts']],
        'repo_url' => '', 'live_url' => 'https://oemline.eu',
    ],
    [
        'title' => 'The Agency CRM',
        'tagline' => 'An agency operating system: CRM, invoicing, marketing automation, and an AI agent network.',
        'category' => 'ai', 'stack' => 'Vue 3, Node.js, PostgreSQL, MCP, Anthropic API',
        'featured' => true, 'sort_order' => 3,
        'summary' => 'A full agency platform — leads, deals, projects, hours, quotes and invoices, email sequences and campaign tooling — with an AI layer: an MCP server exposing 300+ tools so AI assistants can operate the whole CRM, plus an autonomous agent network for lead research and outreach.',
        'body' => '<h2>One system instead of six subscriptions</h2><p>The Agency CRM replaces the usual stack of separate tools with one platform: pipeline management, project and hour tracking, billing with Dutch VAT rules, marketing flows, and a knowledge base.</p><h2>The AI layer</h2><p>The interesting part is the MCP integration: the entire CRM is exposed as tools to AI assistants, so "create a quote from this project and send it" is a conversation, not a workflow. An orchestrated agent network handles lead scraping, enrichment, scoring, and personalised outreach.</p>',
        'metrics' => [['value' => '300+', 'label' => 'MCP tools exposed to AI'], ['value' => '7', 'label' => 'pipeline stages automated']],
        'repo_url' => '', 'live_url' => '',
    ],
    [
        'title' => 'AI Receptionist',
        'tagline' => 'A voice agent that answers the phone: SIP telephony meets OpenAI Realtime.',
        'category' => 'ai', 'stack' => 'LiveKit, OpenAI Realtime API, SIP, Python, TypeScript',
        'featured' => false, 'sort_order' => 4,
        'summary' => 'A production voice agent for real phone lines: incoming SIP calls bridge into LiveKit rooms where a realtime AI handles reception — answering questions, taking messages, and routing callers — with latency low enough to feel like a conversation.',
        'body' => '<h2>Voice is the hard modality</h2><p>Text chatbots are forgiving; phone calls are not. This project wires SIP trunks into LiveKit and streams audio to the OpenAI Realtime API, keeping round-trip latency conversational and handling barge-in, transfer, and voicemail scenarios.</p>',
        'metrics' => [['value' => '<1s', 'label' => 'conversational round-trip'], ['value' => '24/7', 'label' => 'availability']],
        'repo_url' => '', 'live_url' => '',
    ],
    [
        'title' => 'ParentPlaza',
        'tagline' => 'A parenting platform across web, dashboard, and native mobile.',
        'category' => 'platform', 'stack' => 'Next.js, React Native, Go, PHP, Docker',
        'featured' => false, 'sort_order' => 5,
        'summary' => 'A multi-app product: public platform, admin dashboard, and a React Native app sharing one backend — built and shipped as a coherent suite with authentication, content, and community features.',
        'body' => '<h2>One product, three surfaces</h2><p>ParentPlaza spans a public web platform, an operational dashboard, and a native mobile app. The engineering challenge is coherence: shared auth, shared content models, and release trains that keep three clients in sync with one backend.</p>',
        'metrics' => [['value' => '3', 'label' => 'client apps, one backend']],
        'repo_url' => '', 'live_url' => '',
    ],
    [
        'title' => '50+ sites for Dutch businesses',
        'tagline' => 'From wrap shops to healthcare: production websites and webshops, delivered end-to-end.',
        'category' => 'client-work', 'stack' => 'Next.js, Nuxt, WordPress, WooCommerce, Tailwind',
        'featured' => false, 'sort_order' => 6,
        'summary' => 'Through Multichoice Agency I have delivered 50+ production sites and shops: WrapmasterDH, ATA Home (WooCommerce), Matika, Kronos Horren, Volta Elektrotechniek, driving schools, healthcare providers, and more — design to deploy, SEO included.',
        'body' => '<h2>Volume teaches discipline</h2><p>Client work at this cadence forces reusable patterns: design systems, deploy pipelines, SEO baselines, and honest scoping. Every site ships with performance budgets and structured data, and the busiest shops run on WooCommerce or headless storefronts depending on what the business actually needs.</p>',
        'metrics' => [['value' => '50+', 'label' => 'sites in production']],
        'repo_url' => '', 'live_url' => '',
    ],
];

foreach ($studies as $s) {
    DB::table('mf_case_studies')->updateOrInsert(['slug' => str($s['title'])->slug()->toString()], [
        'title' => $s['title'], 'tagline' => $s['tagline'], 'category' => $s['category'],
        'stack' => $s['stack'], 'year' => 2026, 'featured' => $s['featured'],
        'sort_order' => $s['sort_order'], 'summary' => $s['summary'], 'body' => $s['body'],
        'metrics' => json_encode($s['metrics']), 'hero' => null,
        'repo_url' => $s['repo_url'] ?: null, 'live_url' => $s['live_url'] ?: null,
        'status' => 'published',
        'meta_title' => $s['title'], 'meta_description' => $s['tagline'],
        'created_at' => $now, 'updated_at' => $now,
    ]);
}

echo 'case studies: '.DB::table('mf_case_studies')->count();
