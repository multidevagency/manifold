<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Demo Admin',
            'email' => 'admin@manifold.test',
            'password' => bcrypt('password'),
        ]);

        $now = now();

        $categories = collect([
            ['name' => 'Engineering', 'slug' => 'engineering', 'description' => 'Technical deep-dives and architecture notes.'],
            ['name' => 'Design', 'slug' => 'design', 'description' => 'Interface craft, typography, and process.'],
            ['name' => 'Company', 'slug' => 'company', 'description' => 'Announcements and behind-the-scenes.'],
        ])->map(function ($row) use ($now) {
            $row['created_at'] = $row['updated_at'] = $now;

            return $row;
        });

        DB::table('mf_categories')->insert($categories->all());
        $catIds = DB::table('mf_categories')->pluck('id', 'slug');

        $posts = [
            ['Schema as code: why your CMS should generate its own migrations', 'engineering', 'published', true, 12],
            ['Building a field type system with fluent PHP', 'engineering', 'published', false, 9],
            ['The admin panel nobody had to build', 'design', 'published', true, 5],
            ['Designing empty states that teach the product', 'design', 'review', false, 3],
            ['Introducing Manifold', 'company', 'published', true, 15],
            ['What we learned porting Payload concepts to Laravel', 'engineering', 'draft', false, 1],
            ['Access control as closures, not config', 'engineering', 'published', false, 7],
            ['Roadmap: uploads, drafts, and localization', 'company', 'draft', false, 0],
        ];

        foreach ($posts as [$title, $cat, $status, $featured, $daysAgo]) {
            DB::table('mf_posts')->insert([
                'title' => $title,
                'slug' => str($title)->slug(),
                'excerpt' => 'A short introduction to "'.$title.'" — what it covers and why it matters.',
                'body' => '<h2>'.$title.'</h2><p>Full article body for the demo dataset. Replace with real content.</p><p>Manifold generated the table, the API, and the admin form for this entry from a single PHP class.</p>',
                'status' => $status,
                'category_id' => $catIds[$cat],
                'featured' => $featured,
                'published_at' => $status === 'published' ? $now->copy()->subDays($daysAgo) : null,
                'created_at' => $now->copy()->subDays($daysAgo),
                'updated_at' => $now->copy()->subDays($daysAgo),
            ]);
        }

        foreach ([['About', 'published'], ['Contact', 'published'], ['Privacy Policy', 'draft']] as [$title, $status]) {
            DB::table('mf_pages')->insert([
                'title' => $title,
                'slug' => str($title)->slug(),
                'content' => '<h1>'.$title.'</h1><p>Demo page content.</p>',
                'status' => $status,
                'meta_title' => $title.' — Manifold Demo',
                'hero' => json_encode(['heading' => $title, 'subheading' => 'A demo page built with the layout builder.', 'image' => null]),
                'layout' => json_encode([
                    ['blockType' => 'content', 'body' => '<p>This layout was assembled from blocks in the admin.</p>'],
                    ['blockType' => 'cta', 'label' => 'Read the blog', 'url' => '/'],
                ]),
                'faq' => json_encode([
                    ['question' => 'What renders this page?', 'answer' => 'The Next.js example, from block data served by Manifold.'],
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('mf_globals')->insert([
            [
                'slug' => 'header',
                'data' => json_encode([
                    'brand' => 'MANIFOLD',
                    'nav' => [
                        ['label' => 'Blog', 'url' => '/'],
                        ['label' => 'Shop', 'url' => '/shop'],
                        ['label' => 'About', 'url' => '/about'],
                        ['label' => 'Contact', 'url' => '/contact'],
                    ],
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'slug' => 'footer',
                'data' => json_encode([
                    'tagline' => 'A demo site rendered by Next.js, managed by Manifold.',
                    'links' => [
                        ['label' => 'GitHub', 'url' => 'https://github.com/multidevagency/manifold'],
                        ['label' => 'Admin', 'url' => 'http://localhost:3000'],
                        ['label' => 'llms.txt', 'url' => '/llms.txt'],
                    ],
                    'copyright' => '© 2026 Manifold. MIT licensed.',
                ]),
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);

        $products = [
            ['Field Notes Notebook', 12.50, 'stationery'],
            ['Schematic Poster A1', 29.00, 'print'],
            ['Manifold Mug', 18.00, 'goods'],
            ['Blueprint Desk Mat', 39.00, 'goods'],
        ];

        $assetDir = database_path('seed-assets/cases');
        if (is_dir($assetDir)) {
            @mkdir(storage_path('app/public/manifold/cases'), 0755, true);
            foreach (glob($assetDir.'/*.jpg') as $asset) {
                copy($asset, storage_path('app/public/manifold/cases/'.basename($asset)));
            }
        }

        require __DIR__.'/portfolio-data.php';

        foreach ($products as $i => [$title, $price, $kind]) {
            DB::table('mf_products')->insert([
                'title' => $title,
                'slug' => str($title)->slug(),
                'excerpt' => 'Demo product — '.$kind.'.',
                'description' => '<p>'.$title.' from the Manifold demo shop.</p>',
                'price' => $price,
                'status' => 'published',
                'in_stock' => true,
                'variants' => json_encode($i === 0 ? [
                    ['name' => 'Dot grid', 'sku' => 'FN-DOT', 'price' => null, 'in_stock' => true],
                    ['name' => 'Ruled', 'sku' => 'FN-RUL', 'price' => 13.50, 'in_stock' => false],
                ] : []),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
