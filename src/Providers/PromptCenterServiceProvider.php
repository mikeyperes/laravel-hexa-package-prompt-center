<?php

namespace hexa_package_prompt_center\Providers;

use Illuminate\Support\ServiceProvider;

class PromptCenterServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/prompt-center.php', 'prompt-center');
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        if (!config('prompt-center.enabled', true)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../../routes/prompt-center.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'prompt-center');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        if (class_exists(\hexa_core\Services\PackageRegistryService::class)) {
            app(\hexa_core\Services\PackageRegistryService::class)->registerPackage('prompt-center', 'hexawebsystems/laravel-hexa-package-prompt-center', [
                'title' => 'Prompt Center',
                'description' => 'Central prompt library for categorized AI prompt templates and defaults.',
                'docsSlug' => 'prompt-center',
                'instructions' => [
                    'Use Prompt Center to define reusable prompts for downstream packages and workflows.',
                ],
            ]);
        }

        // Settings card
        view()->composer('settings.index', function ($view) {
            $view->getFactory()->startPush('settings-cards',
                view('prompt-center::partials.settings-card')->render());
        });

        // Docs
        if (class_exists(\hexa_core\Services\DocumentationService::class)) {
            try {
                app(\hexa_core\Services\DocumentationService::class)->register('prompt-center', 'Prompt Center', 'hexawebsystems/laravel-hexa-package-prompt-center', [
                    ['title' => 'Overview', 'content' => 'AI prompt management with categories and named templates. Each category has a default prompt that auto-loads when needed.'],
                    ['title' => 'Public API', 'content' => '<code>PromptService::getDefault($slug)</code>, <code>getByCategory($slug)</code>, <code>setDefault($id)</code>'],
                ]);
            } catch (\Throwable $e) {}
        }
    }
}
