<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prompt_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('prompt_templates', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
        });

        if (Schema::hasColumn('prompt_templates', 'slug')) {
            DB::table('prompt_templates')
                ->orderBy('id')
                ->get(['id', 'name', 'slug'])
                ->each(function ($template) {
                    if (!empty($template->slug)) {
                        return;
                    }

                    $base = Str::slug($template->name ?: 'prompt');
                    $slug = $base !== '' ? $base : 'prompt-' . $template->id;
                    $suffix = 1;

                    while (DB::table('prompt_templates')
                        ->where('slug', $slug)
                        ->where('id', '!=', $template->id)
                        ->exists()) {
                        $slug = $base . '-' . $suffix;
                        $suffix++;
                    }

                    DB::table('prompt_templates')
                        ->where('id', $template->id)
                        ->update(['slug' => $slug]);
                });
        }

        Schema::table('prompt_templates', function (Blueprint $table) {
            if (Schema::hasColumn('prompt_templates', 'slug')) {
                $table->unique('slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prompt_templates', function (Blueprint $table) {
            if (Schema::hasColumn('prompt_templates', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
