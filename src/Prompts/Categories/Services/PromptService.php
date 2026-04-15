<?php

namespace hexa_package_prompt_center\Prompts\Categories\Services;

use hexa_package_prompt_center\Prompts\Categories\Models\PromptCategory;
use hexa_package_prompt_center\Prompts\Templates\Models\PromptTemplate;
use Illuminate\Support\Collection;

class PromptService
{
    /**
     * Get the default prompt template for a category by slug.
     *
     * @param string $categorySlug
     * @return PromptTemplate|null
     */
    public function getDefault(string $categorySlug): ?PromptTemplate
    {
        $category = PromptCategory::where('slug', $categorySlug)->first();
        if (!$category) return null;

        return $category->defaultTemplate();
    }

    /**
     * Get a prompt template by stable slug/key.
     *
     * @param string $templateSlug
     * @return PromptTemplate|null
     */
    public function getByTemplateSlug(string $templateSlug): ?PromptTemplate
    {
        return PromptTemplate::where('slug', $templateSlug)->first();
    }

    /**
     * Get all prompt templates for a category.
     *
     * @param string $categorySlug
     * @return Collection
     */
    public function getByCategory(string $categorySlug): Collection
    {
        $category = PromptCategory::where('slug', $categorySlug)->first();
        if (!$category) return collect();

        return $category->templates()->orderBy('name')->get();
    }

    /**
     * Set a prompt template as the default for its category.
     * Unsets all other defaults in the same category.
     *
     * @param int $templateId
     * @return void
     */
    public function setDefault(int $templateId): void
    {
        $template = PromptTemplate::findOrFail($templateId);

        // Unset others in same category
        PromptTemplate::where('prompt_category_id', $template->prompt_category_id)
            ->where('id', '!=', $template->id)
            ->update(['is_default' => false]);

        $template->update(['is_default' => true]);

        if (function_exists('hexaLog')) {
            hexaLog('prompt-center', 'default_set', "Default prompt set: {$template->name} in category #{$template->prompt_category_id}");
        }
    }

    /**
     * Get all categories with their templates.
     *
     * @return Collection
     */
    public function getAllCategories(): Collection
    {
        return PromptCategory::with('templates')
            ->orderBy('sort_order')
            ->get();
    }
}
