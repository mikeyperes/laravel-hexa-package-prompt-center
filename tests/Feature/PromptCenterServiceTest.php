<?php

namespace Tests\Feature;

use hexa_core\Models\SystemLog;
use hexa_core\Services\SystemLogService;
use hexa_package_prompt_center\Prompts\Categories\Models\PromptCategory;
use hexa_package_prompt_center\Prompts\Categories\Services\PromptService;
use hexa_package_prompt_center\Prompts\Templates\Models\PromptTemplate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Tests\TestCase;

class PromptCenterServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->requireInstalledPackage("hexawebsystems/laravel-hexa-package-prompt-center", PromptService::class);
        Schema::dropIfExists("prompt_templates");
        Schema::dropIfExists("prompt_categories");
        Schema::create("prompt_categories", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("slug")->unique();
            $table->text("description")->nullable();
            $table->integer("sort_order")->default(0);
            $table->timestamps();
        });
        Schema::create("prompt_templates", function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger("prompt_category_id");
            $table->string("name");
            $table->string("slug")->unique();
            $table->text("body");
            $table->text("notes")->nullable();
            $table->boolean("is_default")->default(false);
            $table->timestamps();
        });
    }

    public function test_setting_default_unsets_other_templates_in_category(): void
    {
        $category = PromptCategory::create(["name" => "Articles", "slug" => "articles", "sort_order" => 1]);
        $first = PromptTemplate::create(["prompt_category_id" => $category->id, "name" => "First", "slug" => "first", "body" => "First prompt", "is_default" => true]);
        $second = PromptTemplate::create(["prompt_category_id" => $category->id, "name" => "Second", "slug" => "second", "body" => "Second prompt", "is_default" => false]);

        $logs = Mockery::mock(SystemLogService::class);
        $logs->shouldReceive("log")->once()->andReturn(new SystemLog());
        $this->app->instance(SystemLogService::class, $logs);

        $service = app(PromptService::class);
        $service->setDefault($second->id);

        $this->assertFalse($first->fresh()->is_default);
        $this->assertTrue($second->fresh()->is_default);
        $this->assertSame($second->id, $service->getDefault("articles")?->id);
        $this->assertSame(["First", "Second"], $service->getByCategory("articles")->pluck("name")->all());
    }
}
