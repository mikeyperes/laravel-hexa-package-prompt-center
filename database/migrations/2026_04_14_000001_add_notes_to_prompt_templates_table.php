<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('prompt_templates') && !Schema::hasColumn('prompt_templates', 'notes')) {
            Schema::table('prompt_templates', function (Blueprint $table) {
                $table->text('notes')->nullable()->after('body');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('prompt_templates', 'notes')) {
            Schema::table('prompt_templates', function (Blueprint $table) {
                $table->dropColumn('notes');
            });
        }
    }
};
