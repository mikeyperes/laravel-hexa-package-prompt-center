<?php

namespace hexa_package_prompt_center\Prompts\Categories\Models;

use hexa_package_prompt_center\Prompts\Templates\Models\PromptTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromptCategory extends Model
{
    protected $table = 'prompt_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * @return HasMany
     */
    public function templates(): HasMany
    {
        return $this->hasMany(PromptTemplate::class, 'prompt_category_id');
    }

    /**
     * Get the default template for this category.
     *
     * @return PromptTemplate|null
     */
    public function defaultTemplate(): ?PromptTemplate
    {
        return $this->templates()->where('is_default', true)->first();
    }
}
