<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prompt_templates')) {
            Schema::create('prompt_templates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('prompt_category_id')->constrained('prompt_categories')->onDelete('cascade');
                $table->string('name');
                $table->longText('body');
                $table->boolean('is_default')->default(false);
                $table->timestamps();

                $table->index(['prompt_category_id', 'is_default']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_templates');
    }
};
