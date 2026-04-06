<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            ['name' => 'General Article Spin', 'slug' => 'general-article-spin', 'sort_order' => 1],
            ['name' => 'Local News', 'slug' => 'local-news', 'sort_order' => 2],
            ['name' => 'Tabloid', 'slug' => 'tabloid', 'sort_order' => 3],
            ['name' => 'Press Release', 'slug' => 'press-release', 'sort_order' => 4],
            ['name' => 'PR Article', 'slug' => 'pr-article', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            if (!DB::table('prompt_categories')->where('slug', $cat['slug'])->exists()) {
                DB::table('prompt_categories')->insert(array_merge($cat, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('prompt_categories')->whereIn('slug', [
            'general-article-spin', 'local-news', 'tabloid', 'press-release', 'pr-article',
        ])->delete();
    }
};
