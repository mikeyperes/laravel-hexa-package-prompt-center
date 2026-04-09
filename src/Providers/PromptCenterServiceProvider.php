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
            $registry = app(\hexa_core\Services\PackageRegistryService::class);
            $registry->registerSidebarLink('prompt-center.index', 'Prompts', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'Prompt Center', 'prompt-center', 60);
            $registry->registerSidebarLink('prompt-center.settings', 'Settings', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.11 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'Prompt Center', 'prompt-center', 61);
            if (method_exists($registry, 'registerPackage')) {
                $registry->registerPackage('prompt-center', 'hexawebsystems/laravel-hexa-package-prompt-center', [
                'title' => 'Prompt Center',
                'description' => 'Central prompt library for categorized AI prompt templates and defaults.',
                'settingsRoute' => 'prompt-center.settings',
                'docsSlug' => 'prompt-center',
                'instructions' => [
                    'Use Prompt Center to define reusable prompts for downstream packages and workflows.',
                ],
                ]);
            }
        }

        // Legacy settings-card push removed — core renders package cards from registry

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
