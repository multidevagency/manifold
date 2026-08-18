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
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
